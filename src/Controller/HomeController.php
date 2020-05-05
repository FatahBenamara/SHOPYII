<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $pr)
    {

        $liste_produits = $pr->findAll();
        return $this->render('home/index.html.twig', [ "produits" => $liste_produits ]);
    }
}
