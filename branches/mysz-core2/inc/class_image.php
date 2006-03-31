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
 * Error codes:
 * 100 - CESyntaxError       Cannot read "%s"
 * 101 - CESyntaxError       Invalid filter: "%s".
 * 102 - CESyntaxError       Image no loaded - call Image::open() first.
 * 103 - CESyntaxError       Attribute "%s" is read only.
 * 200 - CETypeError         Both values: $max_width and $max_height cannot be false
 * 201 - CETypeError         Specified image (%s) has dimensions different then %dx%d.
 * 202 - CETypeError         Incorrect quant of elements in "$padding" parameter.
 * 300 - CEFileSystemError   Cannot write to "%s" directory.
 * 301 - CEFileSystemError   File "%s" already exists.
 * 400 - CENotFound          Attribute "%s" doesn't exists.
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
     * Constant - max size of image. Used when in {scale,grow,shrink}Prop
     * as $max_{width,height} is false.
     */
    const MAX_SIZE = 40960;

    /**
     * Properties of image
     *
     * Have to be read only, so we keep it in internal array and
     * access to them is via __set() and __get() methods
     *
     * @var array
     * @access protected
     */
    protected $properties = array(
        'width'     => null,
        'height'    => null,
        'truecolor' => null
    );

    /**
     * Image descriptor (handler)
     *
     * @var object
     * @access protected
     */
    protected $body = null;

    /**
     * Available filters
     *
     * List is initialized from constructor, because filters are available
     * only if php use it's internal gd.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $filters;

    /**
     * Image formats handled by this class.
     *
     * This array maps possible formats to assigned with them functions from
     * image extension.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $formats = array(
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
     * Params description: {@link Image::open()}
     *
     * @param string  $fname
     * @param mixed   $width
     * @param mixed   $height
     * @param boolean $exact
     *
     * @access public
     */
    public function __construct($fname=null, $width=null, $height=null,
                                $exact=true)
    {
        // these constants aren't compiled in if php use external gd.
        // In this case, we cant put here these constant, only
        // if imagefilter() function exists (it means: when php use bundled
        // version of gd)
        if (function_exists('imagefilter') && !count(self::$filters)) {
            self::$filters = array(
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

        if (!is_null($fname)) {
            $this->open($fname, $width, $height, $exact);
        }
    }

    /**
     * Destructor
     *
     * Destroy opened image
     *
     * @access public
     */
    public function __destruct()
    {
        if (!is_null($this->body)) {
            imagedestroy($this->body);
        }
    }

    /**
     * Read file and assign properties.
     *
     * If $width and $height are not null, and $exact:
     * - is true: if image dimensions are other then $width x $height, raise
     *   CETypeError;
     * - is false: propportional scale image to specified dimensions.
     *
     * $width or $height can be false ({@link Image::scaleProp() more}).
     *
     * @param string  $fname  filename to read
     * @param mixed   $width
     * @param mixed   $height
     * @param boolean $exact
     *
     * @return mixed
     * @throws CEFileSystemError ({@link CEFileSystemError description})
     * @throws CETypeError       ({@link CETypeError description})
     *
     * @access public
     */
    public function open($fname, $width=null, $height=null, $exact=true)
    {
        $fname = Path::real($fname);
        if (!file_exists($fname) || !is_readable($fname)) {
            throw new CEFileSystemError(sprintf('Cannot read "%s".', $fname), 100);
        }

        $dst = imagecreatefromstring(file_get_contents($fname));
        $this->swap($dst);

        if (!is_null($width) && !is_null($height)) {
            if (false === $width && false === $height) {
                throw new CETypeError('Both values: $max_width and $max_height ' .
                    'cannot be false', 200);
            }

            if ($exact) { // if image has other dimensions then specified
                if (
                    ( false !== $width  && $width  != $this->width  ) ||
                    ( false !== $height && $height != $this->height )
                   ) {

                    throw new CETypeError(sprintf('Specified image (%s) has ' .
                        'dimensions different then %dx%d.',

                        $fname,
                        $width,
                        $height
                    ), 201);
                }
            } else {
                return $this->scaleProp($width, $height, 'ffffff', false, 'c', 0);
            }
        }

        return true;
    }

    /**
     * Send image to standard output.
     *
     * @param string  $format       possible values in self::$formats
     * @param integer $quality      quality of output jpeg
     * @param boolean $sendHeaders if true, send content-type header
     *
     * @access public
     */
    public function show($format=null, $quality=null, $sendHeaders=true)
    {
        $this->isInitialized();

        $format = $this->checkFormat($format);

        if ($sendHeaders) {
            header('Content-type: image/' . $format);
        }

        if (is_null($quality)) {
            $quality = self::QUALITY;
        }

        $fun = self::$formats[$format];
        $fun($this->body, '', $quality);
    }

    /**
     * Save image to given file.
     *
     * @param string $fname      filename
     * @param string $format     possible values in self::$formats
     * @param integer $quality   quality of output jpeg
     * @param boolean $overwrite if true, it overwrite file if it exists
     *
     * @throws CEFilesystemError ({@link CEFileSystemError description})
     *
     * @access public
     */
    public function saveToFile($fname, $format=null, $quality=null, $overwrite=false)
    {
        $this->isInitialized();

        $pathinfo = pathinfo($fname);

        if (is_null($format)) {
            $format = $pathinfo['extension'];
        }
        $format = $this->checkFormat(strtolower($format));

        if ('' == $pathinfo['dirname']) {
            $pathinfo['dirname'] = '.';
        }
        $pathinfo['dirname'] = realpath($pathinfo['dirname']);
        $fullpath = Path::join($pathinfo['dirname'], $pathinfo['basename']);

        if (!is_writeable($pathinfo['dirname'])) {
            throw new CEFileSystemError(sprintf('Cannot write to "%s" directory.', $pathinfo['dirname']), 300);
        }
        if (file_exists($fullpath)) {
            if ($overwrite) {
                unlink($fullpath);
            } else {
                throw new CEFileSystemError(sprintf('File "%s" already exists.', $fullpath), 301);
            }
        }

        if (is_null($quality)) {
            $quality = self::QUALITY;
        }

        $fun = self::$formats[$format];
        $fun($this->body, $fname, $quality);
    }

    /**
     * Return image as an string.
     *
     * @param string  $format  possible alues in self::$formats
     * @param integer $quality quality of output jpeg
     *
     * @return string
     *
     * @access public
     */
    public function toString($format=null, $quality=null)
    {
        $format = $this->checkFormat($format);

        $fname = tempnam(getcwd(), 'coreImg_');
        $this->saveToFile($fname, $format, $quality, true);
        $img = file_get_contents($fname);
        unlink($fname);
        return $img;
    }

    /**
     * Proportional scaling of image
     *
     * If $fill == true, image dimensions has been equal to $max_{width,height}.
     * Free space between scaled image and edge of image will be filled by
     * $bgcolor.
     * If $fill == false, image dimensions will be equal to scaled image, not
     * bigger then $max_{width,height}.
     *
     * If $padding is specified, image will be scaled down to be equal
     * or smaller then $max_width-$padding and $max_height-$padding, and free
     * space will be filled by $bgcolor.
     * $padding can be either an integer (all padding are equal) or array:
     * - 2 elements for top/bottom and left/right paddings;
     * - 4 elemnts for top, tight, bottm and left values of padding
     * Scaled image wil be 'placed' into selected (in $position) place of
     * output image.
     *
     * $max_width or $max_height (but no both) can be false. In this case image
     * will be scaled only for second, non-false dimension.
     *
     * Return false if fail.
     *
     * @param mixed   $max_width
     * @param mixed   $max_height
     * @param string  $bgcolor
     * @param boolean $fill
     * @param string  $position
     * @param mixed   $padding
     *
     * @return boolean
     * @throws CETypeError ({@link CETypeError description})
     *
     * @access public
     */
    public function scaleProp($max_width, $max_height, $bgcolor='ffffff',
                              $fill=true, $position='c', $padding=0)
    {
        $this->isInitialized();

        if ($max_width === false && $max_height === false) {
            throw new CETypeError('Both values: $max_width and $max_height cannot be false', 200);
        }
        if ($max_width  === false) {
            $max_width  = self::MAX_SIZE;
            $fill       = false;
        }
        if ($max_height === false) {
            $max_height = self::MAX_SIZE;
            $fill       = false;
        }

        if (!is_array($padding)) {
            $padding = array_fill(0, 4, $padding);
        } elseif (2 == count($padding)) {
            $padding = array($padding[0], $padding[1], $padding[0], $padding[1]);
        } elseif (4 != count($padding)) {
            throw new CETypeError('Incorrect quant of elements in "$padding" parameter.', 202);
        }

        $width  = $max_width  - ($padding[1] + $padding[3]);
        $height = $max_height - ($padding[0] + $padding[2]);

        list($th_width, $th_height) = $this->calculateSize($width, $height);

        if ($fill) {
            $dst_width  = $max_width;
            $dst_height = $max_height;

            $pos = $this->calculatePosition($width, $height, $position);
        } else {
            $dst_width  = $th_width  + ($padding[1] + $padding[3]);
            $dst_height = $th_height + ($padding[0] + $padding[2]);

            $pos = $this->calculatePosition($th_width, $th_height, $position);
        }

        // initializing destination image
        $dst = $this->newImage($dst_width, $dst_height);

        imagefill($dst, 0, 0, $this->color($bgcolor)); //set background color
        $test = imagecopyresampled($dst, $this->body,
            $pos[0]+$padding[3], $pos[1]+$padding[0],
            $pos[2], $pos[3],
            $th_width, $th_height,
            $this->properties['width'], $this->properties['height']
        );

        if ($test) {
            return $this->swap($dst);
        } else {
            return false;
        }
    }

    /**
     * Non proportional scaling
     *
     * If $padding is specified, image will be scaled down to be equal
     * or smaller then $max_width-$padding and $max_height-$padding, and free
     * space will be filled by $bgcolor.
     * $padding can be either an integer (all padding are equal) or array of
     * top, right, bottom, and left padding value.
     *
     * Return false if fail
     *
     * @param integer $width   width of image
     * @param integer $height  height of image
     * @param mixed   $padding
     * @param string  $bgcolor background color
     *
     * @return boolean
     *
     * @access public
     */
    public function scaleNonProp($width, $height, $padding=0, $bgcolor='ffffff')
    {
        $this->isInitialized();

        $dst = $this->newImage($width, $height);
        if ($padding) {
            imagefill($dst, 0, 0, $this->color($bgcolor));
        }
        if (!is_array($padding)) {
            $padding = array_fill(0, 4, $padding);
        }

        $test = imagecopyresampled($dst, $this->body,
                $padding[3], $padding[0], 0, 0,
                $width - ($padding[1] + $padding[3]),
                $height - ($padding[0] + $padding[2]),
                $this->properties['width'], $this->properties['height']
        );

        if ($test) {
            return $this->swap($dst);
        } else {
            return false;
        }
    }

    /**
     * Grow an image (proportional)
     *
     * Grow image if is smaller then $max_width & $max_height. Return true if
     * bigger and leave image non touched.
     * Use Image::scaleProp()
     *
     * $max_width or $max_height (but no both) can be false. In this case image
     * will be scaled only for second, non-false dimension.
     *
     * Params description: {@link Image::scaleProp()}
     *
     * @param integer $max_width
     * @param integer $max_height
     * @param string  $bgcolor
     * @param boolean $fill
     * @param string  $position
     * @param mixed   $padding
     *
     * @return boolean
     *
     * @access public
     */
    public function growProp($max_width, $max_height, $bgcolor='ffffff',
                             $fill=true, $position='c', $padding=0)
    {
        if ($max_width === false && $max_height === false) {
            throw new CETypeError('Both values: $max_width and $max_height cannot be false', 200);
        }
        if (
                ($max_width  !== false && $this->properties['width']  <= $max_width)  ||
                ($max_height !== false && $this->properties['height'] <= $max_height)
           ) {
            return $this->scaleProp($max_width, $max_height, $bgcolor,
                                   $fill, $position, $padding);
       } else {
           return true;
       }
    }

    /**
     * Grow an image (non-proportional)
     *
     * Grow image if is smaller then $max_width & $max_height. Return true if
     * bigger and leave image non touched.
     * Use {@link Image::scaleNonProp()}
     *
     * Params description: {@link Image::scaleProp()}
     *
     * @param integer $max_width
     * @param integer $max_height
     * @param string  $bgcolor
     * @param boolean $fill
     * @param string  $position
     * @param mixed   $padding
     *
     * @return boolean
     *
     * @access public
     */
    public function growNonProp($max_width, $max_height,
                                $padding=0, $bgcolor='ffffff')
    {
        if ($this->properties['width'] <= $max_width ||
                $this->properties['height'] <= $max_height) {
            return $this->scaleNonProp($max_width, $max_height, $bgcolor,
                                      $fill, $position, $padding);
       } else {
           return true;
       }
    }

    /**
     * Shrink an image (proportional)
     *
     * Shrinks image if is bigger then $min_width & $min_height. Return true if
     * smaller and leave image non touched.
     * Use Image::scaleProp()
     *
     * Params description: {@link Image::scaleProp()}
     *
     * @param integer $min_width
     * @param integer $min_height
     * @param string  $bgcolor
     * @param boolean $fill
     * @param string  $position
     * @param mixed   $padding
     *
     * @return boolean
     *
     * @access public
     */
    public function shrinkProp($min_width, $min_height, $bgcolor='ffffff',
                               $fill=true, $position='c', $padding=0)
    {
        if ($min_width === false && $min_height === false) {
            throw new CETypeError('Both values: $min_width and $min_height cannot be false', 200);
        }
        if (
                ($min_width  !== false && $this->properties['width']  >= $min_width)  ||
                ($min_height !== false && $this->properties['height'] >= $min_height)
           ) {
            return $this->scaleProp($min_width, $min_height, $bgcolor,
                                   $fill, $position, $padding);
       } else {
           return true;
       }
    }

    /**
     * Shrink an image (non-proprtional)
     *
     * Shrinks image if is bigger then $min_width & $min_height. Return true if
     * smaller and leave image non touched.
     * Use Image::scaleNonProp()
     *
     * Params description: {@link Image::scaleProp()}
     *
     * @param integer $min_width
     * @param integer $min_height
     * @param string  $bgcolor
     * @param boolean $fill
     * @param string  $position
     * @param mixed   $padding
     *
     * @return boolean
     *
     * @access public
     */
    public function shrinkNonProp($min_width, $min_height,
                                  $padding=0, $bgcolor='ffffff')
    {
        if ($this->properties['width'] >= $min_width ||
                $this->properties['height'] >= $min_height) {
            return $this->scaleNonProp($min_width, $min_height,
                                      $padding, $bgcolor);
        } else {
            return true;
        }
    }

    /**
     * Cropping of an image, method 1.
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
        $this->isInitialized();

        $dst = $this->newImage($h, $w);

        $test = imagecopy($dst, $this->body, 0, 0, $x, $y, $h, $w);
        if ($test) {
            return $this->swap($dst);
        } else {
            return false;
        }
    }

    /**
     * Cropping of an image, method 2.
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
     * @param string $filter     filter name (available filters from self::$filters)
     * @param string $filter,... {@link htp://php.net/imagefilter description}
     *
     * @return boolean
     * @throws CESyntaxError if invalid filter name ({@link CESyntaxError description})
     *
     * @access public
     */
    public function filter($filter)
    {
        $this->isInitialized();

        if (!in_array($filter, self::$filters)) {
            throw new CESyntaxError(sprintf('Invalid filter: "%s".', $filter), 101);
        }

        // Workaround: i don't know why, but imagefilter() throw some fatal
        // error if there is all 4 possible arguments, so i must give him
        // only that, which are required at call time (depends of filter)
        $argc = func_num_args();
        $argv = func_get_args();
        switch ($argc) {
            case 1: return imagefilter($this->body, self::$filters[$filter]);
            case 2: return imagefilter($this->body, self::$filters[$filter], $argv[1]);
            case 3: return imagefilter($this->body, self::$filters[$filter], $argv[1], $argv[2]);
            default:
                return imagefilter($this->body, self::$filters[$filter], $argv[1], $argv[2], $argv[3]);
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
            $rgb = $this->hex2rgb($r);
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
     * @param boolean $ignore_transparent {@link http://php.net/imagerotate}
     *
     * @access public
     */
    public function rotate($angle, $bgColor='ffffff', $ignoreTransparent=false)
    {
        $this->isInitialized();

        $angle = (float)$angle;
        $color = $this->color($bgColor);
        $this->body = imagerotate($this->body, $angle, $color, $ignoreTransparent);
    }

    /**
     * Apply an border to image
     *
     * Border can be solid, or pattern ({@link http://php.net/imagesetstyle}).
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
     * imagecolorallocate() ({@link http://php.net/imagecolorallocate}).
     *
     * If You want to set pattern for edges, You must set proper arrays
     * ({@link http://php.net/imagesetstyle}).
     *
     * @param mixed $thickness
     * @param mixed $colT
     * @param mixed $colR
     * @param mixed $colB
     * @param mixed $colL
     *
     * @return boolean
     * @throws CETypeError ({@link CETypeError description})
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
            throw new CETypeError('Incorrect quant of elements in "$thickness" parameter.', 202);
        }

        if (is_null($colR)) { //all borders have one style
            $colR = $colB = $colL = $colT;
        } elseif (is_null($colB)) { //borders top and bottom have one style, left and right - second
            $colB = $colT;
            $colL = $colR;
        }
        $col = array($colT, $colR, $colB, $colL);

        $this->drawLine( //top
            array(0, $thickness[0]/2),
            array($this->properties['width'], $thickness[0]/2),
            $colT, $thickness[0]);
        $this->drawLine( //right
            array($this->properties['width'] - ($thickness[1]/2), 0),
            array($this->properties['width'] - ($thickness[1]/2),
                $this->properties['height']),
            $colR, $thickness[1]);
        $this->drawLine( //left
            array($this->properties['width'],
                $this->properties['height'] - ($thickness[2]/2) ),
            array(0, $this->properties['height'] - ($thickness[2]/2) ),
            $colB, $thickness[2]);
        $this->drawLine( //bottom
            array($thickness[3]/2, $this->properties['height']),
            array($thickness[3]/2, 0),
            $colL, $thickness[3]);

        return true;
    }

    /**
     * Add layer on top of image.
     *
     * If $layer is string, it suppose to be file name, which be loaded and
     * merged with current image.
     * $layer can also be an image object ({@link Image::get()}).
     *
     * Can be used to adding some blenda do thumbnails, for example.
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
            $i = new Image($layer);
            $layer = $i->get();
        }
        if (!$layer) {
            return false;
        }

        return imagecopymerge($this->body, $layer, 0, 0, 0, 0, imagesx($layer),
                imagesy($layer), $alpha);
    }

    /**
     * Allocate color.
     *
     * Can be used when adding borders etc.
     * {@link http://php.net/imagecolorallocate}
     *
     * @param string   $hex color in html notation
     * @param resource $img if not null, used by imagecolorallocate
     *
     * @return integer
     *
     * @access public
     */
    public function color($hex, &$img=null)
    {
        list($r, $g, $b) = $this->hex2rgb($hex);
        if (is_null($img)) {
            return imagecolorallocate($this->body, $r, $g, $b);
        } else {
            return imagecolorallocate($img, $r, $g, $b);
        }
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
        return $this->body;
    }

    /**
     * Draw a line on image
     *
     * Currently used only for drawig borders/
     * $begin and $end holds coordinates of begin and end points of line.
     * $color can be:
     * - integer - if line have to be solid
     * - array - if line have to be an pattern ({@link http://php.net/imagesestyle})
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
    protected function drawLine(array $begin, array $end, $color, $thickness)
    {
        imagesetthickness($this->body, $thickness);

        if (!is_array($color)) { //solid
            if (is_string($color)) {
                $color = $this->color($color);
            }
            imageline($this->body,
                    $begin[0], $begin[1],
                    $end[0], $end[1],
                    $color);
        } else { //pattern
            imagesetstyle($this->body, $color);
            imageline($this->body,
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
     * If $exc == false, isInitialized() return false.
     *
     * @param boolean $exc if true, an exception is raised when image wasn't loaded
     *
     * @return boolean
     * @throws CESyntaxError ({@link CESyntaxError description})
     *
     * @access protected
     */
    protected function isInitialized($exc=true)
    {
        if (is_null($this->body)) {
            if ($exc) {
                throw new CESyntaxError('Image no loaded - call Image::open() first.', 102);
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
     * @access protected
     */
    protected function hex2rgb($hex)
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
     * @param boolean $truecolor
     *
     * @return object
     *
     * @access protected
     */
    protected function newImage($width=null, $height=null, $truecolor=null)
    {
        //docelowa szerokosc
        if (is_null($width)) {
            $width = $this->properties['width'];
        }
        //docelowa wysokosc
        if (is_null($height)) {
            $height = $this->properties['height'];
        }
        if (is_null($truecolor)) {
            $truecolor = $this->properties['truecolor'];
        }

        if ($truecolor) {
            return imagecreatetruecolor($width, $height);
        } else {
            return imagecreate($width, $height);
        }
    }

    /**
     * Check for correct image format
     *
     * If format is incorrect, it return default format used by Image class.
     * See: {@link Image::FORMAT}, {@link Image::$formats}
     *
     * @param string $format
     *
     * @return string
     *
     * @access protected
     */
    protected function checkFormat($format=null)
    {
        if (is_null($format) || !array_key_exists($format, self::$formats)) {
            $format = self::FORMAT;
        }
        if ('jpg' == $format) {
            $format = 'jpeg';
        }
        return $format;
    }

    /**
     * Calculate proportional size of scaled image
     *
     * @param integer $max_width
     * @param integer $max_height
     *
     * @return array
     *
     * @access protected
     */
    protected function calculateSize(&$max_width, &$max_height)
    {
        $div_width  = (double)($this->properties['width']  / $max_width);
        $div_height = (double)($this->properties['height'] / $max_height);

        $div = max($div_width, $div_height);

        $ret = array();
        $ret[] = (double)($this->properties['width']  / $div);
        $ret[] = (double)($this->properties['height'] / $div);
        $ret[] = &$div;

        return $ret;
    }

    /**
    * Calculate position of scaled image
    *
    * (coordinates of left top point and
    *
    * @param integer $max_width
    * @param integer $max_height
    * @param string  $position
    *
    * @return array
    *
    * @access protected
    */
    protected function calculatePosition(&$max_width, &$max_height, $position)
    {
        //'c', 'lt', 'ct', 'rt', 'lc', 'rc', 'lb', 'cb', 'rb'
        list($th_w, $th_h) = $this->calculateSize($max_width, $max_height);
        $ret = array(0, 0, 0, 0);
        switch ($position)
        {
            case 'lt':
            break;
            case 'ct':
                $ret[0] = ($max_width-$th_w)/2;
            break;
            case 'rt':
                $ret[0] = $max_width-$th_w;
            break;
            case 'lc':
                $ret[1] = ($max_height-$th_h)/2;
            break;
            case 'rc':
                $ret[0] = $max_width-$th_w;
                $ret[1] = ($max_height-$th_h)/2;
            break;
            case 'lb':
                $ret[1] = $max_height-$th_h;
            break;
            case 'cb':
                $ret[0] = ($max_width-$th_w)/2;
                $ret[1] = $max_height-$th_h;
            break;
            case 'rb':
                $ret[0] = $max_width-$th_w;
                $ret[1] = $max_height-$th_h;
            break;
            default:
                if ($th_w > $th_h) {
                    $ret[1] = ($max_height - $th_h) / 2;
                } else {
                    $ret[0] = ($max_width - $th_w) / 2;
                }
        }

        return $ret;
    }

    /**
     * Put given argument as content of Image::$body
     *
     * @param object $dst
     *
     * @return boolean
     *
     * @access protected
     */
    protected function swap(&$dst)
    {
        if (is_resource($dst)) {
            if (!is_null($this->body)) {
                imagedestroy($this->body);
            }
            $this->body = $dst;

            $this->properties['width']     = imagesx($this->body);
            $this->properties['height']    = imagesy($this->body);
            $this->properties['truecolor'] = imageistruecolor($this->body);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Overloaded setter
     *
     * Use Image::$properties as store container.
     *
     * @param string $k name of property
     * @param mixed  $v value of property
     *
     * @throws CESyntaxError ({@link CESyntaxError description})
     * @throws CENotFound    ({@link CENotFound description})
     *
     * @access public
     */
    public function __set($k, $v)
    {
        if (array_key_exists($k, $this->properties)) {
            throw new CESyntaxError(sprintf('Attribute "%s" is read only.', $k), 103);
        } else {
            throw new CENotFound(sprintf('Attribute "%s" doesn\'t exists.', $k), 400);
        }
    }

    /**
     * Overloaded getter
     *
     * Use Image::$properties as store container.
     *
     * @param string $k name of property
     *
     * @return mixed
     * @throws CENotFound ({@link CENotFound description})
     *
     * @access public
     */
    public function __get($k)
    {
        if (array_key_exists($k, $this->properties)) {
            return $this->properties[$k];
        } else {
            throw new CENotFound(sprintf('Attribute "%s" doesn\'t exists.', $k), 400);
        }
    }

    /**
     * Overloaded isset()
     *
     * Use Image::$properties as store container.
     *
     * @param string $k name of property
     *
     * @return boolean
     *
     * @access public
     */
    public function __isset($k)
    {
        if (array_key_exists($k, $this->properties) &&
                !is_null($this->properties[$k])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return array of available filters
     *
     * Can be usefull when we want to show which filters are available
     * at runtime.
     *
     * @return array
     */
    public function getFilters()
    {
        return self::$filters;
    }
}

?>
