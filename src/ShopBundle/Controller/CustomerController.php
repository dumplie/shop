<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CustomerController extends Controller
{
    /**
     * @Route("/")
     */
    public function homepageAction()
    {
        return $this->render(':customer/homepage:index.html.twig');
    }
}
