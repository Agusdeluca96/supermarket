<?php

namespace AppBundle\Controller;

use AppBundle\Service\BonitaApiService;
use AppBundle\Service\StockApiService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, StockApiService $stockApi)
    {
        if ($this->getUser() != null) {
            $employee = "true";
        } else {
            $employee = "false";
        }
        $products = $stockApi->getProducts($employee)['data'];
        return $this->render('default/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/login", name="login", options={"expose"=true})
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        // replace this example code with whatever you need
        return $this->render('default/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
    }

    /**
     * @Route("/buy/{product}/{coupon}", name="buy", options={"expose"=true})
     */
    public function buyAction(Request $request, $product, $coupon, BonitaApiService $bonitaApi, StockApiService $stockApi)
    {
        // Recupero el token de la API de bonita y lo asigno a una variable de la sesion
        $bonitaToken = $bonitaApi->login();
        $request->getSession()->set('bonitaToken', $bonitaToken);

        // Verifico si hay un empleado logueado queriendon hacer la compra
        if ($this->getUser() != null) {
            $employee = $this->getUser()->getId();
        } else {
            $employee = 0;
        }


        // Seteo las variables para el caso de bonita con los parametros enviados
        $variables = [
            [
                'name' => 'productoId',
                'value' => $product,
            ],
            [
                'name' => 'empleadoId',
                'value' => $employee,
            ],
            [
                'name' => 'cupon', 
                'value' => $coupon,
            ],
        ];

        // Me guardo el id del proceso en una variable de la sesion
        $processId = $bonitaApi->getProcessId();
        $request->getSession()->set('processId', $processId);
        
        // Inicio el caso y me guardo el id del caso en una variable de la sesion
        $caseId = $bonitaApi->startCase($variables);
        $request->getSession()->set('caseId', $caseId);

        // Me guardo el id asignado al usuario que inicio el proceso en una variable de la sesion
        $assignedId = $bonitaApi->getAssignedId();
        $request->getSession()->set('assignedId', $assignedId);

        $finalPrice = $bonitaApi->getCaseVariable('precioTotal');
        while ($finalPrice == '0.0') {
            $finalPrice = $bonitaApi->getCaseVariable('precioTotal');
        }

        return $this->renderBuyConfirmation($request, $stockApi, $product, $finalPrice);
    }

    public function renderBuyConfirmation(Request $request, StockApiService $stockApi, $id, $finalPrice) 
    {
        $product = $stockApi->getProduct($id)['data'];
        // var_dump($product);die;
        return $this->render('default/buy_confirmation.html.twig', [
            'product' => $product,
            'finalPrice' => (int) $finalPrice
        ]);

    }

    /**
     * @Route("/buyConfirmation/{value}", name="buyConfirmation", options={"expose"=true})
     */
    public function buyConfirmationAction(Request $request, $value, BonitaApiService $bonitaApi, StockApiService $stockApi)
    {
        // Recupero el token de la API de bonita y lo asigno a una variable de la sesion
        $bonitaToken = $bonitaApi->login();
        $request->getSession()->set('bonitaToken', $bonitaToken);

        // Me guardo el id de la tarea manual que freno el proceso en una variable de la sesion
        $confirmationTaskId = $bonitaApi->getActualTaskId();
        $request->getSession()->set('confirmationTaskId', $confirmationTaskId);
        
        if ($value == 'accept') {
            $bonitaApi->setCaseVariable('confirmacionCompra', 'java.lang.Boolean', 'true');
            $bonitaApi->setTaskState('completed');
            $processState = $bonitaApi->getProcessState();
            var_dump($processState);die;
            while ($processState != 'RESOLVED') {
                $processState = $bonitaApi->getProcessState();
            }
        } else {
            var_dump("cancelo");die;
        }

        return $this->redirectToRoute('homepage');
    }
}
