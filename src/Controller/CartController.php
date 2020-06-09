<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(CartService $cartService, ClientRepository $clientRepository)
    {

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'clients' =>$clientRepository->findAll()
        ]);
    }



    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService){

        $cartService->add($id);
        return $this->redirectToRoute("accueil");
    }

     /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService){
        
        $cartService->remove($id);
        return $this->redirectToRoute("cart_index");

    }

    /**
     * @Route("/panier/creerFacture", name="cart_creerFacture")
     */
    public function creerFacture($id, CartService $cartService){
        
    

    }
}
