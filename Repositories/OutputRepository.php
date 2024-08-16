<?php

class OutputRepository
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
    
        if ($method === 'POST' || $method === 'PUT')
        {
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
        $result = file_get_contents($url, false, $context);
    
        if ($result === FALSE)
        {
            $error = error_get_last();
            die('Erro ao fazer a requisição: ' . $error['message']);
        }
    
        return json_decode($result, true);
    }

    public static function getAll()
    {
        $url = self::$baseUrl . '/output';
        return self::makeRequest('GET', $url);
    }

    public static function getById($id)
    {
        $url = self::$baseUrl . "/output/{$id}";
        return self::makeRequest('GET', $url);
    }

    public static function insert($outputTypeId, $description, $value, $date)
    {
        $url = self::$baseUrl . '/output';
        $data = [
            'output_type_id' => $outputTypeId,
            'description' => $description,
            'value' => $value,
            'date' => $date
        ];
        return self::makeRequest('POST', $url, $data);
    }

    public static function update($id, $outputTypeId, $description, $value, $date)
    {
        $url = self::$baseUrl . "/output";
        $data = [
            'id' => $id,
            'output_type_id' => $outputTypeId,
            'description' => $description,
            'value' => $value,
            'date' => $date
        ];
        return self::makeRequest('PUT', $url, $data);
    }

    public static function delete($id)
    {
        $url = self::$baseUrl . "/output/{$id}";
        return self::makeRequest('DELETE', $url);
    }
}
