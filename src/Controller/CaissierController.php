<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CaissierController extends AbstractController
{
    /**
     * @Route("/caissier", name="caissier")
     */
    public function index()
    {
        return $this->render('caissier/index.html.twig', [
            'controller_name' => 'CaissierController',
        ]);
    }
}
