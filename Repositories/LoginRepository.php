<?php

class LoginRepository
{
    private static $baseUrl = 'http://localhost:1010/api/login';

    private static function makeRequest($method, $url, $data = null)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data != null)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public static function login($cpfCnpj, $password)
    {
        $data = [
            'cpf_cnpj' => $cpfCnpj,
            'password' => $password
        ];
        
        $response = self::makeRequest('POST', self::$baseUrl, $data);

        if (isset($response['token']))
        {
            session_start();
            $_SESSION['token'] = $response['token'];
        }

        return $response;
    }
}
