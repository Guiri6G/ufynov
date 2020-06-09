<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GestionProduitController extends AbstractController
{
    /**
     * @Route("/gestion/produit", name="gestion_produit")
     */
    public function index(ProduitRepository $produitRepository)
    {
        return $this->render('gestion_produit/index.html.twig', [
            'controller_name' => 'GestionProduitController',
            'Gesproduits' => $produitRepository->findAll()
        ]);
    }
}
