<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjoutClientController extends AbstractController
{
    /**
     * @Route("/ajout/client", name="ajout_client")
     */
    public function index(Request $request): Response
    {

        $client = new Client();
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data1 = $this->json($form);


            $test1 = $this->forward('App\Controller\ApiClientController::store',[
                'test' => $data1,
            ]);
            
            return $this->redirectToRoute("voir_client");
        }

        

        return $this->render('ajout_client/index.html.twig', [
            'controller_name' => 'AjoutClientController', "form" => $form->createView()
        ]);
    }
}
