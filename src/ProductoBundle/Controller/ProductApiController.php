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
    * @Method("POST")
    */

public function newAction(Request $r)
    {
        $product = new Producto();
        $form = $this->createForm(
            'ProductoBundle\Form\ProductoApiType',
            $product,
            [
                'csrf_protection' => false
            ]
        );
        $form->bind($r);
        $valid = $form->isValid();
        $response = new Response();
        if(false === $valid){
            $response->setStatusCode(400);
            $response->setContent(json_encode($this->getFormErrors($form)));
            return $response;
        }
        if (true === $valid) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $response->setContent(json_encode($product));
        }
        return $response;
    }

    
    public function getFormErrors($form){
        $errors = [];
        if (0 === $form->count()){
            return $errors;
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = (string) $form[$child->getName()]->getErrors();
            }
        }
        return $errors;
    }
}
