<?php

namespace TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use TodoBundle\Entity\Todo;
class TodoController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $todos = $em->getRepository('TodoBundle:Todo')->findAll();
        return $this->render('TodoBundle:Todos:index.html.twig',array(
            'todos' => $todos
        ));
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $todo = new Todo();
        $form = $this->createFormBuilder($todo)
                ->add('name',TextType::class,array('attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px')))
                ->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px')))
                ->add('priority',ChoiceType::class,array('choices'=>array('Low'=>'Low','Medium'=>'Medium','High'=>'High'),'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px')))
                ->add('due_date',DatetimeType::class,array('attr'=>array('style'=>'margin-bottom:15px')))
                ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $todo->setName($form['name']->getData());
            $todo->setDescription($form['description']->getData());
            $todo->setPriority($form['priority']->getData());
            $todo->setDueDate($form['due_date']->getData());
            $todo->setCreatedDate(new \DateTime('now'));
            $todo->setEnabled(1);
            $em->persist($todo);
            $em->flush();

            return $this->redirect($this->generateUrl('todo_homepage'));

        }
               
        return $this->render('TodoBundle:Todos:create.html.twig',array(
            'form' => $form->createView()
        ));
    }

    public function updateAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $todo = $em->getRepository('TodoBundle:Todo')->find($id);
        $form = $this->createFormBuilder($todo)
                ->add('name',TextType::class,array('attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px')))
                ->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px')))
                ->add('priority',ChoiceType::class,array('choices'=>array('Low'=>'Low','Medium'=>'Medium','High'=>'High'),'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px')))
                ->add('due_date',DatetimeType::class,array('attr'=>array('style'=>'margin-bottom:15px')))
                ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $todo->setName($form['name']->getData());
            $todo->setDescription($form['description']->getData());
            $todo->setPriority($form['priority']->getData());
            $todo->setDueDate($form['due_date']->getData());
            $todo->setCreatedDate(new \DateTime('now'));

            $em->persist($todo);
            $em->flush();

            return $this->redirect($this->generateUrl('todo_homepage'));

        }
               
        return $this->render('TodoBundle:Todos:update.html.twig',array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $todo = $em->getRepository('TodoBundle:Todo')->find($id);
        
        $em->remove($todo);
        $em->flush();

        return $this->redirect($this->generateUrl('todo_homepage'));
    }

    public function disableAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $todo = $em->getRepository('TodoBundle:Todo')->find($id);

        $todo->setEnabled(0);
        $em->persist($todo);
        $em->flush();

        return $this->redirect($this->generateUrl('todo_homepage'));

    }
}
