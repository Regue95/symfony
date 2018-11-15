<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
	/**
     * @Route("/products/view/{id}", name="productDetail")
     */
    public function viewAction($id)
    {

       $producto = $this->getDoctrine()
		    	->getRepository('ProductoBundle:Producto')
		    	->find($id);

        return $this->render('ProductoBundle:Default:view.html.twig',
                                [
                                'producto'=> $producto,
                                'cart_config' => $this->container->getParameter('cart_config')
                                ]
                            );
    }
    /**
     *@Route("/products/add" , name="product_add_cart", methods="POST")
     */
    public function addToCartAction(Request $r){
        $id=$r->get('id');
        $quantity=$r->get('quantity');
        $requestType=strtolower($r->headers->get('X-Requested-With'));
        $isAjax='xmlhttprequest'===$requestType;
        $producto= $this->getDoctrine()
        ->getRepository('ProductoBundle:Producto')
        ->find($id);
        if(null===$producto){
            throw new \Exception("Product not found");
        }

        $cartService = $this->get('app.cart');
        $cartService->add($producto);
        if(true===$isAjax)
        {
            $response=new Response();
            $response->headers->add(['Content-Type'=>'application/json']);
            $response->setContent(json_encode($cartService->getAll()));
            return $response;
        }
        return $this->redirect($this->generateUrl('product_view_cart'));
    }
    /**
     *@Route("/products/cart/view" , name="product_view_cart")
     */
    public function viewCartAction(){
        $cartService = $this->get('app.cart');
        $products= $cartService->getAll();
        return $this->render('ProductoBundle:productos:cart.html.twig', ['cart'=>$products]);
    }
}
