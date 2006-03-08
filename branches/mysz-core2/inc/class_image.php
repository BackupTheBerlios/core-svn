<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for image manipulations
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
 * Class for image manipulations
 *
 * Scaling, resizing, cropping, applying filters and many others.
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
class Image {

    /**
     * Constant - default output format.
     */
    const FORMAT = 'png';
    /**
     * Constant - default jpeg quality.
     */
    const QUALITY = 75;

    /**
     * Height of image
     *
     * @var integer
     * @access public
     */
    public $height = 0;

    /**
     * Width of image
     *
     * @var integer
     * @access public
     */
    public $width = 0;

    /**
     * Is this image truecolor or not
     *
     * @var boolean
     * @access public
     */
    public $truecolor = null;

    /**
     * Image descriptor (handler)
     *
     * @var object
     * @access private
     */
    private $_body = null;

    /**
     * Filters which can be used.
     *
     * List is initialized from constructor, because filters are available
     * only if php use it's internal gd.
     */
    private $_filters = array();

    /**
     * Formats handled by this class.
     *
     * This array maps possible formats to assigned with them functions from
     * image extension.
     *
     * @var array
     * @access private
     */
    private $_formats = array(
        'jpg'  => 'imagejpeg',
        'jpeg' => 'imagejpeg',
        'gif'  => 'imagegif',
        'png'  => 'imagepng'
    );


    /**
     * Constructor.
     *
     * If filename in argument is specfified, it opens file and read them.
     * Assign available filters too (if any).
     *
     * @param $fname string filename to open
     *
     * @access public
     */
    public function __construct($fname=null)
    {
        // these constants aren't compiled in if php use external gd.
        // In this case, we cant put here these constant, only
        // if imagefilter() function exists (it means: when php use bundled
        // version of gd)
        if (function_exists('imagefilter')) {
            $this->_filters = array(
                'negate'        => IMG_FILTER_NEGATE,
                'grayscale'     => IMG_FILTER_GRAYSCALE,
                'brightness'    => IMG_FILTER_BRIGHTNESS,
                'contrast'      => IMG_FILTER_CONTRAST,
                'colorize'      => IMG_FILTER_COLORIZE,
                'edge'          => IMG_FILTER_EDGEDETECT,
                'emboss'        => IMG_FILTER_EMBOSS,
                'gaussian'      => IMG_FILTER_GAUSSIAN_BLUR,
                'blur'          => IMG_FILTER_SELECTIVE_BLUR,
                'sketchy'       => IMG_FILTER_MEAN_REMOVAL,
                'smooth'        => IMG_FILTER_SMOOTH
            );
        }

        $this->open($fname);
    }

    /**
     * Destructor
     *
     * Destroy opened image
     */
    public function __destruct()
    {
        if (!is_null($this->_body)) {
            imagedestroy($this->_body);
        }
    }

    /**
     * Read file and assign properties.
     *
     * @param string  $fname  filename to read
     * @param boolean $return return file image object if true
     *
     * @return mixed
     */
    public function open($fname=null, $return=false)
    {
        if (is_null($fname) || !file_exists($fname)) {
            return false;
        }
        $fname = Path::normalize($fname);

        $dst = imagecreatefromstring(file_get_contents($fname));
        if ($return) {
            return $dst;
        } else {
            $this->_swap($dst);
            $this->width        = imagesx($dst);
            $this->height       = imagesy($dst);
            $this->truecolor    = imageistruecolor($dst);
        }
    }

    /**
     * Send image to standard output.
     *
     * @param string  $format       possible values in $this->_formats
     * @param integer $quality      quality of output jpeg
     * @param boolean $send_headers if true, send content-type header
     *
     * @access public
     */
    public function show($format=null, $quality=75, $send_headers=true)
    {
        $this->_is_initialized();

        $format = $this->_checkFormat($format);

        if ($send_headers) {
            header('Content-type: image/' . $format);
        }

        if (is_null($quality)) {
            $quality = self::QUALITY;
        }

        $this->_formats[$format]($this->_body, '', $quality);
    }

