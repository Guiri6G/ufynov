<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(ProduitRepository $produitRepository, SessionInterface $session)
    {

        return $this->render('accueil/index.html.twig', [
            'produits' => $produitRepository->findAll()
        ]);
    }
}
