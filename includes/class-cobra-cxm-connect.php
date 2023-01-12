<?php

class cobra_cxm_connect
{
    public static function getToken() {
        $attr = [
            'method' => 'POST',
            'headers' => [
                'ApiKey' => get_option('cobra_cxm_webconnect_apikey'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate, br'
            ],
            'body' => '{"userName": "'. get_option('cobra_cxm_webconnect_username'). '","password": "' . get_option('cobra_cxm_webconnect_password') . '"}'
        ];

        try {
            $response = wp_remote_post( get_option('cobra_cxm_webconnect_url') . "/api/token", $attr );
        } catch (\Throwable $th) {
            return "api-error";
        }

        if (wp_remote_retrieve_response_code($response) == 200) {
            $data = json_decode($response['body']);
            return $data->token;
        }

        return "api-error";
    }








    public static function getEvents($token, $limit) {
        $attr = [
            'method' => 'GET',
            'headers' => [
                'ApiKey' => get_option('cobra_cxm_webconnect_apikey'),
                'Authorization' => "Bearer " . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate, br'
            ],
            'body' => ''
        ];

        try {
            $response = wp_remote_get( get_option('cobra_cxm_webconnect_url') . "/api/ET_Veranstaltungen?OrderBy=Datum%20Beginn&Top=". $limit, $attr );
        } catch (\Throwable $th) {
            return "api-error";
        }

        if (wp_remote_retrieve_response_code($response) == 200) {
            $data = json_decode($response['body']);
            return $data;
        }

        return "api-error";
    }
}
