<?php

class UserRepository
{
    private static $baseUrl = 'http://localhost:1010/api/finance';

    private static function makeRequest($method, $url, $data = null)
    {
        if (!isset($_SESSION)) 
        {
            session_start();
        }
    
        $token = null;

        if ($method != 'POST')
        {
            $token = $_SESSION['token'];

            if (!$token)
            {
                die('Token de autorização não encontrado.');
            }
        }
    
        $headers = [];
    
        if ($token)
        {
            $headers[] = 'Authorization: Bearer ' . $token;
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
        $result = file_get_contents($url, false, $context);
    
        if ($result === FALSE)
        {
            $error = error_get_last();
            die('Erro ao fazer a requisição: ' . $error['message']);
        }
    
        return json_decode($result, true);
    }

    public static function getData()
    {
        $url = self::$baseUrl . "/user";
    
        return self::makeRequest('GET', $url);
    }

    public static function insert($name, $cpfCnpj, $password)
    {
        $url = self::$baseUrl . '/user';
        $data = [
            'name' => $name,
            'cpf_cnpj' => $cpfCnpj,
            'password' => $password
        ];
    
        return self::makeRequest('POST', $url, $data);
    }

    public static function updatePassword($password)
    {
        $url = self::$baseUrl . "/user";
        $data = [
            'password' => $password
        ];
    
        return self::makeRequest('PUT', $url, $data);
    }

    public static function delete($id)
    {
        $url = self::$baseUrl . "/user/{$id}";
    
        return self::makeRequest('DELETE', $url);
    }
}
