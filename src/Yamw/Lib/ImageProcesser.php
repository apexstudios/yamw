<?php
namespace Yamw\Lib;

final class ImageProcesser
{
    private static $instance;
    
    
    final public static function getInstance()
    {
        if(!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        // Do nothing
    }

    public static  function createThumbnail($name, $width = 0, $savename = null, $use_width = true, $height = 0)
    {
        if (preg_match('/(jpg|jpeg)$/',$name)) {
            $src_img = @imagecreatefromjpeg($name);
            $img_type = 'jpg';
        } elseif (preg_match('/png$/',$name)) {
            $src_img = @imagecreatefrompng($name);
            $img_type = 'png';
        }

        if (!isset($src_img) || !$src_img) {
            return false;
        }

        if ($width) {
            $size = $width;
        } else {
            $size = ($use_width) ? TN_DEFAULT_WIDTH : TN_DEFAULT_HEIGHT;
        }


        $old_x = imageSX($src_img);
        $old_y = imageSY($src_img);

        if ($use_width) {
            $factor = $size / $old_x;
            $aspect_ratio = $old_x /  $old_y;
        } else {
            $factor = $size / $old_y;
            $aspect_ratio = $old_x /  $old_y;
        }

        // Calculating the new width and height
        $new_x = $old_x * $factor;
        $new_y = ($height) ? $height: $old_y * $factor;

        // Creating the new image
        $dst_img=ImageCreateTrueColor($new_x, $new_y);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_x,$new_y,$old_x,$old_y);

        if ($savename != NULL) {
            if($savename == 'Output') {
                $filename = NULL;
            } else {
                $filename = $savename;
            }
        } else {
            $filename = getThumbPath($name, $size);
        }

        #Not useable yet
        $dst_img = self::UnsharpMask($dst_img);

        if ($img_type = 'png') {
            imagepng($dst_img, $filename, 0, PNG_NO_FILTER);
        } else {
            imagejpeg($dst_img, $filename, 100);
        }

        imagedestroy($dst_img);
        imagedestroy($src_img);
        return $img_type;
    }
    
    public static function createThumbnail2(GalleryImage $obj, $thumb = false)
    {
        $filename = getThumbPath($obj->getPath(), $obj->getWidth($thumb));
        if (file_exists($filename)) {
            return;
        }
        
        if ($obj->getFileType() == 'jpg') {
            $src_img = @imagecreatefromjpeg($obj->getPath());
            $img_type = 'jpg';
        } elseif ($obj->getFileType() == 'png') {
            $src_img = @imagecreatefrompng($obj->getPath());
            $img_type = 'png';
        } else {
            trigger_error('WTF? Security breach detected! Please contact the
                special squadron YouTube sent out weeks ago immediatly!!!');
        }

        if (!isset($src_img) || !$src_img) {
            return false;
        }

        $old_x = $obj->getActualWidth();
        $old_y = $obj->getActualHeight();

        $factor = $obj->getWidth($thumb) / $old_x;
        $aspect_ratio = $old_x /  $old_y;

        // Calculating the new width and height
        $new_x = $obj->getWidth($thumb);
        $new_y = $obj->getHeight($thumb) ? $obj->getHeight($thumb) :$old_y * $factor;

        // Creating the new image
        $dst_img=ImageCreateTrueColor($new_x, $new_y);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_x,$new_y,$old_x,$old_y);

        $dst_img = self::UnsharpMask($dst_img);

        if ($img_type = 'png') {
            imagepng($dst_img, $filename, 0, PNG_NO_FILTER);
        } else {
            imagejpeg($dst_img, $filename, 100);
        }

        imagedestroy($dst_img);
        imagedestroy($src_img);
        return $img_type;
    }

