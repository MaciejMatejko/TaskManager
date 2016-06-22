<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Task;

class UserController extends Controller
{
    
    /**
     * @Route("/")
     * @Template("")
     */
    public function mainAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        return $this->render("AppBundle:User:main.html.twig", ["user" => $user]) ;
    }
}