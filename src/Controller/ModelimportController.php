<?php

namespace App\Controller;

use App\Entity\Modelimport;
use App\Form\ModelimportType;
use App\Repository\ModelimportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request,Response};
use Symfony\Component\Routing\Annotation\Route;

class ModelimportController extends AbstractController
{
    private $display = false;
    /**
     * @Route("/modelimport", name="modelimport_index", methods="GET")
     */
    public function index(ModelimportRepository $modelimportRepository): Response
    {
        return $this->render('modelimport/index.html.twig', [
            'modelimports' => $modelimportRepository->findAll(),
            'display' => $this->display,
        ]);
    }

    /**
     * @Route("/modelimport/new", name="modelimport_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $modelimport = new Modelimport();
        $form = $this->createForm(ModelimportType::class, $modelimport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($modelimport);
                $em->flush();
                $this->addFlash('success',"Model d'import crée avec succès");
            } catch (\Exception $e) {
                $this->addFlash('danger',"Echec d'ajout, des erreurs sont survenues. Veillez réessayer");
            }

            return $this->redirectToRoute('modelimport_new');
        }

        return $this->render('modelimport/new.html.twig', [
            'modelimport' => $modelimport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modelimport/{id}", name="modelimport_show", methods="GET")
     */
    public function show(Modelimport $modelimport, ModelimportRepository $modelimportRepository): Response
    {
        $this->display = true;

        return $this->render('modelimport/index.html.twig', [
            'selectedMI' => $modelimport,
            'display'=>$this->display,
            'modelimports' => $modelimportRepository->findAll(),
        ]);
    }

    /**
     * @Route("/modelimport/{id}/edit", name="modelimport_edit", methods="GET|POST")
     */
    public function edit(Request $request, Modelimport $modelimport): Response
    {
        $form = $this->createForm(ModelimportType::class, $modelimport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success',"Model d'import mis à jour avec succès");
            } catch (\Exception $e) {
                $this->addFlash('danger',"Echec !!, des erreurs sont survenues. Veillez réessayer");
            }

            return $this->redirectToRoute('modelimport_edit', ['id' => $modelimport->getId()]);
        }

        return $this->render('modelimport/edit.html.twig', [
            'modelimport' => $modelimport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modelimport/{id}/delete", name="modelimport_delete", methods="DELETE")
     */
    public function delete(Request $request, Modelimport $modelimport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modelimport->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($modelimport);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger',"Impossible de supprimer ce model car il est utilisé par un fournisseur");
            }
        }

        return $this->redirectToRoute('modelimport_index');
    }
}
