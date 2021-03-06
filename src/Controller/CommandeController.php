<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Detail;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class CommandeController extends AbstractController
{
    /**
     * @Route("/admin/commande", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/commande/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/commande/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/admin/commande/{id}/edit", name="commande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commande $commande): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/commande/{id}", name="commande_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }


    /**
     * @Route("/commande/", name="commander")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function commander(SessionInterface $si, EntityManagerInterface $em)
    {
        $paniers = $si->get("paniers");
        $commande = new Commande;
        
        $commande->setMembre($this->getUser());
        $commande->setDateEnregistrement(new \DateTime("now"));
        $commande->setEtat("en attente");
        $montant = 0;
        foreach($paniers as $ligne){
            $detail = new Detail;
            $detail->setProduit($ligne["produit"]);
            $detail->setQuantite($ligne["qte"]);
            $detail->setPrix($ligne["produit"]->getPrix());
            $commande->addDetail($detail);
            $montant += $ligne["produit"]->getPrix() * $ligne["qte"];
        }
        $commande->setMontant($montant);
        $em->persist($commande);
        $em->flush();
        $si->remove("paniers");  

        return $this->redirectToRoute("home");
    }



   /**
     * @Route("/profil/commande/detail/{id}", name="profil_commande_show", methods={"GET"})
     */
    public function commandeShow(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }








}
