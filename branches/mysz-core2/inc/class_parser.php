<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide class for parsing entry body
 *
 * PHP version 5
 *
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */

/**
 * Class for parsing entries.
 *
 * Parsing entry body, and delete unallowed html tags, or unallowed attributes
 * (like onclick/onmouseover etc, which provides tools for any kind of XSS
 * (Cross Site Scripting).
 * Other benefit from this class is smart replacing new line chars on html
 * < br /> tag.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class Parser {
    /**
     * Constance
     *
     * Holds what virtual tag is used if source is not well formed xml.
     *
     * @access public
     */
    const   virttag             = 'corecms:virtual';

    /**
     * Define when replace new line chars on html < br/>
     *
     * Used by Parse::cdata()
     *
     * @internal
     * @var    integer
     * @access private
     */
    private $nl2br              = 0;

    /**
     * Hold tags which content is not nl2br'ed
     *
     * Doesn't affect child of these tags.
     * 
     * @var    array 
     * @access private
     */
    private $safe               = array('style', 'ul', 'ol', 'dl', 'html');

    /**
     * Hold tags which content (and theyre childs too).
     *
     * @var    array
     * @access private
     */
    private $safe_tree          = array('head', 'script', 'pre');

    /**
     * If true, strip <b>all</b> new line chars and with empty lines.
     *
     * If $newline is set to non blank, it is not stripped. If You want
     * to strip <b>all</b> new line chars, with $newline, You must set 
     * $newline to blank.
     *
     * @var    boolean
     * @access private
     */
    private $strip              = false;

    /**
     * This chars will be inserted past < br />.
     *
     * If You want not to strip new line chars from data, you may set this
     * para as "\n" (for example). If You want to strip - set this to blank.
     * <code>$newline = "\n";
     *$newline = "\r";
     *$newline = "\r\n";</code>
     *
     * @var string
     * @access private
     */
    private $newline            = "\n";

    /**
     * Array with tags which aren't stripped from input data
     *
     * @var    array
     * @access private
     */
    private $tags_allowed       = array('a', 'abbr', 'acronym', 'address', 'b',
                                  'blockquote', 'br', 'caption', 'cite', 'code',
                                  'dd', 'del', 'dfn', 'div', 'dl', 'dt', 'em',
                                  'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i',
                                  'img', 'ins', 'kbd', 'li', 'ol', 'p', 'pre',
                                  'q', 'samp', 'span', 'strong', 'table',
                                  'tbody', 'td', 'tfoot', 'th', 'thead', 'tr',
                                  'tt', 'ul', 'var');

    /**
     * Enable or disable DEBUG mode
     * @var bool
     */
    private $DEBUG              = false;

    /**
     * XML Parser object
     * @var object
     * @access private
     */
    private $parser             = null;

    /**
     * Holds parsed data as array
     * @var array
     */
    private $output             = array();

    /**
     * Holds all $tag_queue lists tree from parsed domcument.
     *
     * Used for DEBUG purposes.
     *
     * @var array
     * @access private
     */
    private $snapshots          = array();

    /**
     * Holds tags queue from parsed document.
     *
     * Used for DEBUG purposes.
     *
     * @var array
     * @access private
     */
    private $tag_queue          = array();

    /**
     * Elements which have not closing tag
     * @var array
     */
    private $tags_closed        = array('meta', 'img', 'link', 'br', 'col',
                                  'hr', 'base', 'area', 'input');

    /**
     * Attributes which can occur in any tags.
     *
     * @var array
     * @access private
     */
    private $attributes_const   = array('title', 'name', 'id', 'lang', 'class',
                                  'style');

    /**
     * All tags database
     *
     * Holds an array of all tags which can occur in input data as array key.
     * Each value of these array is an array of attributes which can occur
     * in corresponding to its tag.
     *
     * @var array
     * @access private
     */
    private $attributes         = array(
      'a'           => array('href', 'type', 'target'),
      'abbr'        => array(),
      'acronym'     => array(),
      'address'     => array(),
      'applet'      => array('codebase', 'code', 'object', 'width', 'height'),
      'area'        => array('shape', 'coords'),
      'b'           => array(),
      'base'        => array('href', 'target'),
      'bdo'         => array('dir'),
      'big'         => array(),
      'blockquote'  => array('cite'),
      'body'        => array(),
      'br'          => array(),
      'button'      => array('value', 'type', 'disabled'),
      'caption'     => array(),
      'cite'        => array(),
      'code'        => array(),
      'col'         => array('span', 'width'),
      'colgroup'    => array('span', 'width'),
      'dd'          => array(),
      'del'         => array('cite', 'datetime'),
      'dfn'         => array(),
      'div'         => array(),
      'dl'          => array(),
      'dt'          => array(),
      'em'          => array(),
      'fieldset'    => array(),
      'form'        => array('action', 'method', 'enctype', 'accept',
                       'accept-charset'),
      'frame'       => array('longdesc', 'src', 'frameborder', 'marginwidth',
                       'marginheight', 'noresize', 'scrolling'),
      'frameset'    => array('rows', 'cols'),
      'h1'          => array(),
      'h2'          => array(),
      'h3'          => array(),
      'h4'          => array(),
      'h5'          => array(),
      'h6'          => array(),
      'head'        => array('profile'),
      'hr'          => array(),
      'html'        => array(),
      'i'           => array(),
      'iframe'      => array('longdesc', 'src', 'frameborder', 'marginwidth',
                       'marginheight', 'scrolling', 'align', 'height', 'width'),
      'img'         => array('width', 'height', 'alt', 'usemap', 'src',
                       'longdesc', 'ismap'),
      'input'       => array('type', 'value', 'checked', 'disabled', 'readonly',
                       'size', 'maxlength', 'src', 'alt', 'usemap', 'ismap',
                       'accept'),
      'ins'         => array('cite', 'datetime'),
      'kbd'         => array(),
      'label'       => array('for'),
      'legend'      => array(),
      'li'          => array(),
      'link'        => array('charset', 'href', 'hreflang', 'type', 'media',
                       'target'),
      'map'         => array('name'),
      'meta'        => array('http-eqiv', 'name', 'content', 'scheme'),
      'noframes'    => array(),
      'noscript'    => array(),
      'object'      => array('declare', 'classid', 'codebase', 'data', 'type',
                       'codetype', 'archive', 'standby', 'height', 'width',
                       'usemap'),
      'ol'          => array(),
      'optgroup'    => array('size', 'multiple', 'disabled'),
      'option'      => array('size', 'multiple', 'disabled'),
      'p'           => array(),
      'param'       => array('value', 'valuetype', 'type'),
      'pre'         => array(),
      'q'           => array('cite'),
      's'           => array(),
      'samp'        => array(),
      'script'      => array('charset', 'type', 'src', 'defer'),
      'select'      => array('size', 'multiple', 'disabled'),
      'small'       => array(),
      'span'        => array(),
      'strike'      => array(),
      'strong'      => array(),
      'style'       => array('type', 'media', 'title'),
      'sub'         => array(),
      'sup'         => array(),
      'table'       => array('summary', 'width', 'border', 'frame', 'rules',
                       'cellspacing', 'cellpadding', 'dir'),
      'tbody'       => array(),
      'td'          => array('abbr', 'axis', 'headers', 'scope', 'rowspan',
                       'colspan', 'nowrap', 'width', 'height'),
      'textarea'    => array('name', 'rows', 'cols', 'disabled', 'readonly'),
      'tfoot'       => array(),
      'th'          => array('abbr', 'axis', 'headers', 'scope', 'rowspan',
                       'colspan', 'nowrap', 'width', 'height'),
      'thead'       => array(),
      'title'       => array(),
      'tr'          => array(),
      'tt'          => array(),
      'u'           => array(),
      'ul'          => array(),
      'var'         => array()
    );

    /**
     * Attributes which value have to be checked by designed to it's method
     *
     * @var array
     * @access private
     */
    private $attributes_ext     = array('href');
  
    /**
     * Contructor.
     *
     * Initializes XML Parser object and pass it to $this->parser.
     */
    public function __construct()
    {
        $this->parser = xml_parser_create();

        xml_parser_set_option(  $this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_object(                $this->parser, $this                  );
        xml_set_element_handler(       $this->parser, 'tag_open', 'tag_close');
        xml_set_character_data_handler($this->parser, 'cdata'                );
        xml_set_default_handler(       $this->parser, 'h_default'            );
    }

    /**
     * Main engine of Parser class.
     *
     * Return parsed data or null if $rettype is unknown.
     * If $wellformed == false, all text is inserted into
     * Parser::virttag tags.
     * Usually is used text from textarea fields, where data aren't
     * well formed (in XML context), so its important to repair this.
     * If data is for example ready to use HTML file with it's content
     * enclosed into <html /> tag, call parse() with $wellformed as true.
     *
     * @param string $data data to be parsed
     * @param string $rettype defines format of returned data:
     *   - 'array' - as array
     *   - 'text'  - as text
     *   - 'raw'   - unparsed text
     * @param bool $wellformed defines that input text is well formed XML
     *
     * @return mixed
     */
    public function parse($data, $rettype='text', $wellformed = false)
    {
        // if input data isn't well-formed XML, we must put it into virtual tags
        if (!$wellformed) {
            $data = sprintf('<%s>%s</%s>', self::virttag, $data, self::virttag);
        }

        xml_parse($this->parser, $data);

        if ($this->DEBUG) {
            echo implode("<br />\n", $this->snapshots);
        }

        switch ($rettype) {
            case 'array': return $this->output;
            case 'raw':   return $data;
            case 'text':  return implode('', $this->output);
            default:      return null;
        }
    }

    /**
     * Handle start tag.
     *
     * Push into Parser::output start tag (or tag who hasn't closing tag).
     * If Parser::DEBUG is TRUE, it release Parser::make_snapshot() method.
     *
     * @param object $parser
     * @param string $tag name of new tag
     * @param array $attributes list of tag attributes
     *
     * @return bool true
     */
    private function tag_open($parser, $tag, $attributes) 
    {
        if (in_array($tag, $this->tags_allowed)) { //we want these tag in our output ?
            $htmlTag = '<' . $tag;

            if ($attributes) {

                while(list($attr, $val) = each($attributes)) {
                    if (in_array($attr, $this->attributes[$tag]) ||
                        in_array($attr, $this->attributes_const)) {

                        if (in_array($attr, $this->attributes_ext)) {
                            $m = 'checkattr_' . $attr;
                            $val = $this->$m($val);
                        }

                        $htmlTag .= sprintf(' %s="%s"', $attr, $val);
                    }

                }
            } //if ($attributes)

            $htmlTag .= in_array($tag, $this->tags_closed) ? ' />' : '>';

            $this->output[]     = $htmlTag;
        } //if (in_array($tag, $this->tags_allowed))


        if ($this->DEBUG) {
            $this->tag_queue[]  = $tag;
            $this->make_snapshot();
        }
        if (in_array($tag, $this->safe_tree)) {
            $this->nl2br++;
        }

        return true;
    }

    /**
     * Handle character data in parsed text.
     *
     * Checks did text isn't Parser::safe Parser::safe_tree,
     * and if not, replace all new line chars with < br /> XHTML tag,
     * and behind it, content of Parser::newline.
     * If Parser::strip is set to FALSE, or character data
     * isn't empty, append parsed text into Parser::output.
     *
     * @param object $parser 
     * @param string $cdata founded character data
     *
     * @return bool true
     */
    private function cdata($parser, $cdata) 
    {
        $last = end($this->tag_queue);

        if ($this->DEBUG) {
            $br = sprintf('<br type="%s" />', $last) . $this->newline;
        } else {
            $br = '<br />' . $this->newline;
        }

        if (0 == $this->nl2br && !in_array($last, $this->safe)) {
            $cdata = str_replace(array("\r\n", "\r", "\n"), $br, $cdata);
        }

        if ($this->strip) {
            $cdata = trim($cdata, "\r\n");
        }

        if (!$this->strip || '' != trim($cdata)) {
            $this->output[] = $cdata;
        }

        return true;
    }

    /**
     * Handle action at close tag.
     *
     * If handled tag isn't in Parser::safe or Parser::safe_tree,
     * and is in Parser::tags_allowed, append a close part of
     * tag into Parser::output.
     *
     * @param object $parser
     * @param string $tag name of closed tag
     * @return bool true
     */
    private function tag_close($parser, $tag) 
    {
        if (!in_array($tag, $this->tags_closed) && in_array($tag, $this->tags_allowed)) {
            $this->output[] = sprintf('</%s>', $tag);
        }

        array_pop($this->tag_queue);

        if (in_array($tag, $this->safe_tree)) {
            $this->nl2br--;
        }

        return true;
    }

    /**
     * Released when found other type of data, not handled by previuos methods.
     *
     * @param object $parser
     * @param string $data
     * @return bool true
     */    
    private function h_default($parser, $cdata)
    {
        if (!$this->strip || trim($cdata) != '') {
            $this->output[] = $cdata;
        }

        return true;
    }

    /**
     * Creates snapshot of current tag queue. Used only for DEBUG purposes.
     * 
     * @access private
     * @return bool true
     */
    private function make_snapshot()
    {
        if (count($this->tag_queue) > 1) {
            $this->snapshots[] = implode(' -&gt; ', array_slice($this->tag_queue, 1));
        }
        return true;
    }

    /**
     * Checker for value of 'href' attribute.
     *
     * Href cannot start with 'javascript' - XSS preventing.
     *
     * @param $val string value of href attribute
     *
     * @return string
     *
     * @access private
     */
    private function checkattr_href($val) {
        if (substr($val, 0, 11) == 'javascript:') {
            return substr($val, 11);
        } else {
            return $val;
        }
    }
}


$content = 'cze¶æ. co¶ tam co¶ tam, tralala.
<head>tralala <title>tytul</title> <a href="as">link as</a> sad</head>
<strong>mony</strong>
<em style="font-size: small">emfazja</em>
<a href="#" onclick="alert(\'test\')">link</a>

<a href="javascript:alert(\'tralala\')">tralala</a>

<ul>
  <li>test <a href="asd">tralal</a></li>

  <li>umcyk <ol>
    <li>tralala</li>
    <li><a href="okulala">bum maryna</a></li>

    </ol></li>
</ul>

<pre>trlala

assasa
as
as
a</pre>';
$content = iconv('iso-8859-2', 'utf-8', $content);
header('Content-type: text/html; charset=utf-8');

$p = new Parser;
echo $p->parse($content, 'text', false);


?>
