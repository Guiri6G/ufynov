<?php

namespace App\Controller;

use\App\Repository\ProduitRepository;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProduitController extends AbstractController
{
    //RetrieveALL method = get

    /**
     * @Route("/api/produit", name="api_produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository, SerializerInterface $serializer)
    {   
        

       return $this->json($produitRepository->findAll(), 200, [],  ['groups' => 'produit:read']);

        

    }

    //RetrieveOne method = get

    /**
     * @Route("/api/produit/{id}", name="api_produit_indexOne", methods={"GET"})
     */
    public function indexOne($id ,ProduitRepository $produitRepository, SerializerInterface $serializer)
    {   
       try {   

        return $this->json($produitRepository->find($id), 200, [],  ['groups' => 'produit:read']);

       }catch(NotEncodableValueException $e){

        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }

    }

    // Create method = post

    /**
     * @Route("/api/produit", name="api_produit_store", methods={"POST"})
     */
    public function store($test, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $jsonRecu1 = $test;
        $jsonRecu = $jsonRecu1->getContent();
        


            $produit1 = $serializer->deserialize($jsonRecu, Produit::class, 'json');

            $errors = $validator->validate($produit1);
            
            if(count($errors) > 0 )
            {
                return $this->json($errors, 400);
            }

            $em->persist($produit1);
            $em->flush();

            return $this->json($produit1, 201, [], ['groups' => 'produit:read']);

    }

    // Update method = put

    /**
     * @Route("/api/produit/{id}", name="api_produit_updateP", methods={"PUT"})
     */

     public function updateP($id, Request $request, ProduitRepository $produitRepository, EntityManagerInterface $em) : JsonResponse{
       
       try{
    
       $produit = $produitRepository->findOneBy(['id' => $id]);
       $data = json_decode($request->getContent(), true);

       $produit->setNom($data['nom']);
       $produit->setPhoto($data['photo']);
       $produit->setStock($data['stock']);
       $produit->setPrix($data['prix']);
       
       $em->persist($produit);
       $em->flush();

        return $this->json($produit, 201, [], ['groups' => 'produit:read']);

       }catch(NotEncodableValueException $e){
    
        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }
       
     }

     // DELETE method = delete

     /**
     * @Route("/api/produitss/{id}", name="api_produit_deleteP", methods={"GET"})
     */

     public function deleteP($id, ProduitRepository $produitRepository, EntityManagerInterface $em ){

        try {   

            $produit = $produitRepository->findOneBy(['id' => $id]);
            $em->remove($produit);
            $em->flush();

            return $this->redirectToRoute("accueil");

           }catch(NotEncodableValueException $e){
    
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }   
     }

}
