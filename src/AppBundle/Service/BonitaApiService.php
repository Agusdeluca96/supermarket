<?php

namespace AppBundle\Service;

class BonitaApiService
{

    private $urlBonitaApi;

    public function __construct(String $urlBonitaApi) {
        $this->urlBonitaApi = $urlBonitaApi;
    }

    private function curl($method, $params) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlBonitaApi.$method.'?'.http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'X-Bonita-API-Token: d4c025f7-f9aa-40ba-8c26-8da145fd9c59'
            ],
        ));

        return $curl;
    }

    public function getProcess() {
        $curl = $this->curl("process", ["c" => 10, "p" => 0]);
        var_dump(curl_getinfo($curl));die;
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return ['data' => json_decode($response, false), 'code' => $code];
    }

}
