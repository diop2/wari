<?php

namespace App\Controller;
namespace App\Controller;
use App\Entity\User;
use App\Entity\Versement;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;

class VersementController extends AbstractController
{
    /**
     * @Route("/versement", name="versement")
     */
    public function register( Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());

            $versement = new Versement;
            $repository = $this->getDoctrine()->getRepository(Entreprise::class);
            $entreprise=$repository->find($values->entreprise_id);
            $versement->setEntreprise($entreprise);
            $repository = $this->getDoctrine()->getRepository(user::class);
            $ver=$repository->find($values->caissier_id);
            $versement->setCaissier($ver);
            $versement->setType($values->type);
            $versement->setSolde($values->solde);                                                                                                                                                                                                                      
            $versement->setDateversement(new \DateTime('now'));
            $repository = $this->getDoctrine()->getRepository(User::class);
            $entreprise=$repository->find($values->versementuser_id);
            $versement->setVersementuser($entreprise);
            $errors = $validator->validate($versement);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($versement);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Vesement effectuer'
            ];

            return new JsonResponse($data, 201);
        
        $data = [
            'status' => 500,
            'message' => 'versement nom effectuer'
        ];
        return new JsonResponse($data, 500);
    }
}
