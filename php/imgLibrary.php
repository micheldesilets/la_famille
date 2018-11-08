<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-11-05
 * Time: 17:16
 */
// ----------------------- RESIZE FUNCTION -----------------------
// Function for resizing any jpg, gif, or png image files
function img_resize($target, $newcopy, $w, $h, $ext)
{
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
        $w = $h * $scale_ratio;
    } else {
        $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif") {
        $img = imagecreatefromgif($target);
    } else if ($ext == "png") {
        $img = imagecreatefrompng($target);
    } else {
        $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    if ($ext == "gif") {
        imagegif($tci, $newcopy);
    } else if ($ext == "png") {
        imagepng($tci, $newcopy);
    } else {
        imagejpeg($tci, $newcopy, 84);
    }
}

// ---------------- THUMBNAIL (CROP) FUNCTION ------------------
// Function for creating a true thumbnail cropping from any jpg, gif, or png image files
function img_thumb($target, $newcopy, $w, $h, $ext)
{
    list($width, $height) = getimagesize($target);
    $thumb_width = $w;
    $thumb_height = $h;
    $original_aspect = $width / $height;
    $thumb_aspect = $w / $h;
    if ($original_aspect >= $thumb_aspect) {
        // If image is wider than thumbnail (in aspect ratio sense)
        $new_height = $thumb_height;
        $new_width = $width / ($height / $thumb_height);
    } else {
        // If the thumbnail is wider than the image
        $new_width = $thumb_width;
        $new_height = $height / ($width / $thumb_width);
    }
    $img = imagecreatefromjpeg($target);
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

    imagecopyresampled($thumb,
        $img,
        0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
        0 - ($new_height - $thumb_height) / 2, // Center the image vertically
        0, 0,
        $new_width, $new_height,
        $width, $height);
    if ($ext == "gif") {
        imagegif($thumb, $newcopy);
    } else if ($ext == "png") {
        imagepng($thumb, $newcopy);
    } else {
        imagejpeg($thumb, $newcopy, 84);
    }
}

// ------------------ IMAGE CONVERT FUNCTION -------------------
// Function for converting GIFs and PNGs to JPG upon upload
function img_convert_to_jpg($target, $newcopy, $ext)
{
    list($w_orig, $h_orig) = getimagesize($target);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif") {
        $img = imagecreatefromgif($target);
    } else if ($ext == "png") {
        $img = imagecreatefrompng($target);
    }
    $tci = imagecreatetruecolor($w_orig, $h_orig);
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
}