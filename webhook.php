<?php
/*
PB Project Demo - LINEBOT
:license MPL 2.0
(c) 2020 SuperSonic(https://github.com/supersonictw)
*/

include_once "config.php";
require_once "api/api.php";
require_once "functions.php";

$client = new LINEAPI($channelAccessToken, $channelSecret);
$msgobj = new LINEMSG();
foreach ($client->parseEvents() as $event) {
    switch ($event["type"]) {
        case "message":
            $message = $event["message"];
            switch ($message["type"]) {
                case "text":
                    $result = analytics($message["text"]);
                    if ($result) {
                        $client->replyMessage(
                            $event["replyToken"],
                            $msgobj->Text($result)
                        );
                    }
                    break;
            }
            break;
        case "join":
        case "follow":
            $client->replyMessage(
                $event["replyToken"],
                $msgobj->Text("I will scan all URLs for protecting your Internet Security.")
            );
    }
}
