<?php

class LoginRepository
{
    private static $baseUrl = 'http://localhost:1010/api/login';

    private static function makeRequest($method, $url, $data = null)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            curl_close($ch);
            return null;
        }

        $decodedResponse = json_decode($response, true);
        curl_close($ch);

        return [
            'body' => $decodedResponse,
            'httpCode' => $httpCode
        ];
    }

    public static function login($cpfCnpj, $password)
    {
        $data = [
            'cpf_cnpj' => $cpfCnpj,
            'password' => $password
        ];

        $response = self::makeRequest('POST', self::$baseUrl, $data);

        if ($response !== null && $response['httpCode'] >= 200 && $response['httpCode'] <= 299 && isset($response['body']['token'])) {
            return $response['body'];
        }

        if ($response !== null && $response['httpCode'] >= 400 && isset($response['body']['message'])) {
            return [
                'error' => [
                    'message' => $response['body']['message']
                ]
            ];
        }

        return null;
    }
}
