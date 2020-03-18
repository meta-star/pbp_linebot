<?php
/*
PB Project Demo - LINEBOT
:license MPL 2.0
(c) 2020 SuperSonic(https://github.com/supersonictw)
*/

function error_report($data)
{
    $content = "PB Project Demo - LINEBOT\nError:\n%s";
    error_log($data);
    return sprintf($content, $data);
}

function analytics_connect($data, $json_decode = 0)
{
    $data_string = json_encode($data);
    $analytics_host = "https://client.starinc.xyz/pbp";

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
        "(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))", 
        strtolower($message_text),
        $match
    );
    if (count($match) < 2 or empty($match[0])) {
        return false;
    }
    foreach ($match[0] as $url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $result = analytics_connect([
                "version" => 1,
                "url" => $url,
            ], 1);
            if (is_null($result)) {
                $msg = "PBP_A Server HandShaking Error";
                return error_report($msg);
            } else {
                if ($result["status"] === 200) {
                    if (array_key_exists("trust-score", $result) and $result["trust-score"] < 0.5) {
                        return "Warning";
                    }
                } else {
                    $msg = sprintf("PBP_A Server\nStatus: %s", $result["status"]);
                    return error_report($msg);
                }
            }
        }
    }
    return "Safe";
}
