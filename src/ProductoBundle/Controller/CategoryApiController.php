<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ProductoBundle\Entity\Category;

 Class CategoryApiController extends Controller
 {
 	/**
     * @Route("/category/api/category/list", name="category_api_category_list")
     */

	 public function listCategories()
	 {

	    $categorias = $this->getDoctrine()
		->getRepository('ProductoBundle:Category')
		->findAll();

	   	$response=new Response();
	    $response->headers->add(['Content-Type'=>'application/json']);
	    $response->setContent(json_encode($categorias));
	    return $response;
	}


    /**
    * Creates a new categoria entity.
    *
    * @Route("/category/api/new", name="categoria_new_api")
    * @Method({"GET", "POST"})
    */

	public function newAction(Request $request)
    {
        $errors=[];
        $categorias = new Category();
        $form = $this->createForm('ProductoBundle\Form\CategoryApiType', $categorias);
        $form->handleRequest($request);
        $response=new Response();
	    $response->headers->add(['Content-Type'=>'application/json']);
	   
	 
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorias);
            $em->flush();

        }
        else
        {
            foreach ($form->getErrors() as $error)
            {
               $errors[]=$error->getMessage();
            }
            $response->setStatusCode(400);
            $response->setContent(json_encode($categorias));
        
        }

	    return $response;
    }
}