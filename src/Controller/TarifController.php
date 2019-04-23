<?php

namespace App\Controller;

use App\Entity\{Tarif, Fournisseur};
use App\Form\TarifType;
use App\Repository\TarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\{FormBuilderInterface, Extension\Core\Type\ChoiceType};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class TarifController extends AbstractController
{
    /**
     * @Route("/tarif/accueil/{fournisseurId}", name="tarif_index", methods="GET|POST")
     */
    public function index(Request $request, TarifRepository $tarifRepository, $fournisseurId=null): Response
    {

        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $tarifs = null;
        $form = $this->createFormBuilder()
        ->add('selectedFr', ChoiceType::class, array(
            'placeholder' => "<< Selectionner un fournisseur ici >>",
            'choices' => $fournisseurs,
            'choice_label' => function($fournisseurs) {
                /** @var fournisseur $fournisseurs */
                return strtoupper($fournisseurs->getCode());
            },
            'choice_attr' => function($fournisseurs) {
                return ['class' => 'fournisseur_'.strtolower($fournisseurs->getCode())];
            },
        ))
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedFr = $form->get('selectedFr')->getData();
            $fournisseurId = $selectedFr->getId();
        }

        if (isset($fournisseurId)) {
            $tarifs = $tarifRepository->findByFournisseurId($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('tarif/index.html.twig', [
            'form' => $form->createView(),
            'tarifs' => $tarifs,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/tarif/new", name="tarif_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();

        $tarif = new Tarif();
        $form = $this->createForm(TarifType::class, $tarif);
        $form->add('selectedFr', ChoiceType::class, array(
            'placeholder' => "<< Choisir le model d'import ici >>",
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
            if (null !== $tarif->getSelectedFr()) {
                $tarif->setFournisseurId($tarif->getSelectedFr()->getId());
            }
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($tarif);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }
            $this->addFlash('success',"Article crée avec succès");
            return $this->redirectToRoute('tarif_new');
        }

        return $this->render('tarif/new.html.twig', [
            'tarif' => $tarif,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tarif/{id}/show", name="tarif_show", methods="GET")
     */
    public function show(Tarif $tarif): Response
    {
        return $this->render('tarif/show.html.twig', ['tarif' => $tarif]);
    }

    /**
     * @Route("/tarif/{id}/edit", name="tarif_edit", methods="GET|POST")
     */
    public function edit(Request $request, Tarif $tarif): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $form = $this->createForm(TarifType::class, $tarif);
        $form->add('selectedFr', ChoiceType::class, array(
            'placeholder' => "<< Choisir le model d'import ici >>",
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
            if (null !== $tarif->getSelectedFr()) {
                $tarif->setFournisseurId($tarif->getSelectedFr()->getId());
            }
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }
            $this->addFlash('success',"Article mis à jour avec succès");
            return $this->redirectToRoute('tarif_edit', ['id' => $tarif->getId()]);
        }

        return $this->render('tarif/edit.html.twig', [
            'tarif' => $tarif,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tarif/{id}/delete", name="tarif_delete", methods="DELETE")
     */
    public function delete(Request $request, Tarif $tarif): Response
    {
        $fournisseurId = null;

        if ($this->isCsrfTokenValid('delete'.$tarif->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $fournisseurId = $tarif->getFournisseurId();
            try {
                $em->remove($tarif);
                $em->flush();
            } catch (\Exception $e) {
                    $this->addFlash('danger',"Oups!, Impossible de supprimer cet article.");
            }
        }

        return $this->redirectToRoute('tarif_index');
    }
}
