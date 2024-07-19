<?php

class OutputRepository
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
