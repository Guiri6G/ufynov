<?php

namespace App\Controller;

use\App\Repository\FactureRepository;
use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiFactureController extends AbstractController
{
    //RetrieveALL method = get

    /**
     * @Route("/api/facture", name="api_facture_index", methods={"GET"})
     */
    public function index(FactureRepository $factureRepository, SerializerInterface $serializer)
    {   

        try {

       return $this->json($factureRepository->findAll(), 200, [],  ['groups' => 'facture:read']);

        }catch(NotEncodableValueException $e){

            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }


    }

    //RetrieveOne method = get

    /**
     * @Route("/api/facture/{id}", name="api_facture_indexOne", methods={"GET"})
     */
    public function indexOne($id ,FactureRepository $factureRepository, SerializerInterface $serializer)
    {   
       try {   

        return $this->json($factureRepository->find($id), 200, [],  ['groups' => 'facture:read']);

       }catch(NotEncodableValueException $e){

        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }

    }

    // Create method = put

    /**
     * @Route("/api/facture", name="api_facture_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonRecu = $request->getContent();

        try{

            $facture = $serializer->deserialize($jsonRecu, Facture::class, 'json');
            $facture->setDateEmission(new \DateTime());
            $facture->setStatutPaiement(0);
            $errors = $validator->validate($facture);
            
            if(count($errors) > 0 )
            {
                return $this->json($errors, 400);
            }

            $em->persist($facture);
            $em->flush();
            

            return $this->json($facture, 201, [], ['groups' => 'facture:read']);

        }catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    // Update method = put

    /**
     * @Route("/api/facture/{id}", name="api_facture_updateF", methods={"PUT"})
     */

     public function updateC($id, Request $request, FactureRepository $factureRepository, EntityManagerInterface $em) : JsonResponse{
       
       try{
    
       $facture = $factureRepository->findOneBy(['id' => $id]);
       $data = json_decode($request->getContent(), true);

       $facture->setRefClient($data['refClient']);
       $facture->setPrixTotal($data['prixTotal']);
       
       $em->persist($facture);
       $em->flush();

        return $this->json($facture, 201, [], ['groups' => 'facture:read']);

       }catch(NotEncodableValueException $e){
    
        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }
       
     }

     // UpdatePaiement method = put

    /**
     * @Route("/api/factureUP/{id}", name="api_facture_updatePaiement", methods={"GET"})
     */

    public function updatePaiement($id, Request $request, FactureRepository $factureRepository, EntityManagerInterface $em){
       
        try{
     
        $facture = $factureRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $facture->setStatutPaiement(1);
        $facture->setDatePaiement(new \DateTime());

        
        $em->persist($facture);
        $em->flush();
 
        return $this->redirectToRoute("voir_facture");
 
        }catch(NotEncodableValueException $e){
     
         return $this->json([
             'status' => 400,
             'message' => $e->getMessage()
         ], 400);
     }
        
      }

     // DELETE method = delete

     /**
     * @Route("/api/facturess/{id}", name="api_facture_deleteF", methods={"GET"})
     */

     public function deleteF($id, FactureRepository $factureRepository, EntityManagerInterface $em){

        try {   

            $facture = $factureRepository->findOneBy(['id' => $id]);
            $em->remove($facture);
            $em->flush();

            return $this->redirectToRoute("voir_facture");

           }catch(NotEncodableValueException $e){
    
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }   
     }
}
