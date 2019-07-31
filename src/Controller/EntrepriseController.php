<?php

namespace App\Controller;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function register( Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());

            $entreprise = new entreprise();

            $entreprise->setNom($values->nom);
            $entreprise->setLINEA($values->linea);                                                                                                                                                                                                                      
            $entreprise->setRaisonSocial($values->raisonsocial);
            $entreprise->setSolde($values->solde);
            $errors = $validator->validate($entreprise);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'utilisateur ajouter'
            ];

            return new JsonResponse($data, 201);
        
        $data = [
            'status' => 500,
            'message' => 'utilisateur non ajouter'
        ];
        return new JsonResponse($data, 500);
    }
}
