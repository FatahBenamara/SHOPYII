<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $pr)
    {

        $produits = $pr->findAll();

        return $this->render('home/index.html.twig', [ "produits" => $produits ]);
    }

  /**
     * @Route("/recherche", name="recherche")
     */
    public function recherche(ProduitRepository $pr, Request $request)
    {
        $motRecherche = $request->query->get("recherche");
        if($motRecherche){
            
            $liste_produits = $pr->findByTitreCategorieDescription($motRecherche);
        } else {
            $liste_produits = [];
        }
            
        return $this->render('home/index.html.twig', [ "produits" => $liste_produits, "mot_recherche" => $motRecherche ]);
    }



    
}
