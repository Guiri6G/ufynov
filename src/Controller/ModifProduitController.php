<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ModifProduitController extends AbstractController
{
    /**
     * @Route("/modif/produit/{id}", name="modif_produit")
     */
    public function index(Request $request, $id, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);

        $form = $this->createFormBuilder($produit)

        ->add('nom', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])

        ->add('stock', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])
        
        ->add('prix', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])

        ->getForm();

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

            $article = $form->getData();
            $em->persist($article, $produit);
            $em->flush();

            return $this->redirectToRoute('gestion_produit');

        }
        return $this->render('modif_produit/index.html.twig', [
            'controller_name' => 'ModifProduitController',
            "form" => $form->createView()
        ]);
    }
}
