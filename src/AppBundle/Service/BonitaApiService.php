<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BonitaApiService
{

    private $urlBonitaApi;
    private $container;
    private $caseId;
    private $token;
    private $processId;

    public function __construct(String $urlBonitaApi, ContainerInterface $container) {
        $this->urlBonitaApi = $urlBonitaApi;
        $this->container = $container;
    }

    public function getTask($processId){

    }

    public function setCaseVariable($caseVariable, $variableType, $variableValue){
        $client = $this->container->get('bonita.client');
        $response = $this->createClient()->request(
        'PUT',
        "API/bpm/caseVariable/$caseId/$caseVariable",
        [
          'headers' => [
            'X-Bonita-API-Token' => $token->getValue()
          ],
          'json' => [
            'type' => $variableType,
            'value' => $variableValue
          ]
        ]
      );
      $body = $response->getBody();
      return json_decode($body);
    }

    public function getCaseVariable($caseVariable){
        $client = $this->container->get('bonita.client');
        $token = $this->login();
        $response = $this->createClient()->request(
        'GET',
        "API/bpm/caseVariable/$caseId/$caseVariable",
        [
          'headers' => [
            'X-Bonita-API-Token' => $token->getValue()
          ]
        ]
      );
      $body = $response->getBody();
      return json_decode($body);
    }

    public function getCaseId($processId, $variables ) {
        $client = $this->container->get('bonita.client');
        $processId = $this->getProcessId();
        // $variables = array(['name' => $nombreVariable, 'value' => $valorVariable]);
        $json = json_encode(['processDefinitionId' => $processId, 'variables' => $variables]);
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
        $caseId = json_decode($body)->{'id'};
        return $caseId;
    }

    public function getProcessId() {
        $token = $this->login();
        $client = $this->container->get('bonita.client');
        $response = $client->request('GET', 'bonita/API/bpm/process?c=10&p=0',[
                'headers' =>[
                    'X-Bonita-API-Token' => $token->getValue()
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
