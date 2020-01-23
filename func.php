<?php
/*
PB Project Demo - LINEBOT
:license MPL 2.0
(c) 2020 SuperSonic(https://github.com/supersonictw)
 */

$analytics_host = "https://project.starinc.xyz/pbp/api";

function analytics_connect($data, $json_decode = 0)
{
    $data_string = json_encode($data);

    $client = curl_init();
    curl_setopt($client, CURLOPT_POST, true);
    curl_setopt($client, CURLOPT_URL, $analytics_host);
    curl_setopt($client, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($client, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $client,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
        )
    );
    $result = curl_exec($client);
    curl_close($client);

    switch ($json_decode) {
        case 0:
            return json_decode($result);

        case 1:
            return json_decode($result, true);

        default:
            return $result;
    }
}

function analytics($message_text)
{
    preg_match_all(
        '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#',
        $message_text,
        $match
    );
    $queue = json_encode($match);
    $result = analytics_connect();
    if (is_null($result)) {
        error_log("PBP_A Server HandShaking Error");
        return false;
    } else {
        if ($result["status"] === 200) {
            return false;
        } else {
            return "Warning";
        }
    }
}