    public static function UnsharpMask($img, $amount = 50, $radius = 0.4, $threshold = 3)
    {
        ////////////////////////////////////////////////////////////////////////
        ////
        ////                  Unsharp Mask for PHP - version 2.1.1
        ////
        ////    Unsharp mask algorithm by Torstein Hønsi 2003-07.
        ////             thoensi_at_netcom_dot_no.
        ////               Please leave this notice.
        ////
        ////////////////////////////////////////////////////////////////////////



        // $img is an image that is already created within php using
        // imgcreatetruecolor. No url! $img must be a truecolor image.

        // Attempt to calibrate the parameters to Photoshop:
        if ($amount > 500) {
            $amount = 500;
        }
        $amount = $amount * 0.016;
        if ($radius > 50) {
            $radius = 50;
        }
        $radius = $radius * 2;
        if ($threshold > 255) {
            $threshold = 255;
        }

        $radius = abs(round($radius));     // Only integers make sense.
        if ($radius == 0) {
            return $img;
            imagedestroy($img);
            break;
        }
        
        $w = imagesx($img);
        $h = imagesy($img);
        $imgCanvas = imagecreatetruecolor($w, $h);
        $imgBlur = imagecreatetruecolor($w, $h);


        // Gaussian blur matrix:
        //
        //    1    2    1
        //    2    4    2
        //    1    2    1
        //
        //////////////////////////////////////////////////


        if (function_exists('imageconvolution')) { // PHP >= 5.1
            $matrix = array(
                array( 1, 2, 1 ),
                array( 2, 4, 2 ),
                array( 1, 2, 1 )
            );
            imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h);
            imageconvolution($imgBlur, $matrix, 16, 0);
        }

        if ($threshold>0) {
            // Calculate the difference between the blurred pixels and the original
            // and set the pixels
            for ($x = 0; $x < $w-1; $x++) { // each row
                for ($y = 0; $y < $h; $y++) { // each pixel

                    $rgbOrig = ImageColorAt($img, $x, $y);
                    $rOrig = (($rgbOrig >> 16) & 0xFF);
                    $gOrig = (($rgbOrig >> 8) & 0xFF);
                    $bOrig = ($rgbOrig & 0xFF);

                    $rgbBlur = ImageColorAt($imgBlur, $x, $y);

                    $rBlur = (($rgbBlur >> 16) & 0xFF);
                    $gBlur = (($rgbBlur >> 8) & 0xFF);
                    $bBlur = ($rgbBlur & 0xFF);

                    // When the masked pixels differ less from the original
                    // than the threshold specifies, they are set to their original value.
                    $rNew = (abs($rOrig - $rBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
                    : $rOrig;
                    $gNew = (abs($gOrig - $gBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
                    : $gOrig;
                    $bNew = (abs($bOrig - $bBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
                    : $bOrig;



                    if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
                        $pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
                        ImageSetPixel($img, $x, $y, $pixCol);
                    }
                }
            }
        } else {
            for ($x = 0; $x < $w; $x++) { // each row
                for ($y = 0; $y < $h; $y++) { // each pixel
                    $rgbOrig = ImageColorAt($img, $x, $y);
                    $rOrig = (($rgbOrig >> 16) & 0xFF);
                    $gOrig = (($rgbOrig >> 8) & 0xFF);
                    $bOrig = ($rgbOrig & 0xFF);

                    $rgbBlur = ImageColorAt($imgBlur, $x, $y);

                    $rBlur = (($rgbBlur >> 16) & 0xFF);
                    $gBlur = (($rgbBlur >> 8) & 0xFF);
                    $bBlur = ($rgbBlur & 0xFF);

                    $rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;
                    if ($rNew>255) {
                        $rNew=255;
                    } elseif ($rNew<0) {
                        $rNew=0;
                    }
                    
                    $gNew = ($amount * ($gOrig - $gBlur)) + $gOrig;
                    if ($gNew>255) {
                        $gNew=255;
                    } elseif($gNew<0) {
                        $gNew=0;
                    }
                    
                    $bNew = ($amount * ($bOrig - $bBlur)) + $bOrig;
                    if ($bNew>255){
                        $bNew=255;
                    } elseif ($bNew<0) {
                        $bNew=0;
                    }
                    
                    $rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew;
                    ImageSetPixel($img, $x, $y, $rgbNew);
                }
            }
        }
        imagedestroy($imgCanvas);
        imagedestroy($imgBlur);

        return $img;
    }

}