    /**
     * Save image to given file.
     *
     * @param string $fname      filename
     * @param string $format     possible values in $this->_formats
     * @param integer $quality   quality of output jpeg
     * @param boolean $overwrite if true, it overwrite file if it exists
     *
     * @throws CEFilesystemError
     *
     * @access public
     */
    public function saveToFile($fname, $format=null, $quality=75, $overwrite=false)
    {
        $this->_is_initialized();

        $pathinfo = pathinfo($fname);

        if (is_null($format)) {
            $format = $pathinfo['extension'];
        }
        $format = $this->_checkFormat($format);

        if ($pathinfo['dirname'] == '') {
            $pathinfo['dirname'] = '.';
        }
        $pathinfo['dirname'] = realpath($pathinfo['dirname']);
        $fullpath = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['basename'];

        if (!is_writeable($pathinfo['dirname'])) {
            throw new CEFilesystemError(sprintf('Cannot write to "%s" directory.', $pathinfo['dirname']));
        }
        if (file_exists($fullpath)) {
            if ($overwrite) {
                unlink($fullpath);
            } else {
                throw new CEFilesystemError(sprintf('File "%s" already exists.', $fullpath));
            }
        }

        if (is_null($quality)) {
            $quality = self::QUALITY;
        }

        $this->_formats[$format]($this->_body, $fname, $quality);
    }

    /**
     * Return image as an string.
     *
     * @param string  $format  possible alues in $this->_formats
     * @param integer $quality quality of output jpeg
     *
     * @return string
     *
     * @access public
     */
    public function toString($format=null, $quality=null)
    {
        $format = $this->_checkFormat($format);

        $fname = tempnam(getcwd(), 'core_');
        $this->saveToFile($fname, $format, $quality, true);
        $img = file_get_contents($fname);
        unlink($fname);
        return $img;
    }


    /**
     * Alias for scaleProp() and scaleNonprop()
     *
     * @param integer $width  width of image
     * @param integer $height height of image
     * @param boolean $prop   proportional or nonproportional scaling
     * @param string  $color  background color in html notation
     *
     * @return boolean
     *
     * @access public
     */
    public function scale($width, $height, $prop, $bgcolor='ffffff')
    {
        if ($prop) {
            return $this->scaleProp($width, $height, $bgcolor);
        } else {
            return $this->scaleNonprop($width, $height, $bgcolor);
        }
    }

    /**
     * Proportional scaling of image
     *
     * Return false if fail
     *
     * @param integer $width   width of image
     * @param integer $height  of image
     * @param string  $bgcolor background color
     *
     * @return boolean
     *
     * @access public
     */
    public function scaleProp($width, $height, $bgcolor='ffffff')
    {
        $this->_is_initialized();

        $dst = $this->_newImage($width, $height);

        //background color
        imagefill($dst, 0, 0, $this->color($bgcolor));

        // we must to now dzielna
        $div_width  = $this->width  / $width;
        $div_height = $this->height / $height;

        $div = max($div_width, $div_height);
        unset($div_width, $div_height);

        // proportional size of shrinked picture
        $th_w = $this->width  / $div;
        $th_h = $this->height / $div;

        if ($th_w > $th_h) {
            $test = imagecopyresampled($dst, $this->_body,
                0, round(($th_w - $th_h)/2),
                0, 0,
                $th_w, $th_h,
                $this->width, $this->height);
        } else {
            $test = imagecopyresampled($dst, $this->_body,
                round(($th_h - $th_w)/2), 0,
                0, 0,
                $th_w, $th_h,
                $this->width, $this->height);
        }

        if ($test) {
            return $this->_swap($dst);
        } else {
            return false;
        }
    }

    /**
     *  Non proportional scaling
     *
     * Return false if fail
     *
     * @param integer $width   width of image
     * @param integer $height  height of image
     * @param string  $bgcolor background color
     *
     * @return boolean
     *
     * @access public
     */
    public function scaleNonprop($width, $height, $bgcolor='ffffff')
    {
        $this->_is_initialized();

        $dst = $this->_newImage($width, $height);

        imagefill($dst, 0, 0, $this->color($bgcolor));

        $test = imagecopyresampled($dst, $this->_body,
                0, 0, 0, 0,
                $width, $height, $this->width, $this->height);

        if ($test) {
            return $this->_swap($dst);
        } else {
            return false;
        }
    }

