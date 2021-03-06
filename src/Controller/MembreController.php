<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType, App\Form\ProfilType;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Password;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class MembreController extends AbstractController
{
    /**
     * @Route("/admin/membre", name="membre_index", methods={"GET"})
     */
    public function index(MembreRepository $membreRepository): Response
    {
        return $this->render('membre/index.html.twig', [
            'membres' => $membreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/membre/new", name="membre_new", methods={"GET","POST"})
     */
    public function new(Request $request, Password $passwordEncoder): Response
    {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membre->setPassword( $passwordEncoder->encodePassword($membre, $form->get("password")->getData()) );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($membre);
            $entityManager->flush($membre);

            return $this->redirectToRoute('membre_index');
        }

        return $this->render('membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/membre/{id}", name="membre_show", methods={"GET"})
     */
    public function show(Membre $membre): Response
    {
        return $this->render('membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    /**
     * @Route("/admin/membre/{id}/edit", name="membre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Membre $membre): Response
    {
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->render('membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/membre/{id}", name="membre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Membre $membre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('membre_index');
    }

    /**
     * @Route("/profil", name="profil_index")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profilIndex(){
        
        return $this->render("profil/profil.html.twig");
    }


    /**
     * @Route("/profil/edit", name="profil_membre_edit", methods={"GET","POST"})
     */
    public function profilEdit(Request $request, MembreRepository $mr): Response
    {
        $membre=$this->getUser();
        $form = $this->createForm(ProfilType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($mdp = $form->get("password")->getData()){
                $membre->setPassword(password_hash($mdp, PASSWORD_DEFAULT));
            } else {
               $membre->setPassword($mr->find($membre->getId())->getPassword());  
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }



}
