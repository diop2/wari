<?php

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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     * 
     */
    public function register( Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if(isset($values->email , $values->password)) {

           

            $user = new User();
            
            $user->setEmail($values->email);
            $user->setRoles($values->roles);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setNomComplet($values->nomComplet);
            $user->setAdresse($values->adresse);
            $user->setNci($values->nci);
            $user->setTel($values->tel);
            $user->setIsActive($values->isActive);

            $entreprise = new Entreprise();

            $entreprise->setNom($values->nom);
            $entreprise->setAdresse($values->adresse);
            $entreprise->setTel($values->tel);
            $entreprise->setNci($values->nci);
            $entreprise->setNomComplet($values->nomComplet);
            $entreprise->setLINEA($values->linea);                                                    
            $entreprise->setRaisonSocial($values->raisonsocial);
            $entreprise->setSolde($values->solde);

            $user->setEntreprise($entreprise);  
            
            $versement = new Versement;
           
            $versement->setNumeroCompte($values->numerocompte);
            $versement->setSolde($values->solde);
            $versement->setDateversement(new \DateTime('now'));

            $versement->setEntreprise($entreprise);
            $versement->setCaissier($user);
            $versement->setVersementUser($user);
           
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $errors = $validator->validate($entreprise);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            
            $entityManager->persist($user);
            $entityManager->persist($entreprise);
            $entityManager->persist($versement);                                                                             
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'nouveau partenaire creer'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    }

   
}