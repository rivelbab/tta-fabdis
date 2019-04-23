<?php
namespace App\Controller;

use App\Entity\{Fournisseur, Modelimport, Octave, Tarif};
use App\Form\FournisseurType;
use App\Repository\{FournisseurRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FournisseurController extends AbstractController
{
    private $display = false;
    private $em;

    /**
     * @Route("/fournisseur", name="fournisseur_index", methods="GET")
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
            'display' => $this->display,
        ]);
    }

    /**
     * @Route("/fournisseur/new", name="fournisseur_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $modelimports = $this->getDoctrine()->getRepository(Modelimport::class)->findAll();

        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->add('miCommerce', ChoiceType::class, array(
            'placeholder' => "<< Choisir le model d'import pour l'onglet commerce >>",
            'choices' => $modelimports,
            'choice_label' => function($modelimports) {
                return strtoupper($modelimports->getName());
            },
            'choice_attr' => function($modelimports) {
                return ['class' => 'modelimport_'.strtolower($modelimports->getName())];
            },
        ));
        $form->add('miMedia', ChoiceType::class, array(
            'placeholder' => "<< Choisir le model d'import pour l'onglet Media >>",
            'choices' => $modelimports,
            'choice_label' => function($modelimports) {
                return strtoupper($modelimports->getName());
            },
            'choice_attr' => function($modelimports) {
                return ['class' => 'modelimport_'.strtolower($modelimports->getName())];
            },
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getEm()->persist($fournisseur);
                $this->getEm()->flush();
                $this->addFlash('success',"Fournisseur crée avec succès");
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }

            return $this->redirectToRoute('fournisseur_new');
        }

        return $this->render('fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fournisseur/{id}", name="fournisseur_show", methods="GET")
     */
    public function show(Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository): Response
    {
        $this->display = true;

        return $this->render('fournisseur/index.html.twig', [
            'selectedFr' => $fournisseur,
            'display' => $this->display,
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/fournisseur/{id}/edit", name="fournisseur_edit", methods="GET|POST")
     */
    public function edit(Request $request, Fournisseur $fournisseur): Response
    {
        $modelimports = $this->getDoctrine()->getRepository(Modelimport::class)->findAll();

        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->add('miCommerce', ChoiceType::class, array(
            'placeholder' => "Choisir le model d'import pour l'onglet commerce",
            'choices' => $modelimports,
            'choice_label' => function($modelimports) {
                return strtoupper($modelimports->getName());
            },
            'choice_attr' => function($modelimports) {
                return ['class' => 'modelimport_'.strtolower($modelimports->getName())];
            },
        ));
        $form->add('miMedia', ChoiceType::class, array(
            'placeholder' => "Choisir le model d'import pour l'onglet media",
            'choices' => $modelimports,
            'choice_label' => function($modelimports) {
                return strtoupper($modelimports->getName());
            },
            'choice_attr' => function($modelimports) {
                return ['class' => 'modelimport_'.strtolower($modelimports->getName())];
            },
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getEm()->flush();
                $this->addFlash('success',"Fournisseur mis à jour avec succès");
            } catch (\Exception $e) {
                $this->addFlash('danger',"Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }

            return $this->redirectToRoute('fournisseur_edit', ['id' => $fournisseur->getId()]);
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fournisseur/{id}/delete", name="fournisseur_delete", methods="DELETE")
     */
    public function delete(Request $request, Fournisseur $fournisseur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($fournisseur);
                $this->getEm()->flush();
            } catch (\Exception $e) {
                    $this->addFlash('danger',"Oups!, Impossible de supprimer ce fournisseur car il est utilisé.");
            }
        }

        return $this->redirectToRoute('fournisseur_index');
    }

    /*
    * @Route("/statistique/maj/fournisseur/update", name="fournisseur_stats_update", methods="GET|POST")
    *
    public function statsUpdate(Request $request, FournisseurRepository $fournisseurRepository) : Response
    {
        $id = intval($request->request->get('myData'));
        if (isset($id)) {
            $fournisseur = $fournisseurRepository->findOneById($id);
            if (isset($fournisseur)) {
                $fournisseur->setMaj(1);
                $date = new \DateTime('now');
                $fournisseur->setUpdatedAt($date);
                try {
                    $this->getEm()->flush();
                } catch (\Exception $e) {
                    $this->addFlash('danger',"Oups!, les erreurs sont survenues, veillez réessayer.");
                }
            }
        }
        return new Response();
    } 
    */

    /*
    * @Route("/statistique/maj/fournisseur", name="fournisseur_stats", methods="GET|POST")
    *
    public function stats(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $statbyfr = array();

        $fournisseurs = $fournisseurRepository->findAll();
        $nbrFr = count($fournisseurs);

        for ($i=0; $i < $nbrFr; $i++) { 
            $statbyfr[$i]['maj'] = $fournisseurs[$i]->getMaj();
            $statbyfr[$i]['total'] = count($this->getEm()->getRepository(Octave::class)->allArticlesByFr($fournisseurs[$i]->getId()));
            $statbyfr[$i]['totalTarif'] = count($this->getEm()->getRepository(Octave::class)->allArticlesByFrTarif($fournisseurs[$i]->getId()));
            $statbyfr[$i]['mismatchs'] = count($this->getEm()->getRepository(Octave::class)->mismatchs($fournisseurs[$i]->getId()));
            $statbyfr[$i]['matchs'] = $statbyfr[$i]['total']  - $statbyfr[$i]['mismatchs'];
            $statbyfr[$i]['code'] = $fournisseurs[$i]->getCode();
            $statbyfr[$i]['id'] = $fournisseurs[$i]->getId();
            $statbyfr[$i]['updatedAt'] = $fournisseurs[$i]->getUpdatedAt();

            (!empty($statbyfr[$i]['total'])) ? $statbyfr[$i]['percent'] = ( $statbyfr[$i]['matchs'] * 100 ) / $statbyfr[$i]['total'] : $statbyfr[$i]['percent'] = 0 ;
        }
        return $this->render('fournisseur/stats.html.twig', [
            'fournisseurs' => $statbyfr,
        ]);
    }
    */

    /**
    * Return entity manager
    * check if connexion is always open
    * if not then open it before return
    */
    private function getEm()
    {
        $this->em = $this->getDoctrine()->getManager();
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }
        return $this->em;
    }
}
