<?php

namespace App\Controller;

use\App\Repository\ClientRepository;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiClientController extends AbstractController
{
    //RetrieveALL method = get

    /**
     * @Route("/api/client", name="api_client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository)
    {   

        return $this->json($clientRepository->findAll(), 200, [], ['groups'=>'clients:read']);
    }

    //RetrieveOne method = get

    /**
     * @Route("/api/client/{id}", name="api_client_indexOne", methods={"GET"})
     */
    public function indexOne($id ,ClientRepository $clientRepository, SerializerInterface $serializer)
    {   
       try {   

        return $this->json($clientRepository->find($id), 200, [],  ['groups' => 'clients:read']);

       }catch(NotEncodableValueException $e){

        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }

    }

    // Create method = post

    /**
     * @Route("/api/client", name="api_clients_store", methods={"POST"})
     */
    public function store($test, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {   
        $jsonRecu1 = $test;
        $jsonRecu = $jsonRecu1->getContent();

        try{

            $client = $serializer->deserialize($jsonRecu, Client::class, 'json');
            $client->setDateCreation(new \DateTime());
            $errors = $validator->validate($client);
            
            if(count($errors) > 0 )
            {
                return $this->json($errors, 400);
            }

            $em->persist($client);
            $em->flush();

            return $this->json($client, 201, [], ['groups' => 'clients:read']);

        }catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    // Update method = put

    /**
     * @Route("/api/client/{id}", name="api_client_updateP", methods={"PUT"})
     */

     public function updateC($id, Request $request, ClientRepository $clientRepository, EntityManagerInterface $em) : JsonResponse{
       
       try{
    
       $client = $clientRepository->findOneBy(['id' => $id]);
       $data = json_decode($request->getContent(), true);

       $client->setNom($data['nom']);
       $client->setPrenom($data['prenom']);
       $client->setEmail($data['email']);
       
       $em->persist($client);
       $em->flush();

        return $this->json($client, 201, [], ['groups' => 'clients:read']);

       }catch(NotEncodableValueException $e){
    
        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }
       
     }

     // DELETE method = delete

     /**
     * @Route("/api/clientss/{id}", name="api_client_deleteC", methods={"GET"})
     */

     public function deleteC($id, ClientRepository $clientRepository, EntityManagerInterface $em ){

            
            try{

            $client = $clientRepository->findOneBy(['id' => $id]);
            $em->remove($client);
            $em->flush();

            return $this->redirectToRoute("voir_client");

            }catch(NotEncodableValueException $e){
    
        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }

}

}

