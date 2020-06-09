<?php

namespace App\Controller;

use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoirFactureController extends AbstractController
{
    /**
     * @Route("/voir/facture", name="voir_facture")
     */
    public function index(FactureRepository $factureRepository)
    {
        return $this->render('voir_facture/index.html.twig', [
            'controller_name' => 'VoirFactureController',
            'factures' => $factureRepository->findAll()
        ]);
    }
}
