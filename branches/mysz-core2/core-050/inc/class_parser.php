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
 * @version    SVN: $Id: class_parser.php 1342 2006-03-31 17:47:05Z mysz $
 * @link       $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/class_parser.php $
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
 * @version    SVN: $Id: class_parser.php 1342 2006-03-31 17:47:05Z mysz $
 * @link       $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/class_parser.php $
 */
class Parser {
    /**
     * Constant
     *
     * Holds what virtual tag is used if source is not well formed xml.
     */
    const   VIRTTAG             = 'corecms:virtual';

    /**
     * Define when replace new line chars on html < br/>
     *
     * Used by Parse::_cdata()
     *
     * @var    integer
     * @access private
     */
    private $_nl2br              = 0;

    /**
     * Hold tags which content is not nl2br'ed
     *
     * Doesn't affect child of these tags.
     *
     * @var    array
     * @access private
     */
    static private $_safe        = array('style', 'ul', 'ol', 'dl', 'html');

    /**
     * Hold tags which content (and theyre childs too).
     *
     * @var    array
     * @access private
     */
    static private $_safeTree   = array('head', 'script', 'pre', 'style');

    /**
     * If true, strip <b>all</b> new line chars and with empty lines.
     *
     * If $_newline is set to non blank, it is not stripped. If You want
     * to strip <b>all</b> new line chars, with $_newline, You must set
     * $_newline to blank.
     *
     * @var    boolean
     * @access private
     */
    private $_strip              = false;

    /**
     * This chars will be inserted past < br />.
     *
     * If You want not to strip new line chars from data, you may set this
     * para as "\n" (for example). If You want to strip - set this to blank.
     * <code>$_newline = "\n";
     *$_newline = "\r";
     *$_newline = "\r\n";</code>
     *
     * @var string
     * @access private
     */
    private $_newline            = "\n";

