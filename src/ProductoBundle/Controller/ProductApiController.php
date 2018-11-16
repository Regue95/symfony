<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ProductoBundle\Entity\Producto;

 Class ProductApiController extends Controller
 {
 	/**
     * @Route("/product/api/product/list", name="product_api_product_list")
     */

	 public function listProducts()
	 {

	    $productos = $this->getDoctrine()
		->getRepository('ProductoBundle:Producto')
		->findAll();

	   	$response=new Response();
	    $response->headers->add(['Content-Type'=>'application/json']);
	    $response->setContent(json_encode($productos));
	    return $response;
	}


    /**
    * Creates a new producto entity.
    *
    * @Route("/product/api/new", name="producto_new_api")
    * @Method({"GET", "POST"})
    */

	public function newAction(Request $request)
    {
        $productos = new Producto();
        $form = $this->createForm('ProductoBundle\Form\ProductoApiType', $productos);
        $form->handleRequest($request);
        $response=new Response();
	    $response->headers->add(['Content-Type'=>'application/json']);
	   
	 
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productos);
            $em->flush();

        }

 		$response->setContent(json_encode($productos));
	    return $response;
    }
}
