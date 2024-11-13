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

        $ch = curl_init();

        if (isset($_SESSION['token']))
        {
            $headers = [
                'Authorization: Bearer ' . $_SESSION['token']
            ];
        }

        if ($method === 'GET' && $data !== null)
        {
            $url .= '?' . http_build_query($data);
        }
        else
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (($method === 'POST' || $method === 'PUT') && $data !== null)
        {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($jsonData);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false)
        {
            return null;
        }

        $decodedResponse = !empty($response) ? json_decode($response, true) : null;

        if ($httpCode >= 400)
        {
            if ($httpCode === 503)
            {
                return null;
            }

            if ($httpCode === 401)
            {
                return [
                    'error' => [
                        'message' => "O token de autorização expirou, realize o login novamente."
                    ]
                ];
            }

            if ($httpCode === 404)
            {
                return [
                    'error' => [
                        'message' => "O endpoint da ação desejada não foi encontrado."
                    ]
                ];
            }

            if (is_array($decodedResponse['message']))
            {
                return [
                    'error' => [
                        'message' => implode(' ', array_map(fn($err) => $err['error'], $decodedResponse['message']))
                    ]
                ];
            }
            else
            {
                return [
                    'error' => [
                        'message' => $decodedResponse['message']
                    ]
                ];
            }
        }

        return [
            'body' => $decodedResponse,
            'httpCode' => $httpCode
        ];
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
