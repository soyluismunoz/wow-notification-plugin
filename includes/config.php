<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require __DIR__ . "/twilio/sdk/src/Twilio/autoload.php"; // Sdk twilio

$sid = get_option ('woo_wa_sid');
$authToken = get_option ('woo_wa_auth_token');
define('TWILIO_SID', $sid);
define('TWILIO_TOKEN', $authToken);

use Twilio\Rest\Client;

function SendMessageWsOwner($to, $from, $msg)
{
    $twilio = new Client(TWILIO_SID, TWILIO_TOKEN);
    
    $message = $twilio->messages
                     ->create("whatsapp:". $to,
                         array(
                                  "body" => $msg,
                                  "from" => "whatsapp:" . $from 
                              )
                     );
    print($message->sid);
}


function SendMessageWsCustomer($to, $from, $msg)
{
    $twilio = new Client(TWILIO_SID, TWILIO_TOKEN);
    
    $message = $twilio->messages
                     ->create("whatsapp:". $to,
                         array(
                                  "body" => $msg,
                                  "from" => "whatsapp:" . $from 
                              )
                     );
    print($message->sid);
}