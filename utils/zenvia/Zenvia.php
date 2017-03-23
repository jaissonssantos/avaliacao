<?php

class Zenvia
{
    public static function send($number, $message)
    {
        $authorization = "";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api-rest.zenvia360.com.br/services/send-sms");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
          \"sendSmsRequest\": {
            \"to\": \"$number\",
            \"msg\": \"$message\"
          }
        }");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic " . $authorization,
            "Accept: application/json"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);
    }
}
