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
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ]);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response !== false ? [
            'body' => json_decode($response, true),
            'httpCode' => $httpCode
        ] : null;
    }

    public static function login($cpfCnpj, $password)
    {
        $data = [
            'cpf_cnpj' => $cpfCnpj,
            'password' => $password
        ];

        $response = self::makeRequest('POST', self::$baseUrl, $data);

        if ($response != null && $response['httpCode'] >= 400 && isset($response['body']['message']))
        {
            if (is_array($response['body']['message']))
            {
                return [
                    'error' => [
                        'message' =>  implode(' ', array_map(fn($err) => $err['error'], $response['body']['message']))
                    ]
                ];
            }
            else
            {
                return [
                    'error' => [
                        'message' => $response['body']['message']
                    ]
                ];
            }
        }

        return $response;
    }
}
