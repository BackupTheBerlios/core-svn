<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$
  
class Image {
    const format = 'png';
    const quality = 75;

    private $fname = null;
    private $body = null;

    public $height = 0;
    public $width = 0;
    public $truecolor = null;

    public $dst_width = 0;
    public $dst_height = 0;

    private $filters = array();
    private $formats = array(
        'jpg'  => 'imagejpeg',
        'jpeg' => 'imagejpeg',
        'gif'  => 'imagegif',
        'png'  => 'imagepng'
    );
    private $positions = array('center', 'lt', 'lc', 'lb', 'rt', 'rc', 'rb');

    public function __construct($fname=null)
    {
        // these constants aren't compiled in if php use not bundle gd, but
        // some other. In this case, we cant put here these constant, only
        // if imagefilter() function exists (it means: when php use bundled
        // version of gd)
        if (function_exists('imagefilter')) {
            $this->filters = array(
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

    public function __destruct()
    {
        if (!is_null($this->body)) {
            imagedestroy($this->body);
        }
    }

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
            $this->swapImage($dst);
            $this->width        = imagesx($dst);
            $this->height       = imagesy($dst);
            $this->truecolor    = imageistruecolor($dst);
        }
    }

    public function scale_prop($dst_width, $dst_height, $bgcolor='ffffff',
                               $pos='center')
    {
        $this->is_initialized();

        $dst = $this->newImage($dst_width, $dst_height);

        //background color
        imagefill($dst, 0, 0, $this->color($bgcolor));

        // we must to now dzielna
        $div_width  = $this->width  / $dst_width;
        $div_height = $this->height / $dst_height;

        $div = max($div_width, $div_height);
        unset($div_width, $div_height);

        // proportional size of shrinked picture
        $th_w = $this->width  / $div;
        $th_h = $this->height / $div;

        if ($th_w > $th_h) {
            if (!imagecopyresampled($dst, $this->body,
                0, round(($th_w - $th_h)/2),
                0, 0,
                $th_w, $th_h,
                $this->width, $this->height)) {
                return false;
            }
        } else {
            if (!imagecopyresampled($dst, $this->body,
                    round(($th_h - $th_w)/2), 0,
                    0, 0,
                    $th_w, $th_h,
                    $this->width, $this->height)) {
                return false;
            }
        }

        return $this->swapImage($dst);
    }

    public function scale_nonprop($dst_width, $dst_height,
                                  $bgcolor='ffffff', $pos='center')
    {
        $this->is_initialized();
        
        $dst = $this->newImage($dst_width, $dst_height);

        imagefill($dst, 0, 0, $this->color($bgcolor));
        
        if (!imagecopyresampled($dst, $this->body,
                0, 0, 0, 0,
                $dst_width, $dst_height, $this->width, $this->height)) {
            return false;
        }

        return $this->swapImage($dst);
    }

    public function scale($dst_width, $dst_height, $prop,
                          $bgcolor='ffffff', $pos='center')
    {
        if ($prop) {
            return $this->scale_prop($dst_width, $dst_height, $bgcolor, $pos);
        } else {
            return $this->scale_nonprop($dst_width, $dst_height, $bgcolor, $pos);
        }
    }

    public function crop1($t, $l, $w, $h)
    {
        $this->is_initialized();

        $dst = $this->newImage($w, $h);

        $test = imagecopy($dst, $this->body, 0, 0, $l, $t, $w, $h);
        if ($test) {
            return $this->swapImage($dst);
        } else {
            return false;
        }
    }

    public function crop2($t, $r, $b, $l)
    {
        $w = $r - $l;
        $h = $b - $t;
        
        return $this->crop1($t, $l, $w, $h);
    }
    public function show($format=null, $quality=75, $send_headers=true)
    {
        $this->is_initialized();

        $format = $this->checkFormat($format);

        if ($send_headers) {
            header('Content-type: image/' . $format);
        }

        if (is_null($quality)) {
            $quality = self::quality;
        }

        $this->formats[$format]($this->body, '', $quality);
    }

    public function saveToFile($fname, $format=null, $quality=75, $overwrite=false)
    {
        $this->is_initialized();

        $pathinfo = pathinfo($fname);

        if (is_null($format)) {
            $format = $pathinfo['extension'];
        }
        $format = $this->checkFormat($format);

        if ($pathinfo['dirname'] == '') {
            $pathinfo['dirname'] = '.';
        }
        $pathinfo['dirname'] = realpath($pathinfo['dirname']);
        $fullpath = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['basename'];

        if (!is_writeable($pathinfo['dirname'])) {
            throw new Exception(sprintf('Cannot write to "%s" directory.', $pathinfo['dirname']));
        }
        if (file_exists($fullpath)) {
            if ($overwrite) {
                unlink($fullpath);
            } else {
                throw new Exception(sprintf('File "%s" already exists.', $fullpath));
            }
        }

        if (is_null($quality)) {
            $quality = self::quality;
        }

        $this->formats[$format]($this->body, $fname, $quality);
    }

    public function filter($filter) //, $arg1=null, $arg2=null, $arg3=null)
    {
        $this->is_initialized();

        if (!in_array($filter, $this->filters)) {
            throw new Exception(sprintf('Invalid filter: "%s".', $filter));
        }

        // Workaround: i don't know why, but imagefilter() throw some fatal
        // error if there is all 4 possible arguments, so i must give him
        // only that, which are required at call time (depends of filter)
        $argc = func_num_args();
        $argv = func_get_args();
        switch ($argc) {
            case 1: return imagefilter($this->body, $this->filters[$filter]);
            case 2: return imagefilter($this->body, $this->filters[$filter], $argv[1]);
            case 3: return imagefilter($this->body, $this->filters[$filter], $argv[1], $argv[2]);
            default:
                return imagefilter($this->body, $this->filters[$filter], $argv[1], $argv[2], $argv[3]);
        }
        //return imagefilter($this->body, $this->filters[$filter], $arg1, $arg2, $arg3);
    }

    public function rotate($angle, $bgcolor='ffffff', $ignore_transparent = false)
    {
        $this->is_initialized();

        $angle = (float)$angle;
        $color = $this->color($bgcolor);
        $this->body = imagerotate($this->body, $angle, $color, $ignore_transparent);
    }

    public function border($thick, $color='000000')
    {
        imagesetthickness($this->body, $thick);

        if ()

        $coords = $thick/2;
        $this->line(array(0, $coords), array($this->width, $coords), $color[0]);

        /*
        if (is_array($style1)) {
            imagesetstyle($this->body, $style1);
        }
        imageline($this->body,
                    0, $coords,
                    $this->width, $coords,
                    $col1);
        *//*
        if (is_array($style2)) {
            imagesetstyle($this->body, $style2);
        }
        imageline($this->body,
                    $this->width - $coords, 0,
                    $this->width - $coords, $this->height,
                    $col2);

        if (is_array($style3)) {
            imagesetstyle($this->body, $style3);
        }
        imageline($this->body,
                    $this->width, $this->height - $coords,
                    0, $this->height - $coords,
                    $col3);

        if (is_array($style4)) {
            imagesetstyle($this->body, $style4);
        }
        imageline($this->body,
                    $coords, $this->height,
                    $coords, 0,
                    $col4);
        */

        return true;
    }

    public function addLayer($fname, $alpha=100)
    {
        $layer = $this->open($fname, true);
        if (!$layer) {
            return false;
        }

        return imagecopymerge($this->body, $layer, 0, 0, 0, 0, imagesx($layer),
                imagesy($layer), $alpha);
    }

    public function toString($format=null, $quality = null)
    {
        $format = $this->checkFormat($format);

        $fname = tempnam(getcwd(), 'core_');
        $this->saveToFile($fname, $format, $quality, true);
        $img = file_get_contents($fname);
        unlink($fname);
        return $img;
    }

    private function is_initialized($exc = true)
    {
        if (is_null($this->body)) {
            if ($exc) {
                throw new Exception('Image no loaded - call Image::open() first.');
            } else {
                return false;
            }
        }
        return true;
    }

    private function hex2rgb($hex)
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

    public function color($hex)
    {
        list($r, $g, $b) = $this->hex2rgb($hex);

        return imagecolorallocate($this->body, $r, $g, $b);
    }

    private function newImage($width=null, $height=null)
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

    private function checkFormat($format)
    {
        if (!array_key_exists($format, $this->formats)) {
            $format = self::format;
        }
        if ('jpg' == $format) {
            $format = 'jpeg';
        }
        return $format;
    }

    private function swapImage(&$dst)
    {
        if (is_resource($dst)) {
            if (!is_null($this->body)) {
                imagedestroy($this->body);
            }
            $this->body = &$dst;

            $this->width  = imagesx($this->body);
            $this->height = imagesy($this->body);
            
            return true;
        } else {
            return false;
        }
    }

    private function line($begin, $end, $color)
    {
        //Arrays::debug(func_get_args(), 1);
        if (is_string($color)) {
            //$color = $this->color($color);
            imageline($this->body,
                      $begin[0], $begin[1],
                      $end[0], $end[1],
                      $color);
        } elseif (is_array($color)) {
            imagesetstyle($this->body, $color);
            imageline($this->body,
                      $begin[0], $begin[1],
                      $end[0], $end[1],
                      IMG_COLOR_STYLED);
        }
        return true;
    }


    // shortcuts filter functions
    public function filter_negate() {
        return $this->filter('negate');
    }
    public function filter_grayscale() {
        return $this->filter('grayscale');
    }
    public function filter_brightness($value) {
        return $this->filter('brightness', $value);
    }
    public function filter_contrast($value) {
        return $this->filter('contrast', $value);
    }
    public function filter_colorize($r, $g=null, $b=null) {
        if (is_null($g) || is_null($b)) {
            $rgb = $this->hex2rgb($r);
            if (false === $rgb) {
                return false;
            }
            list($r, $g, $b) = $rgb;
        }
        return $this->filter('colorize', $r, $g, $b);
    }
    public function filter_edge() {
        return $this->filter('edge');
    }
    public function filter_emboss() {
        return $this->filter('emboss');
    }
    public function filter_gaussian() {
        return $this->filter('gaussian');
    }
    public function filter_blur() {
        return $this->filter('blur');
    }
    public function filter_sketchy() {
        return $this->filter('sketchy');
    }
    public function filter_smooth($value) {
        return $this->filter('smooth', $value);
    }
}

?>
