<?php

namespace AppBundle\Service;

class RrhhApiService
{

    private $urlRrhhApi;

    public function __construct(String $urlRrhhApi) {
        $this->urlRrhhApi = $urlRrhhApi;
    }

    private function curl($method, $params) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlRrhhApi.$method.'?'.http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ));

        return $curl;
    }

    public function getEmployeeByUsername($email) {
        $curl = $this->curl("employee/find", ["email" => $email]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return ['data' => json_decode($response, false), 'code' => $code];
    }

}