    /**
     * Cropping of an image, style 1.
     *
     * As parameters are given: x and y coordinats of top left corner
     * cropped area, and width and height of it.
     *
     * @param integer $x
     * @param integer $y
     * @param integer $h
     * @param integer $w
     *
     * @return boolean
     *
     * @access public
     */
    public function crop1($x, $y, $h, $w)
    {
        $this->_is_initialized();

        $dst = $this->_newImage($h, $w);

        $test = imagecopy($dst, $this->_body, 0, 0, $x, $y, $h, $w);
        if ($test) {
            return $this->_swap($dst);
        } else {
            return false;
        }
    }

    /**
     * Cropping of an image, style 2.
     *
     * As parameters are given: top, right, bottom and left coordinates of
     * image.
     *
     * @param integer $t
     * @param integer $r
     * @param integer $b
     * @param integer $l
     *
     * @return boolean
     *
     * @access public
     */
    public function crop2($t, $r, $b, $l)
    {
        $w = $r - $l;
        $h = $b - $t;

        return $this->crop1($t, $l, $w, $h);
    }

    /**
     * Apply filter to an image
     *
     * @param string $filter filter name (available filters from $this->_filters)
     * @param...             @sse htp://php.net/imagefilter
     *
     * @return boolean
     * @throws CESyntaxError if invalid filter name
     *
     * @access public
     */
    public function filter($filter)
    {
        $this->_is_initialized();

        if (!in_array($filter, $this->_filters)) {
            throw new CESyntaxError(sprintf('Invalid filter: "%s".', $filter));
        }

        // Workaround: i don't know why, but imagefilter() throw some fatal
        // error if there is all 4 possible arguments, so i must give him
        // only that, which are required at call time (depends of filter)
        $argc = func_num_args();
        $argv = func_get_args();
        switch ($argc) {
            case 1: return imagefilter($this->_body, $this->_filters[$filter]);
            case 2: return imagefilter($this->_body, $this->_filters[$filter], $argv[1]);
            case 3: return imagefilter($this->_body, $this->_filters[$filter], $argv[1], $argv[2]);
            default:
                return imagefilter($this->_body, $this->_filters[$filter], $argv[1], $argv[2], $argv[3]);
        }
    }


    /**
     * Apply negate filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterNegate()
    {
        return $this->filter('negate');
    }

    /**
     * Apply grayscale filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterGrayscale()
    {
        return $this->filter('grayscale');
    }

    /**
     * Apply brightness filter to an image
     *
     * @param integer $brightness
     *
     * @return boolean
     *
     * @access public
     */
    public function filterBrightness($value)
    {
        return $this->filter('brightness', $value);
    }

    /**
     * Apply contrast filter to an image
     *
     * @param integer $contrast
     *
     * @return boolean
     *
     * @access public
     */
    public function filterContrast($value)
    {
        return $this->filter('contrast', $value);
    }

    /**
     * Apply colorize filter to an image
     *
     * Color can be as html notation, like 'ffffff' (white) or '000000'
     * (black), either too three arguments, each one power of red ($r),
     * green ($g) or blue ($b).
     *
     * @param mixed   $r
     * @param integer $g
     * @param integer $b
     *
     * @return boolean
     *
     * @access public
     */
    public function filterColorize($r, $g=null, $b=null)
    {
        if (is_null($g) || is_null($b)) {
            $rgb = $this->_hex2rgb($r);
            if (false === $rgb) {
                return false;
            }
            list($r, $g, $b) = $rgb;
        }
        return $this->filter('colorize', $r, $g, $b);
    }

