<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoirClientController extends AbstractController
{
    /**
     * @Route("/voir/client", name="voir_client")
     */
    public function index(ClientRepository $clientRepository)
    {
        return $this->render('voir_client/index.html.twig', [
            'controller_name' => 'VoirClientController',
            'clients' => $clientRepository->findAll()
        ]);
    }
}
