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
        ));

        return $curl;
    }

    public function getProcessId() {
        $token = $this->login();
        // $curl = $this->curl("process", ["c" => 10, "p" => 0]);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, [
        //     "Content-Type: application/x-www-form-urlencoded",
        //     "X-Bonita-API-Token: ".$token
        // ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_PORT => "8080",
        CURLOPT_URL => "http://localhost:8080/bonita/API/bpm/process?c=10&p=0",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            "Postman-Token: 54eb9769-c051-46a0-851a-582362a0c10a",
            "X-Bonita-API-Token: 790b3b36-6d22-40fb-8d0e-dd2b2c761af6",
            "cache-control: no-cache"
        ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        var_dump($code);die;
        return ['data' => json_decode($response, false), 'code' => $code];

            // $body = $response->getBody();
            // $procesos = json_decode($body);
            // return $procesos[0]->{'id'};


    }

    public function login() {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_PORT => "8080",
            CURLOPT_URL => "http://localhost:8080/bonita/loginservice",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=walter.bates&password=bpm&redirect=false&undefined=",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,        
        ]);

        $result = curl_exec($curl);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = [];
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        return $cookies['X-Bonita-API-Token'];
    }

}
