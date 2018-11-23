<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BonitaApiService2
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

    private function logout() {
        $client = $this->container->get('bonita.client');
        return $client->request(
            'GET', 
            'bonita/logoutservice', 
            [
                'form_params' => 
                [
                    'redirect' => 'false'
                ]
            ]
        );
    }

    public function getProcessId() {
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
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
        finally {
            $this->logout();
        }
    }

    public function startCase($variables) {
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
            $session = $this->container->get('session');
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
        finally {
            $this->logout();
        }
    }

    public function getAssignedId() {
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
            $session = $this->container->get('session');
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
        finally {
            $this->logout();
        }
    }

    public function getCaseVariable($caseVariable){
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
            $session = $this->container->get('session');
            $caseId = $session->get('caseId');
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
        finally {
            $this->logout();
        }
    }

    public function setCaseVariable($caseVariable, $variableType, $variableValue){
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
            $session = $this->container->get('session');
            $caseId = $session->get('caseId');
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
        finally {
            $this->logout();
        }
    }

    public function getActualTaskId(){
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
            $session = $this->container->get('session');
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
        finally {
            $this->logout();
        }
    }

    public function setTaskState($state){
        $token = $this->login();
        try {
            $client = $this->container->get('bonita.client');
            $session = $this->container->get('session');
            $processId = $session->get('processId');
            $assignedId = $session->get('assignedId');
            $taskId = $session->get('confirmationTaskId');
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
        finally {
            $this->logout();
        }
    }
}