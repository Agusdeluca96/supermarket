<?php

namespace AppBundle\Service;

class StockApiService
{

    private $urlStockApi;

    public function __construct(String $urlStockApi) {
        $this->urlStockApi = $urlStockApi;
    }

    private function curl($method, $params) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlStockApi.$method.'?'.http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ));

        return $curl;
    }

    public function getProducts($employee = "false") {
        $curl = $this->curl("product", []);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return ['data' => json_decode($response, false), 'code' => $code];
    }
    
    public function getProductsAvailables($employee = "false") {
        $curl = $this->curl("product/availables", []);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return ['data' => json_decode($response, false), 'code' => $code];
    }   

    public function getProduct($id) {
        $curl = $this->curl("product/$id", []);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return ['data' => json_decode($response, false), 'code' => $code];
    }
}
