<?php

namespace App\Controller;

use App\Entity\{GroupeRemise, Fournisseur};
use App\Form\GroupeRemiseType;
use App\Repository\GroupeRemiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GroupeRemiseController extends AbstractController
{
    private $display = false;
    /**
     * @Route("/groupe-remise", name="groupe_remise_index", methods="GET")
     */
    public function index(GroupeRemiseRepository $groupeRemiseRepository): Response
    {
        return $this->render('groupe_remise/index.html.twig', [
            'groupe_remises' => $groupeRemiseRepository->findAll(),
            'display' => $this->display,
        ]);
    }

    /**
     * @Route("/groupe-remise/new", name="groupe_remise_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();

        $groupeRemise = new GroupeRemise();
        $form = $this->createForm(GroupeRemiseType::class, $groupeRemise);
        $form->add('fournisseur', ChoiceType::class, array(
            'placeholder' => "<< Choisir le fournisseur concerné ici >>",
            'choices' => $fournisseurs,
            'choice_label' => function($fournisseurs) {
                return strtoupper($fournisseurs->getCode());
            },
            'choice_attr' => function($fournisseurs) {
                return ['class' => 'fournisseur_'.strtolower($fournisseurs->getCode())];
            },
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($groupeRemise);
                $em->flush();
                $this->addFlash('success',"Groupe crée avec succès");
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }


            return $this->redirectToRoute('groupe_remise_new');
        }

        return $this->render('groupe_remise/new.html.twig', [
            'groupe_remise' => $groupeRemise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/groupe-remise/{id}", name="groupe_remise_show", methods="GET")
     */
    public function show(GroupeRemise $groupeRemise, GroupeRemiseRepository $groupeRemiseRepository): Response
    {
        $this->display = true;

        return $this->render('groupe_remise/index.html.twig', [
            'selectedGR' => $groupeRemise,
            'display' => $this->display,
            'groupe_remises' => $groupeRemiseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/groupe-remise/{id}/edit", name="groupe_remise_edit", methods="GET|POST")
     */
    public function edit(Request $request, GroupeRemise $groupeRemise): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();

        $form = $this->createForm(GroupeRemiseType::class, $groupeRemise);
        $form->add('fournisseur', ChoiceType::class, array(
            'placeholder' => "<< Choisir le fournisseur concerné ici >>",
            'choices' => $fournisseurs,
            'choice_label' => function($fournisseurs) {
                return strtoupper($fournisseurs->getCode());
            },
            'choice_attr' => function($fournisseurs) {
                return ['class' => 'fournisseur_'.strtolower($fournisseurs->getCode())];
            },
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success',"Groupe mis à jour avec succès");
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }

            return $this->redirectToRoute('groupe_remise_edit', ['id' => $groupeRemise->getId()]);
        }

        return $this->render('groupe_remise/edit.html.twig', [
            'groupe_remise' => $groupeRemise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/groupe-remise/{id}/delete", name="groupe_remise_delete", methods="DELETE")
     */
    public function delete(Request $request, GroupeRemise $groupeRemise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupeRemise->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($groupeRemise);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, Impossible de supprimer ce groupe car il est utilisé.");
            }
        }

        return $this->redirectToRoute('groupe_remise_index');
    }
}
