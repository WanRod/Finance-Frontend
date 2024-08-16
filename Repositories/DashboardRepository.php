<?php

class DashboardRepository
{
    private static $baseUrl = 'http://localhost:1010/api/finance';

    private static function makeRequest($method, $url, $data = null)
    {
        if (!isset($_SESSION)) 
        {
            session_start();
        }

        $token = $_SESSION['token'] ?? null;

        //Ver se remove e retorna só a validação
        if (!$token)
        {
            die('Token de autorização não encontrado.');
        }

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ];

        $options = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
            ]
        ];

        if ($data != null)
        {
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        //Ver se remove e retorna só a validação
        if ($result === FALSE)
        {
            $error = error_get_last();
            die('Erro ao fazer a requisição: ' . $error['message']);
        }

        return json_decode($result, true);
    }

    public static function getData($year, $month)
    {
        $url = self::$baseUrl . "/dashboard/data/{$year}/{$month}";
        return self::makeRequest('GET', $url);
    }    
}
