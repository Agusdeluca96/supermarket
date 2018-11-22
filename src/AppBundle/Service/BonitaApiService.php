<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BonitaApiService
{

    private $urlBonitaApi;
    private $container;

    public function __construct(String $urlBonitaApi, ContainerInterface $container) {
        $this->urlBonitaApi = $urlBonitaApi;
        $this->container = $container;
    }

    public function getCaseId($processId, $variables ) {
        $client = $this->container->get('bonita.client');
        $token = $this->login();
        // $variables = array(['name' => $nombreVariable, 'value' => $valorVariable]);
        $json = json_encode(['processDefinitionId' => $idProceso, 'variables' => $variables]);
        try {
            $response = $this->createClient()->request(
                'POST',
                'API/bpm/case/',
                [
                    'headers' => [
                        'X-Bonita-API-Token' => $token->getValue()
                    ],
                    'body' => $json
                ]
            );
            $body = $response->getBody();
            return json_decode($body)->{'id'};
    }

    public function getProcessId() {
        $token = $this->login();

        $client = $this->container->get('bonita.client');
        //dump($client);die;

        //$response = $client->get('bonita/API/bpm/process?c=10&p=0');
        $response = $client->request('GET', 'bonita/API/bpm/process?c=10&p=0',[
                'headers' =>[
                    'X-Bonita-API-Token' => $token->getValue() //se debe pasar la api de bonita en el header para que tenga efecto el request
                ]
            ]
        );
        $body = $response->getBody();
        $procesos = json_decode($body);
        return $procesos[0]->{'id'};
    }

    public function login() {
        $client = $this->container->get('bonita.client');

        $response = $client->request('POST', 'bonita/loginservice',[
            'form_params' => [
                'username' => "walter.bates",
                'password' => "bpm",
                'redirect' => 'false'
            ]
        ]);

        $cookies = $client->getConfig('cookies') ;
        return $cookies->getCookieByName('X-Bonita-API-Token');
    }

}
