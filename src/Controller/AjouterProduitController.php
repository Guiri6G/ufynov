<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Controller\ApiProduitController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AjouterProduitController extends AbstractController
{
    /**
     * @Route("/ajouter/produit", name="ajouter_produit")
     */
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $produit = new Produit();
        $form  =  $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        

    

        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $photoFile */

            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $produit->setPhoto($newFilename); 
            }


            
            
            $data1 = $this->json($form);

            


            $test1 = $this->forward('App\Controller\ApiProduitController::store',[
                'test' => $data1,
            ]);

            $em->persist($produit);
            $em->flush();
            
            
            return $this->redirectToRoute("ajouter_produit");
        

        }

       
        

        return $this->render('ajouter_produit/index.html.twig', [
            'controller_name' => 'AjouterProduitController', "form" => $form->createView()
        ]);
    }
}