    /**
     * Apply edge filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterEdge()
    {
        return $this->filter('edge');
    }

    /**
     * Apply emboss filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterEmboss()
    {
        return $this->filter('emboss');
    }
    /**
     * Apply gaussian blur filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterGaussian()
    {
        return $this->filter('gaussian');
    }
    /**
     * Apply blur filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterBlur()
    {
        return $this->filter('blur');
    }
    /**
     * Apply sketchy filter to an image
     *
     * @return boolean
     *
     * @access public
     */
    public function filterSketchy()
    {
        return $this->filter('sketchy');
    }
    /**
     * Apply smooth filter to an image
     *
     * @param integer $value smoothness
     *
     * @return boolean
     *
     * @access public
     */
    public function filterSmooth($value)
    {
        return $this->filter('smooth', $value);
    }
    /**
     * Rotate image for given angle
     *
     * @param integer $angle
     * @param string  $bgcolor background color
     * @param boolean $ignore_transparent @see http://php.net/imagerotate
     *
     * @access public
     */
    public function rotate($angle, $bgcolor='ffffff', $ignore_transparent=false)
    {
        $this->_is_initialized();

        $angle = (float)$angle;
        $color = $this->color($bgcolor);
        $this->_body = imagerotate($this->_body, $angle, $color, $ignore_transparent);
    }

    /**
     * Apply an border to image
     *
     * Border can be solid, or pattern (@see http://php.net/imagesetstyle).
     * Each of borders can be other color/style, and thickness.
     *
     * If $thickness is an integer, it means is one value (thickness) for
     * all borders. $thickness can be an array too, and it must have 4
     * elements, one per one border.
     *
     * Colors/style can be set one for all edges (only $colT set), different
     * for top/bottom and left/right edges (set $colT and $colR,
     * and different for any of edge (all 4 $col* must be set).
     *
     * Colors can be as hex value in html notation, or result of
     * imagecolorallocate() (@see http://php.net/imagecolorallocate).
     *
     * If You want to set pattern for edges, You must set proper arrays
     * (@see http://php.net/imagesetstyle).
     *
     * @param mixed $thickness
     * @param mixed $colT
     * @param mixed $colR
     * @param mixed $colB
     * @param mixed $colL
     *
     * @return boolean
     * @throws CETypeError
     *
     * @access public
     */
    public function border($thickness, $colT, $colR=null, $colB=null, $colL=null)
    {
        if (!is_array($thickness)) { //all borders
            $thickness = array_fill(0, 4, $thickness);
        } elseif (2 == count($thickness)) { //top and bottom, left and right
            $tmp = $thickness;
            $thickness = array($tmp[0], $tmp[1], $tmp[0], $tmp[1]);
            unset($tmp);
        } elseif (4 != count($thickness)) { //four different
            throw new CETypeError('Incorrect type of "$thickness" parameter.');
        }

        if (is_null($colR)) { //all borders have one style
            $colR = $colB = $colL = $colT;
        } elseif (is_null($colB)) { //borders top and bottom have one style, left and right - second
            $colB = $colT;
            $colL = $colR;
        }
        $col = array($colT, $colR, $colB, $colL);

        $this->_line(
            array(0, $thickness[0]/2),
            array($this->width, $thickness[0]/2),
            $colT, $thickness[0]);
        $this->_line(
            array($this->width - ($thickness[1]/2), 0),
            array($this->width - ($thickness[1]/2), $this->height),
            $colR, $thickness[1]);
        $this->_line(
            array($this->width, $this->height - ($thickness[2]/2) ),
            array(0, $this->height - ($thickness[2]/2) ),
            $colB, $thickness[2]);
        $this->_line(
            array($thickness[3]/2, $this->height),
            array($thickness[3]/2, 0),
            $colL, $thickness[3]);

        return true;
    }

    /**
     * Add layer on top of image.
     *
     * If $layer is string, it suppose to be file name, which be loaded and
     * merged with current image.
     * $layer can also be an image object (@see Image::get())
     *
     * @param mixed   $layer
     * @param integer $alpha opacity of new layer
     *
     * @return boolean
     *
     * @access public
     */
    public function addLayer($layer, $alpha=100)
    {
        if (is_string($layer) && is_file($layer)) {
            $layer = $this->open($layer, true);
        }
        if (!$layer) {
            return false;
        }

        return imagecopymerge($this->_body, $layer, 0, 0, 0, 0, imagesx($layer),
                imagesy($layer), $alpha);
    }


