<?php

class InputRepository
{
    private static $baseUrl = 'http://localhost:1010/api/finance';

    private static function makeRequest($method, $url, $data = null)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data)
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

    public static function getAll()
    {
        $url = self::$baseUrl . '/input';
        return self::makeRequest('GET', $url);
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
