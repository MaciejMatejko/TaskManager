<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/task")
 */
class TaskController extends Controller
{
    
    /**
     * @Route("/new")
     * @Template()
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createFormBuilder($task)
                ->add("title")
                ->add("description")
                ->add("team", "entity", array("class" => "AppBundle:Team", "choice_label" => "name"))
                ->add('priority', 'choice', ['choices'  => ["Normal" => "normal", "High" => "high", "Low" => "low"], 'choices_as_values' => true])
                ->add("deadline", "date")
                ->add("add", "submit")
                ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return $this->redirectToRoute("app_user_main");
        }
        
        return $this->render("AppBundle:Team:new.html.twig", ["form" => $form->createView()]);
    }
    
    /**
    * @Route("/{id}/edit")
    * @Template()
    * @Security("has_role('ROLE_MANAGER')")
    */
    public function editAction(Request $request, $id)
    {
        $task = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$task){
            throw $this->createNotFoundException("Team not found");
        }
        if($user->getTeam() == null || $task->getId() !== $user->getTeam()->getId()){
            $this->redirectToRoute("app_user_error403");
        }
        
        $form = $this->createFormBuilder($task)
                ->add("name")
                ->add("description")
                ->add('users', 'entity', array('class' => 'AppBundle:User', 'choice_label' => 'username', 'multiple' => true))
                ->add("edit", "submit")
                ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid()){
            $users=$task->getUsers();
            foreach($users as $user){
                $user->setTeam($task);
            }
            $em = $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute("app_user_main");
        }
        
        return $this->render("AppBundle:Team:edit.html.twig", ["task" => $task, "form" => $form->createView()]);
    }
    
    /**
    * @Route("/{id}/delete")
    * @Template()
    * @Security("has_role('ROLE_MANAGER')")
     */
    public function deleteAction(Request $request, $id)
    {
        $task = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$task){
            throw $this->createNotFoundException("Team not found");
        }
        if($user->getTeam() == null || $task->getId() !== $user->getTeam()->getId()){
            $this->redirectToRoute("app_user_error403");
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        
        return $this->redirectToRoute("app_user_main");
    }
    
    /**
    * @Route("/{id}/show")
    * @Template()
    */
    public function showAction($id)
    {
        $task = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        
        if(!$task){
            throw $this->createNotFoundException("Team not found");
        }
        
        return ["task" => $task];
    }
    
    /**
     * @Route("/{id}/{userId}/remove")
     */
    public function removeAction($id, $userId)
    {
        $task = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        
        if(!$task){
            throw $this->createNotFoundException("Team not found");
        }
        
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($userId);
        
        if(!$user){
            throw $this->createNotFoundException("User not found");
        }
        
        $em = $this->getDoctrine()->getManager();
        $task->removeUser($user);
        $user->setTeam(null);
        $em->flush();
        
        return $this->redirectToRoute("app_task_edit", ["id" => $id]);
    }
}
