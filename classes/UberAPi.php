<?php

if (!defined('_PS_VERSION_'))
    exit;

class SlackAPI
{

    public static function curlCall($method, $parameters)
    {
        if (!$method) {
            die("method not defined");
        } else {
            $url = 'https://login.uber.com/oauth/v2/';
        }

        if ($query = http_build_query($parameters)) {
            $url .= '?' . $query;
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
//        curl_setopt($ch, CURLOPT_URL, 'https://login.uber.com/oauth/v2/token');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        $post = array(
//            'client_secret' => 'ToWbb78sRWFSl-BF6fMMCWmqf2nnmdAqJLCm7Gbm',
//            'client_id' => 'uHQQIPQbRpkqWUPoPsfgxR9Vh18snb4k',
//            'grant_type' => 'authorization_code',
//            'redirect_uri' => 'http://localhost/prestashop/prestashop/fr/',
//            'scope' => 'profile',
//            'code' => 'IA.VUNmGAAAAAAAEgASAAAABwAIAAwAAAAAAAAAEgAAAAAAAAGgAAAAFAAAAAAADgAQAAQAAAAIAAwAAAAOAAAAdAAAABwAAAAEAAAAEAAAAEf0rv9wq7rZoUQ_Dk7SvoFOAAAA-Y0uXZSMf4dXGn8gqlXoGow1I_rLS039OaDwnB8pOqcAYc6hdNUsfmEQ2qy5HrUq1MkXdKU5xap7TmnmQl81Q6xZyhHk0B-799HfIKdvAAAMAAAAdYmXI28IRkkBasCIJAAAAGIwZDg1ODAzLTM4YTAtNDJiMy04MDZlLTdhNGNmOGUxOTZlZQ'
//        );
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//
//        $result = curl_exec($ch);
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
        curl_close($ch);
        return json_decode($result);
    }
}
