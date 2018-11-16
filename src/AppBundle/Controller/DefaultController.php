<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use AppBundle\Service\StockApiService;
use AppBundle\Service\RrhhApiService;
use AppBundle\Security\WebserviceUser;

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
            'products' => $products
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
            'error'         => $error,
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
    public function buyAction($product, $coupon)
    {
        var_dump("Producto: " . $product . ", Coupon: " . $coupon);die;
    }
}
