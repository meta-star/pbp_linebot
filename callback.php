<?php
/*
PB Project Demo - LINEBOT
:license MPL 2.0
(c) 2020 SuperSonic(https://github.com/supersonictw)
 */

require_once 'api/api.php';
require_once 'func.php';
include 'set.php';

$client = new LINEAPI($channelAccessToken, $channelSecret);
$msgobj = new LINEMSG();
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $client->replyMessage($event['replyToken'], $msgobj->Text($message['text']));
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
    }
}
;
