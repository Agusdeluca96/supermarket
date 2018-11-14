<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Service\StockApiService;
use AppBundle\Service\RrhhApiService;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, StockApiService $stockApi)
    {
        $products = $stockApi->getProducts()['data'];
        return $this->render('default/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/login.html.twig', []);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request, RrhhApiService $rrhhApi)
    {
        dump($rrhhApi->checkEmployeeCredentials('agusdeluca96@gmail.com', 'puntito'));die;
    }
}