    /**
     * Allocate color.
     *
     * Can be used when adding borders etc.
     * @see http://php.net/imagecolorallocate
     *
     * @param string $hex color in html notation
     *
     * @return integer
     *
     * @access public
     */
    public function color($hex)
    {
        list($r, $g, $b) = $this->_hex2rgb($hex);
        return imagecolorallocate($this->_body, $r, $g, $b);
    }

    /**
     * Return current image object
     *
     * @return resource
     *
     * @access public
     */
    public function get()
    {
        return $this->_body;
    }


    /**
     * Draw a line on image
     *
     * Currently used only for drawig borders/
     * $begin and $end holds coordinates of begin and end points of line.
     * $color can be:
     * - integer - if line have to be solid
     * - array - if line have to be an pattern (@see http://php.net/imagesestyle)
     *
     *
     * @param array   $begin
     * @param array   $end
     * @param mixed   $color
     * @param integer $thickness
     *
     * @return blue
     *
     * @access public
     */
    private function _line($begin, $end, $color, $thickness)
    {
        imagesetthickness($this->_body, $thickness);

        if (!is_array($color)) { //solid
            if (is_string($color)) {
                $color = $this->color($color);
            }
            imageline($this->_body,
                    $begin[0], $begin[1],
                    $end[0], $end[1],
                    $color);
        } else { //pattern
            imagesetstyle($this->_body, $color);
            imageline($this->_body,
                    $begin[0], $begin[1],
                    $end[0], $end[1],
                    IMG_COLOR_STYLED);
        }
        return true;
    }

    /**
     * Checks for image that was loaded
     *
     * If image wasn't loaded, and $exc == true, an exception is raised.
     * If $exc == false, is_initialized() return false.
     *
     * @param boolean $exc if true, an exception is raised when image wasn't loaded
     *
     * @return boolean
     * @throws CESyntaxError
     *
     * @access private
     */
    private function _is_initialized($exc=true)
    {
        if (is_null($this->_body)) {
            if ($exc) {
                throw new CESyntaxError('Image no loaded - call Image::open() first.');
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Converts html notation of color to rgb array
     *
     * Return false or 3 element array with rgb data.
     *
     * @param string $hex color in html notation
     *
     * @return mixed
     *
     * @access private
     */
    private function _hex2rgb($hex)
    {
        if (6 != strlen($hex)) {
            return false;
        }

        $data = str_split($hex, 2);
        $data[0] = hexdec($data[0]);
        $data[1] = hexdec($data[1]);
        $data[2] = hexdec($data[2]);
        return $data;
    }

    /**
     * Creates new image object
     *
     * If $width or $height are null, these values are got from
     * $this->{width,height}.
     *
     * @param integer $width
     * @param integer $height
     *
     * @return object
     *
     * @access private
     */
    private function _newImage($width=null, $height=null)
    {
        //docelowa szerokosc
        if (is_null($width)) {
            $width = $this->width;
        }
        //docelowa wysokosc
        if (is_null($height)) {
            $height = $this->height;
        }

        if ($this->truecolor) {
            return imagecreatetruecolor($width, $height);
        } else {
            return imagecreate($width, $height);
        }
    }

    /**
     * Check for correct image format
     *
     * If format is incorrect, it return default format used by Image class
     * (@see Image::format)
     *
     * @param string $format
     *
     * @return string
     *
     * @access private
     */
    private function _checkFormat($format)
    {
        if (!array_key_exists($format, $this->_formats)) {
            $format = self::FORMAT;
        }
        if ('jpg' == $format) {
            $format = 'jpeg';
        }
        return $format;
    }

    /**
     * Put given argument as content of Image::$_body
     *
     * @param object $dst
     *
     * @return boolean
     *
     * @access private
     */
    private function _swap(&$dst)
    {
        if (is_resource($dst)) {
            if (!is_null($this->_body)) {
                imagedestroy($this->_body);
            }
            $this->_body = &$dst;

            $this->width  = imagesx($this->_body);
            $this->height = imagesy($this->_body);

            return true;
        } else {
            return false;
        }
    }
}

?>
