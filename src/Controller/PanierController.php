<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;



class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $si)
    {
        $paniers = $si->get("paniers");
        
        return $this->render('panier/index.html.twig', [ "paniers" => $paniers ]);
    }

    /**
     * @Route("/panier/addtopanier/{id}", name="addtopanier")
     */
    public function addToPanier(SessionInterface $si, ProduitRepository $produitRepository, $id, Request $request)
    {
        //ajouter un produit au panier

        $produitToAdd = $produitRepository->find($id);
        $paniers= $si -> get("paniers", []);


        //ajout multiple
        $qte = $request->query->get("qte");
        $qte = empty($qte) ? 1 : $qte;


        
        
        $existe = false;
        foreach($paniers as $indice => $ligne){
            if($produitToAdd->getId() == $ligne["produit"]->getId()){
                $qte += $ligne["qte"];
                $paniers[$indice] = [ "produit" => $produitToAdd, "qte" => $qte ];
                $existe = true;
            } 
        }

        if(!$existe){
            $paniers[] = [ "produit" => $produitToAdd, "qte" => $qte ];
        }
        $this->addFlash("success", "Le produit a été ajouté dans votre panier");
        $si->set("paniers", $paniers);
        return $this->redirectToRoute("home");
    }


    /**
     * @Route("/panier/vider", name="vider_panier")
     */
    public function viderPanier(SessionInterface $si)
    {

        $si->remove("paniers");

        return $this->redirectToRoute("home");
    }


    /**
     * @Route("/panier/supprimer-produit/{id}", name="supprimer_produit_panier")
     */
    public function supprimerProduit(SessionInterface $si, $id){
        $paniers = $si->get("paniers");
        foreach($paniers as $indice => $ligne){
            if($ligne["produit"]->getId() == $id){
                unset($paniers[$indice]);
                break;
            }
        }
        $si->set("paniers", $paniers);
        return $this->redirectToRoute("panier");
    }


    /**
     * @Route("/panier/modifier-produit/{id}", name="modifier_produit_panier")
     */
    public function modifierQuantite(SessionInterface $si, Request $request, $id){
        $paniers = $si->get("paniers");
        $qte = $request->query->get("qte");
        foreach($paniers as $indice => $ligne){
            if($ligne["produit"]->getId() == $id){
                $paniers[$indice]["qte"] = $qte;
                break;
            }
        }
        $si->set("paniers", $paniers);
        return $this->redirectToRoute("panier");
    }

}
