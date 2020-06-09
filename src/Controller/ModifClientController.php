<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModifClientController extends AbstractController
{
    /**
     * @Route("/modif/client/{id}", name="modif_client")
     */
    public function index(Request $request, $id, EntityManagerInterface $em): Response
    {
        $client = new Client();
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);

        $form = $this->createFormBuilder($client)

        ->add('nom', TextType::class, [
            "attr" => [
                "class" => "form-control",
                "style="  
                
            ]
        ])

        ->add('prenom', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])
        
        ->add('email', EmailType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])

        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $article = $form->getData();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('voir_client');

        }





        return $this->render('modif_client/index.html.twig', [
            'controller_name' => 'ModifClientController', "form" => $form->createView()
        ]);
    }
}
