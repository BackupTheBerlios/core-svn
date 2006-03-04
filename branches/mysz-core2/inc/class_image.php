<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$
  
class Image {
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

    public function open($fname=null)
    {
        if (is_null($fname) || !file_exists($fname)) {
            return false;
        }

        $this->body = imagecreatefromstring(file_get_contents($fname));
        $this->width = imagesx($this->body);
        $this->height = imagesy($this->body);
        $this->truecolor = imageistruecolor($this->body);
    }

    public function scale($dst_width=null, $dst_height=null, $prop=true,
                          $bgcolor='ffffff', $position='center')
    {
        $this->is_initialized();
        
        $dst_data = array();
        list($dst_data['width'], $dst_data['height'], $dst) = $this->image($dst_width, $dst_height);

        $color = $this->color($bgcolor);
        imagefill($dst, 0, 0, $color);
        
        if ($prop) { //skalujemy proporcjonalnie
            $div_width  = $this->width  / $dst_data['width'];
            $div_height = $this->height / $dst_data['height'];

            $dst_data['div'] = max($div_width, $div_height);
            unset($div_width, $div_height);

            $dst_data['th_w'] = $this->width   / $dst_data['div'];
            $dst_data['th_h'] = $this->height / $dst_data['div'];

            if ($dst_data['th_w'] > $dst_data['th_h']) {
                $dst_data['coord'] = round(($dst_data['th_w'] - $dst_data['th_h'])/2);

                if (!imagecopyresampled($dst, $this->body,
                    0, $dst_data['coord'],
                    0, 0,
                    $dst_data['th_w'], $dst_data['th_h'],
                    $this->width, $this->height)) {
                    return false;
                }
            } else {
                $dst_data['coord'] = round(($dst_data['th_h'] - $dst_data['th_w'])/2);

                if (!imagecopyresampled($dst, $this->body,
                        $dst_data['coord'], 0,
                        0, 0,
                        $dst_data['th_w'], $dst_data['th_h'],
                        $this->width, $this->height)) {
                    return false;
                }
            }
        } else { //po prostu rozciagamy na full
            if (!imagecopyresampled($dst, $this->body,
                    0, 0, 0, 0,
                    $dst_data['width'], $dst_data['height'], $this->width, $this->height)) {
                return false;
            }
        }

        imagedestroy($this->body);
        $this->body = &$dst;

        $this->width  = imagesx($this->body);
        $this->height = imagesy($this->body);
        return true;
    }

    public function scale_prop($dst_width=null, $dst_height=null,
                               $bgcolor='ffffff', $position='center')
    {
        return $this->scale($dst_width, $dst_height, true, $bgcolor, $position);
    }

    public function scale_nonprop($dst_width=null, $dst_height=null,
                               $bgcolor='ffffff', $position='center')
    {
        return $this->scale($dst_width, $dst_height, false, $bgcolor, $position);
    }

    public function crop($t, $r, $b, $l)
    {
        $w = $r - $l;
        $h = $b - $t;
        
        $dst_data = array();
        list($dst_data['width'], $dst_data['height'], $dst) = $this->image($w, $h);
        imagecopy($dst, $this->body, 0, 0, $l, $t, $w, $h);

        imagedestroy($this->body);
        $this->body = &$dst;

        $this->width  = imagesx($this->body);
        $this->height = imagesy($this->body);

        return true;
    }
    public function show($type='png', $send_headers=true)
    {
        $this->is_initialized();

        if (!array_key_exists($type, $this->formats)) {
            throw new Exception(sprintf('Incorrect type: "%s".', $type));
        }
        if ('jpg' == $type) {
            $type = 'jpeg';
        }

        if ($send_headers) {
            header('Content-type: image/' . $type);
        }
        $this->formats[$type]($this->body);
    }

    public function saveToFile($fname, $overwrite = false)
    {
        $this->is_initialized();

        $ext = pathinfo($fname, PATHINFO_EXTENSION);

        $dst_dir = dirname($fname);
        if ($dst_dir == '') {
            $dst_dir = '.';
        }
        $dst_dir = realpath($dst_dir);
        $dst_dir .= substr($dst_dir, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
        $fname = $dst_dir . $fname;

        if (!is_writeable($dst_dir)) {
            throw new Exception(sprintf('Cannot write to "%s" directory.', $dst_dir));
        }
        if (file_exists($fname)) {
            if ($overwrite) {
                unlink($fname);
            } else {
                throw new Exception(sprintf('File "%s" already exists.', $fname));
            }
        }

        ob_start();
        $this->show($ext, false);
        $image = ob_get_contents();
        ob_clean();

        file_put_contents($fname, $image);
        return true;
    }

    public function filter($filter) //, $arg1=null, $arg2=null, $arg3=null)
    {
        $this->is_initialized();

        if (!in_array($filter, $this->filters)) {
            throw new Exception(sprintf('Invalid filter name: "%s".', $filter));
        }

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
        return true;
    }

    public function border($thick, $color='000000')
    {
        imagesetthickness($this->body, $thick);

        if (is_array($color)) {
            if (count($color) != 4) {
                return false;
            }

            $col1 = $this->color($color[0]);
            $col2 = $this->color($color[1]);
            $col3 = $this->color($color[2]);
            $col4 = $this->color($color[3]);
        } else {
            $col1 = $col2 = $col3 = $col4 = $this->color($color);
        }
        $coords = $thick/2;

        imageline($this->body, 0, $coords, $this->width, $coords, $col1);
        imageline($this->body, $this->width-$coords, 0,
                  $this->width-$coords, $this->height, $col2);
        imageline($this->body, $this->width, $this->height-$coords,
                  0, $this->height-$coords, $col3);
        imageline($this->body, $coords, $this->height, $coords, 0, $col4);

        return true;
    }

    private function is_initialized($exc = true)
    {
        if (is_null($this->body)) {
            if ($exc) {
                throw new Exception('Image not initialized - call Image::init() first.');
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

    private function color($hex)
    {
        list($r, $g, $b) = $this->hex2rgb($hex);

        return imagecolorallocate($this->body, $r, $g, $b);
    }

    private function image($dst_width=null, $dst_height=null)
    {
        $dst = array();
        //docelowa szerokosc
        if (!is_null($dst_width)) {
            $dst[] = $dst_width;
        } elseif ($this->dst_width > 0) {
            $dst[] = $this->dst_width;
        } else {
            $dst[] = $this->width;
        }
        //docelowa wysokosc
        if (!is_null($dst_height)) {
            $dst[] = $dst_height;
        } elseif ($this->dst_height > 0) {
            $dst[] = $this->dst_height;
        } else {
            $dst[] = $this->height;
        }

        if ($this->truecolor) {
            $dst[] = imagecreatetruecolor($dst[0], $dst[1]);
        } else {
            $dst[] = imagecreate($dst[0], $dst_data[1]);
        }

        return $dst;
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

function debug($v, $e=true)
{
    if (!defined('DEBUG') || !DEBUG) {return;}
    echo '<pre>';
    print_r($v);
    echo '</pre>';
    if ($e) exit;
}

//define('DEBUG', true);

try {
    $i = new Image('03.jpg');
    //$i->show();

    //$i->border(6, array('aabbcc', '112233', '445566', '778899'));
    //$i->scale(100, 100, true, '334455');
    $i->crop(0, 500, 400, 100);
    $i->show();
    //$i->saveToFile('asd.jpg', 1);
} catch(Exception $e) {
    echo $e->getMessage();
}

?>
