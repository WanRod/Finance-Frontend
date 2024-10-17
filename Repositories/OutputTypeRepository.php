<?php

class OutputTypeRepository
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
            return ['error' => 'Token de autorização não encontrado, faça o login novamente.'];
        }
    
        $headers = [
            'Authorization: Bearer ' . $token
        ];
    
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
    
    public static function getAll()
    {
       $url = self::$baseUrl . '/output-type';
       return self::makeRequest('GET', $url);
    }

    public static function getById($id)
    {
        $url = self::$baseUrl . "/output-type/{$id}";
        return self::makeRequest('GET', $url);
    }

    public static function insert($description)
    {
        $url = self::$baseUrl . '/output-type';
        $data = [
            'description' => $description
        ];
        return self::makeRequest('POST', $url, $data);
    }

    public static function update($id, $description)
    {
        $url = self::$baseUrl . "/output-type";
        $data = [
            'id' => $id,
            'description' => $description
        ];
        return self::makeRequest('PUT', $url, $data);
    }

    public static function delete($id)
    {
        $url = self::$baseUrl . "/output-type/{$id}";
        return self::makeRequest('DELETE', $url);
    }
}
