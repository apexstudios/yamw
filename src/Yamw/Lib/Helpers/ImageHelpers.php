<?php
function gen($src_img, $old_x, $old_y, $size, $height)
{
    $factor = $size / $old_x;
    $aspect_ratio_old = $old_x /  $old_y;
    $aspect_ratio = $size / $height;

    $x1 = -($aspect_ratio_old*$size*$factor-$size)/2;
    $y1 = 0;
    
    $x2 = $old_x/$aspect_ratio_old*$aspect_ratio;
    $y2 = $old_y;
    
    // Creating the new image
    $dst_img=ImageCreateTrueColor($size, $height);
    imagecopyresampled(
        $dst_img,
        $src_img,
        0,
        0,
        $x1,
        $y1,
        $size,
        $height,
        $x2,
        $y2
    );

    return $dst_img;
}
