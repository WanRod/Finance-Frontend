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

        if ($method === 'GET' && $data != null)
        {
            $url .= '?' . http_build_query($data);
        }

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
        
        try {
            $result = @file_get_contents($url, false, $context);
            if ($result === false) {
                $error = error_get_last();
                
                if (strpos($error['message'], 'Failed to open stream') !== false) {
                    throw new Exception('A API está offline.');
                } else {
                    throw new Exception('Erro ao fazer a requisição: ' . $error['message']);
                }
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }

        return json_decode($result, true);
    }

    public static function getAvailableYears()
    {
        $url = self::$baseUrl . "/dashboard/available-years";        
        return self::makeRequest('GET', $url);
    }  

    public static function getData($year, $month)
    {
        $url = self::$baseUrl . "/dashboard/data";
        $data = [
            'year' => $year,
            'month' => $month
        ];
        
        return self::makeRequest('GET', $url, $data);
    }    
}
