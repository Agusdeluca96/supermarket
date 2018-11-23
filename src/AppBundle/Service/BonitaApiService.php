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

    public function login() {
        $client = $this->container->get('bonita.client');

        $response = $client->request(
            'POST', 
            'bonita/loginservice',
            [
                'form_params' => 
                [
                    'username' => "walter.bates",
                    'password' => "bpm",
                    'redirect' => 'false'
                ]
            ]
        );

        $cookies = $client->getConfig('cookies') ;
        return $cookies->getCookieByName('X-Bonita-API-Token')->getValue();
    }

    public function getProcessId() {
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $response = $client->request(
            'GET', 
            'bonita/API/bpm/process?c=10&p=0',
            [
                'headers' =>
                [
                    'X-Bonita-API-Token' => $token
                ]
            ]
        );
        $body = $response->getBody();
        $processes = json_decode($body);
        return $processes[0]->{'id'};
    }

    public function getProcessState() {
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $response = $client->request(
            'GET', 
            'bonita/API/bpm/process?c=10&p=0',
            [
                'headers' =>
                [
                    'X-Bonita-API-Token' => $token
                ]
            ]
        );
        $body = $response->getBody();
        $processes = json_decode($body);
        return $processes[0]->{'configurationState'};
    }

    public function startCase($variables) {
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $processId = $session->get('processId');
        $json = json_encode(['processDefinitionId' => $processId, 'variables' => $variables]);
        $response = $client->request(
            'POST',
            'bonita/API/bpm/case/',
            [
                'headers' => [
                    'X-Bonita-API-Token' => $token
                ],
                'body' => $json
            ]
        );
        $body = $response->getBody();
        return json_decode($body)->{'id'};
    }

    public function getAssignedId() {
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $caseId = $session->get('caseId');
        $response = $client->request(
            'GET', 
            "bonita/API/bpm/case/$caseId",
            [
                'headers' =>
                [
                    'X-Bonita-API-Token' => $token
                ]
            ]
        );
        $body = $response->getBody();
        return json_decode($body)->{'started_by'};
    }

    public function setCaseVariable($caseVariable, $variableType, $variableValue){
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $caseId = $session->get('caseId');
        $token = $session->get('bonitaToken');
        $session->set('bonitaToken', $token);
        $response = $client->request(
            'PUT',
            "bonita/API/bpm/caseVariable/$caseId/$caseVariable",
            [
                'headers' => 
                [
                    'X-Bonita-API-Token' => $token
                ],
                'json' => 
                [
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
        $session = $this->container->get('session');
        $caseId = $session->get('caseId');
        $token = $session->get('bonitaToken');
        $response = $client->request(
        'GET',
        "bonita/API/bpm/caseVariable/$caseId/$caseVariable",
        [
            'headers' => 
            [
                'X-Bonita-API-Token' => $token
            ]
        ]
      );
      $body = $response->getBody();
      return json_decode($body)->{'value'};
    }

    public function setTaskState($state){
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $processId = $session->get('processId');
        $taskId = $session->get('confirmationTaskId');
        $assignedId = $session->get('assignedId');
        $response = $client->request(
            'PUT', 
            "bonita/API/bpm/userTask/$taskId",
            [
                'headers' =>
                [
                    'X-Bonita-API-Token' => $token
                ],
                'json' => 
                [
                    'processDefinitionId' => $processId,
                    'assigned_id' => $assignedId,
                    'state' => $state
                ]
            ]
        );
    
        $body = $response->getBody();
        return json_decode($body);
    }

    public function getActualTaskId(){
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $processId = $session->get('processId');
        $response = $client->request(
            'GET', 
            "bonita/API/bpm/task?c=10&p=0&f=processId=$processId&o=state",
            [
                'headers' =>
                [
                    'X-Bonita-API-Token' => $token
                ]
            ]
        );
        $body = $response->getBody();
        $tasks = json_decode($body);
        return $tasks[0]->{'id'};
    }

    public function getFinishedCaseState(){
        $client = $this->container->get('bonita.client');
        $session = $this->container->get('session');
        $token = $session->get('bonitaToken');
        $caseId = $session->get('caseId');
        do {
            $response = $client->request(
                'GET', 
                "bonita/API/bpm/archivedCase?o=id&f=sourceObjectId=$caseId",
                [
                    'headers' =>
                    [
                        'X-Bonita-API-Token' => $token
                    ]
                ]
            );
            $body = $response->getBody();
            $case = json_decode($body);
        } while (count($case) == 0);

        return $case[0]->{'state'};
    }
}
