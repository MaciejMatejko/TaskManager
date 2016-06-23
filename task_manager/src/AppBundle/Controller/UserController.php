<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Task;
use Symfony\Component\Form\FormView;

class UserController extends Controller
{
    
    /**
     * @Route("/")
     * @Template()
     */
    public function mainAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        return $this->render("AppBundle:User:main.html.twig", ["user" => $user]) ;
    }
    
    /**
     * @Route("/error403")
     * @Template()
     */
    public function error403Action()
    {
        return $this->render("AppBundle:User:error403.html.twig");
    }
    
    /**
     * @Route("/admin")
     * @Template()
     */
    public function adminAction(Request $request)
    {
        $data = [];
        $form = $this->createFormBuilder($data)->
                add('users', 'entity', ['class' => 'AppBundle:User', 'choice_label' => 'username'])->
                add('role', 'choice', [
                'choices'  => ['admin' => "ROLE_ADMIN", 'manager' => "ROLE_MANAGER", 'user' => "ROLE_USER",],
                'choices_as_values' => true,])->add('add', 'submit')->getForm();
        $form->handleRequest($request);

        
        if($form->isValid()){
            $formData=$form->getData();
            $user=$formData["users"];
            $role=$formData["role"];
            
            $user->setRoles([$role]);
            $this->getDoctrine()->getManager()->flush();
                    
            return $this->redirectToRoute("app_user_admin");
        }
        
        return ["form" => $form->createView()];
    }
    
}