<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Entreprise;
use App\Controller\UserController;
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
        if(isset($values->email,$values->password)) {
            $user = new User();
            $repository = $this->getDoctrine()->getRepository(Entreprise::class);
            $entreprise=$repository->find($values->entreprise_id);
            $user->setEntreprise($entreprise);
            $user->setEmail($values->email);
            $user->setRoles($user->getRoles());
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setNomComplet($values->nomComplet);
            $user->setAdresse($values->adresse);
            $user->setNci($values->nci);
            $user->setTel($values->tel);
            $user->setIsActive($values->isActive);
            
            
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'utilisateur ajouter'
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