    /**
     * Array with tags which aren't stripped from input data
     *
     * @var    array
     * @access public
     */
    static public $tagsAllowed   = array('a', 'abbr', 'acronym', 'address', 'b',
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
     * @access private
     */
    private $_DEBUG              = false;

    /**
     * XML Parser object
     * @var object
     * @access private
     */
    private $_parser             = null;

    /**
     * Holds parsed data as array
     * @var array
     * @access private
     */
    private $_output             = array();

    /**
     * Holds all $_tagQueue lists tree from parsed domcument.
     *
     * Used for DEBUG purposes.
     *
     * @var array
     * @access private
     */
    private $_snapshots          = array();

    /**
     * Holds tags queue from parsed document.
     *
     * Used for DEBUG purposes.
     *
     * @var array
     * @access private
     */
    private $_tagQueue          = array();

    /**
     * Elements which have not closing tag
     * @var array
     * @access public
     */
    static public $tagsClosed   = array('meta', 'img', 'link', 'br', 'col',
                                  'hr', 'base', 'area', 'input');

    /**
     * Attributes which can occur in any tags.
     *
     * @var array
     * @access public
     */
    static public $attributesConst = array('title', 'name', 'id', 'lang', 'class',
                                  'style');

    /**
     * All tags database
     *
     * Holds an array of all tags which can occur in input data as array key.
     * Each value of these array is an array of attributes which can occur
     * in corresponding to its tag.
     *
     * @var array
     * @access public
     */
    static public $attributes   = array(
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
    static private $_attributesExt = array('href', 'src');

    /**
     * Contructor.
     *
     * Initializes XML Parser object and pass it to $this->_parser.
     *
     * @access public
     */
    public function __construct()
    {
        $this->_parser = xml_parser_create();

        xml_parser_set_option(  $this->_parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_object(                $this->_parser, $this                  );
        xml_set_element_handler(       $this->_parser, '_tagOpen', '_tagClose');
        xml_set_character_data_handler($this->_parser, '_cdata'                );
        xml_set_default_handler(       $this->_parser, '_hDefault'            );
        xml_set_processing_instruction_handler($this->_parser, '_PIHandler'    );
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
     *
     * @access public
     */
    public function parse($data, $rettype='text', $wellformed = false)
    {
        // if input data isn't well-formed XML, we must put it into virtual tags
        if (!$wellformed) {
            $data = sprintf('<%s>%s</%s>', self::VIRTTAG, $data, self::VIRTTAG);
        }

        xml_parse($this->_parser, $data);

        if ($this->_DEBUG) {
            echo implode("<br />\n", $this->_snapshots);
        }

        switch ($rettype) {
            case 'array': return $this->_output;
            case 'raw':   return $data;
            case 'text':  return implode('', $this->_output);
            default:      return null;
        }
    }

    /**
     * Handle start tag.
     *
     * Push into Parser::output start tag (or tag who hasn't closing tag).
     * If Parser::DEBUG is TRUE, it release Parser::_makeSnapshot() method.
     *
     * @param object $_parser
     * @param string $tag name of new tag
     * @param array $attributes list of tag attributes
     *
     * @return bool true
     *
     * @access private
     */
    private function _tagOpen($_parser, $tag, $attributes)
    {
        if (in_array($tag, self::$tagsAllowed)) { //we want these tag in our output ?
            $htmlTag = '<' . $tag;

            if ($attributes) {

                while(list($attr, $val) = each($attributes)) {
                    if (in_array($attr, self::$attributes[$tag]) ||
                        in_array($attr, self::$attributesConst)) {

                        if (in_array($attr, self::$_attributesExt)) {
                            $m = '_checkattr_' . $attr;
                            $val = $this->$m($val);
                        }

                        $htmlTag .= sprintf(' %s="%s"', $attr, $val);
                    }

                }
            } //if ($attributes)

            $htmlTag .= in_array($tag, self::$tagsClosed) ? ' />' : '>';

            $this->_output[]     = $htmlTag;
        } //if (in_array($tag, self::$tagsAllowed))


        if ($this->_DEBUG) {
            $this->_tagQueue[]  = $tag;
            $this->_makeSnapshot();
        }
        if (in_array($tag, self::$_safeTree)) {
            $this->_nl2br++;
        }

        return true;
    }

    /**
     * Handle character data in parsed text.
     *
     * Checks did text isn't Parser::safe Parser::_safeTree,
     * and if not, replace all new line chars with < br /> XHTML tag,
     * and behind it, content of Parser::newline.
     * If Parser::strip is set to FALSE, or character data
     * isn't empty, append parsed text into Parser::output.
     *
     * @param object $_parser
     * @param string $cdata founded character data
     *
     * @return bool true
     *
     * @access private
     */
    private function _cdata($_parser, $cdata)
    {
        $last = end($this->_tagQueue);

        if ($this->_DEBUG) {
            $br = sprintf('<br type="%s" />', $last) . $this->_newline;
        } else {
            $br = '<br />' . $this->_newline;
        }

        if (0 == $this->_nl2br && !in_array($last, self::$_safe)) {
            $cdata = str_replace(array("\r\n", "\r", "\n"), $br, $cdata);
        }

        if ($this->_strip) {
            $cdata = trim($cdata, "\r\n");
        }

        if (!$this->_strip || '' != trim($cdata)) {
            $this->_output[] = $cdata;
        }

        return true;
    }

    /**
     * Handle action at close tag.
     *
     * If handled tag isn't in Parser::safe or Parser::_safeTree,
     * and is in Parser::$tagsAllowed, append a close part of
     * tag into Parser::output.
     *
     * @param object $_parser
     * @param string $tag name of closed tag
     *
     * @return bool true
     *
     * @access private
     */
    private function _tagClose($_parser, $tag)
    {
        if (!in_array($tag, self::$tagsClosed) && in_array($tag, self::$tagsAllowed)) {
            $this->_output[] = sprintf('</%s>', $tag);
        }

        array_pop($this->_tagQueue);

        if (in_array($tag, self::$_safeTree)) {
            $this->_nl2br--;
        }

        return true;
    }

    /**
     * Released when found other type of data, not handled by previuos methods.
     *
     * @param object $_parser
     * @param string $data
     *
     * @return bool true
     *
     * @access private
     */
    private function _hDefault($_parser, $cdata)
    {
        if (!$this->_strip || trim($cdata) != '') {
            $this->_output[] = $cdata;
        }

        return true;
    }

    /**
     * If allowed, evaluate PHP code.
     *
     * @param object $_parser
     * @param string $target type of processing instruction
     * @param string $data
     *
     * @return string
     *
     * @access private
     */
    private function _PIHandler($_parser, $target, $data)
    {
        if ('php' == strtolower($target)) {
            if ($this->parsePHP) {
                return eval($data);
            } else {
                return Strings::entities($data);
            }
        }
    }

    /**
     * Creates snapshot of current tag queue. Used only for DEBUG purposes.
     *
     * @return bool true
     *
     * @access private
     */
    private function _makeSnapshot()
    {
        if (count($this->_tagQueue) > 1) {
            $this->_snapshots[] = implode(' -&gt; ', array_slice($this->_tagQueue, 1));
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
    private function _checkattr_href($val)
    {
        $pattern = '#^\s*j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\s*:#im';
        return preg_replace($pattern, '', $val);
    }

    /**
     * Checker for value of 'src' attribute.
     *
     * Src cannot start with 'javascript' - XSS preventing.
     *
     * @param $val string value of src attribute
     *
     * @return string
     *
     * @access private
     */
    private function _checkattr_src($val)
    {
        return $this->_checkattr_href($val);
    }
}

?>
