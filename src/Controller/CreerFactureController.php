<?php

namespace App\Controller;

use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreerFactureController extends AbstractController
{
    /**
     * @Route("/creer/facture", name="creer_facture")
     */
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session) : Response
    {

        $prixTotal = $request->request->get('prixTotal');
        $choixClient = $request->request->get('choixClient');

        if(!empty($choixClient) && (!empty($prixTotal)) ){

            $newFacture = new Facture();

            $newFacture
                ->setRefClient($choixClient)
                ->setDateEmission(new \DateTime())
                ->setStatutPaiement(0)
                ->setPrixTotal($prixTotal);
                
                $em->persist($newFacture);
                $em->flush();

                $session->clear();

            return $this->redirectToRoute("accueil");
        }

        return $this->render('creer_facture/index.html.twig', [
            'controller_name' => 'CreerFactureController',
        ]);
    }
}
