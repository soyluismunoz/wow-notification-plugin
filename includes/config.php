<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$token = get_option ('woo_wa_wb_token');
$from = get_option ('woo_wa_wb_from');
define('WB_TOKEN', $token);
define('WB_FROM', $from);

function SendMessageCurl($to, $msg)
{
    $to = filter_var($to, FILTER_SANITIZE_NUMBER_INT);

    if(empty($to)) return false;

    $msg = urlencode($msg);

    $custom_uid = "unique-" . time();

    $url = "https://www.waboxapp.com/api/send/chat?token=" . WB_TOKEN . "&uid=" . WB_FROM . "&to=" . $to . "&custom_uid=" . $custom_uid . "&text=" . $msg;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);

    if($result) return json_decode($result);

    return false;
}

function SendImageCurl($to, $url_image, $caption = '', $description = '')
{
    $to = filter_var($to, FILTER_SANITIZE_NUMBER_INT);

    if(empty($to)) return false;

    $url_image = urlencode($url_image);
    $caption = urlencode($caption);
    $description = urlencode($description);

    $custom_uid = "unique-" . time();

    $url = "https://www.waboxapp.com/api/send/image?token=" . WB_TOKEN . "&uid=" . WB_FROM . "&to=" . $to . "&custom_uid=" . $custom_uid . "&url=" . $url_image . "&caption=" . $caption . "&description=" . $description;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);

    if($result) return json_decode($result);

    return false;
}