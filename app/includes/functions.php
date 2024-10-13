<?php

include 'resize_img_code.php';

function secure($conn, $val) {
    $clean = mysqli_escape_string($conn, htmlentities(addslashes($val)));
    return $clean;
}

function uniqueCode($db) {
    $code_init = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
    $code_init2 = substr(str_shuffle($code_init), 0, 8);
    $otp_code = substr($code_init2, 0, 4) . "-" . substr($code_init2, 4, 7);
    $check_code = mysqli_num_rows(mysqli_query($db, "select r_id from residence where r_id='$otp_code'"));
    if ($check_code > 0) {
        uniqueCode($db);
    } else {
        return $otp_code;
    }
}

//getting date intervals
function gatDates($date) {
    $db_date = date_create($date);
    $current_date = date_create(date("Y-m-d H:i:s"));
    $date_diff = date_diff($current_date, $db_date);
    $min = $date_diff->format("%i");
    $day = $date_diff->format("%d");
    $month = $date_diff->format("%m");
    $hour = $date_diff->format("%h");
    $sec = $date_diff->format("%s");
    $year = $date_diff->format("%Y");
    if ($year > 0) {
       // return $Year . " Year. " . $months . " months(s) ago ";
    } elseif ($month > 0) {
        return $month . " Month(s) " . $day . " day(s) ago ";
    } elseif ($month < 1 && $day > 0) {
        return $day . " day(s) " . $hour . " Hrs ago ";
    } elseif ($month < 1 && $day < 1 && $hour > 0) {
        return $hour . " Hr(s) " . $min . " mins ago ";
    } elseif ($month < 1 && $day < 1 && $hour < 1 && $min > 0) {
        return $min . " mins ago ";
    } else {
        return $sec . " secs ago ";
    }
}

//Upload audio media file 
function upload_audio($path1, $ext) {
    $aud_url = 'AUDIO' . '-' . date('mdYHis.') . $ext;
    $bool = move_uploaded_file($path1, "media/audio/" . $aud_url);
    if ($bool == TRUE) {
        return $aud_url;
    } else {
        return "";
    }
}

//Upload video media file 
function upload_video($paths, $ext) {
    $vid_url = 'VIDEO' . '-' . date('mdYHis.') . $ext;
    $bool = move_uploaded_file($paths, "media/video/" . $vid_url);
    if ($bool == TRUE) {
        return $vid_url;
    } else {
        return "";
    }
}

//Upload and Crop screenshot images 
function upload_screenshot($path, $ext, $sn) {
    $img_url = 'PHOTO' . $sn . '-' . date('mdYHis.') . $ext;
    move_uploaded_file($path, "temp_img/" . $img_url);

    $resizeObj = new resize("temp_img/" . $img_url);
    $resizeObj->resizeImage(350, 350, 'exact');
    $resizeObj->saveImage("media/testifiers/" . $img_url, 100);
    unlink("temp_img/" . $img_url);
    return $img_url;
}

//Upload and Crop member images 
function upload_bussiness_image($path, $ext) {
    $img_url = 'img_' . date('mdYHis.') . $ext;
    move_uploaded_file($path, "temp_img/" . $img_url);

    $resizeObj = new resize("temp_img/" . $img_url);
    $resizeObj->resizeImage(400, 400, 'exact');
    $resizeObj->saveImage("images/" . $img_url, 100);
    unlink("temp_img/" . $img_url);
    return $img_url;
}

//Upload and Crop blog images 
function upload_art_img($path, $ext, $sn) {
    $img_url = 'ART' . $sn . '-' . date('mdYHis.') . $ext;
    move_uploaded_file($path, "temp_img/" . $img_url);

    $resizeObj = new resize("temp_img/" . $img_url);
    $resizeObj->resizeImage(768, 512, 'exact');
    $resizeObj->saveImage("media/blog_images/" . $img_url, 100);
    unlink("temp_img/" . $img_url);
    return $img_url;
}

function upload_testimony_img($path, $ext, $sn) {
    $img_url = 'TES' . $sn . '-' . date('mdYHis.') . $ext;
    move_uploaded_file($path, "temp_img/" . $img_url);

    $resizeObj = new resize("temp_img/" . $img_url);
    $resizeObj->resizeImage(394, 452, 'exact');
    $resizeObj->saveImage("media/blog_images/" . $img_url, 100);
    unlink("temp_img/" . $img_url);
    return $img_url;
}

//Upload and Crop banner images 
function upload_gallery_image($path, $ext, $sn) {
    $img_url = 'PIC' . $sn . '-' . date('mdYHis.') . $ext;
    move_uploaded_file($path, "temp_img/" . $img_url);

    $resizeObj = new resize("temp_img/" . $img_url);
    $resizeObj->resizeImage(450, 475, 'exact');
    $resizeObj->saveImage("media/gallery/" . $img_url, 100);
    unlink("temp_img/" . $img_url);
    return $img_url;
}

function sendsms_post($url, array $params) {
    $params = http_build_query($params);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    $output = curl_exec($ch);

    curl_close($ch);
    return $output;
}

function validate_sendsms($response) {
    $validate = explode('||', $response);
    if ($validate[0] == '1000') {
        //return TRUE;
        //return custom response here instead.
        return 1;
    } else {
        return FALSE;
        //return custom response here instead.
    }
}
