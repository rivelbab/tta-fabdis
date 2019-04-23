<?php

namespace App\Controller;

use App\Entity\{Octave, Fournisseur, Tarif, Upload};
use App\Repository\{TarifRepository, OctaveRepository, UploadRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\{FormBuilderInterface, Extension\Core\Type\ChoiceType};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;


class HistoryController extends AbstractController
{
    /**
     * @Route("/history/tarif/{fournisseurId}", name="history_tarif", methods="GET|POST")
     */
    public function indexTarif(Request $request, TarifRepository $tarifRepository, $fournisseurId=null, UploadRepository $upr): Response
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

        return $this->render('history/index.html.twig', [
            'form' => $form->createView(),
            'tarifs' => $tarifs,
            'fournisseur' => $selectedFr,
            'uploads' => $upr->findByIsOctave(false),
        ]);
    }

    /**
     * @Route("/history/tarif/{id}/show", name="tarif_history_show", methods="GET")
     */
    public function showTarif(Tarif $tarif): Response
    {
        $em = $this->getDoctrine()->getManager();
        // === consultation historique ===
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($tarif);

        return $this->render('history/show.html.twig', [
            'tarifLogs' => $logs,
            'tarif' => $tarif,
        ]);
    }

    /**
     * @Route("/history/octave/{fournisseurId}", name="history_octave", methods="GET|POST")
     */
    public function indexOctave(Request $request, OctaveRepository $octaveRepository, $fournisseurId=null, UploadRepository $upr): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $octaves = null;
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
            $octaves = $octaveRepository->findByFournisseurId($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('history/octave.html.twig', [
            'form' => $form->createView(),
            'octaves' => $octaves,
            'fournisseur' => $selectedFr,
            'uploads' => $upr->findByIsOctave(true),
        ]);
    }

    /**
     * @Route("/history/octave/{id}/show", name="octave_history_show", methods="GET")
     */
    public function showOctave(Octave $octave): Response
    {
        $em = $this->getDoctrine()->getManager();
        // === consultation historique ===
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($octave);

        return $this->render('history/show_octave.html.twig', [
            'octaveLogs' => $logs,
            'octave' => $octave,
        ]);
    }
}
