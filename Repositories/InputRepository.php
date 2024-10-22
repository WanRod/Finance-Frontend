<?php

class InputRepository
{
    private static $baseUrl = 'http://localhost:1010/api/finance';

    private static function makeRequest($method, $url, $data = null)
    {
        if (!isset($_SESSION)) 
        {
            session_start();
        }
    
        $token = $_SESSION['token'] ?? null;
    
        if (!$token)
        {
            die('Token de autorização não encontrado.');
        }
    
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        if ($method === 'GET' && $data != null)
        {
            $url .= '?' . http_build_query($data);
        }
    
        if ($method === 'POST' || $method === 'PUT') {
            $headers[] = 'Content-Type: application/json';
        }
    
        $options = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
            ]
        ];
    
        if ($data !== null)
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

    public static function getAll($currentQuantity)
    {
        $url = self::$baseUrl . '/input';
        $data = [
            'quantity' => $currentQuantity
        ];
        return self::makeRequest('GET', $url, $data);
    }

    public static function getById($id)
    {
        $url = self::$baseUrl . "/input/{$id}";
        return self::makeRequest('GET', $url);
    }

    public static function insert($description, $value, $date)
    {
        $url = self::$baseUrl . '/input';
        $data = [
            'description' => $description,
            'value' => $value,
            'date' => $date
        ];
        return self::makeRequest('POST', $url, $data);
    }

    public static function update($id, $description, $value, $date)
    {
        $url = self::$baseUrl . "/input";
        $data = [
            'id' => $id,
            'description' => $description,
            'value' => $value,
            'date' => $date
        ];
        return self::makeRequest('PUT', $url, $data);
    }

    public static function delete($id)
    {
        $url = self::$baseUrl . "/input/{$id}";
        return self::makeRequest('DELETE', $url);
    }
}
