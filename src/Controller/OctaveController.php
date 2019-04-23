<?php
namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\GroupeRemise;
use App\Entity\Octave;
use App\Form\OctaveType;
use App\Repository\OctaveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OctaveController extends AbstractController
{
    /**
     * @Route("/octave/accueil/{fournisseurId}", name="octave_index", methods="GET|POST")
     */
    public function index(Request $request, OctaveRepository $octaveRepository, $fournisseurId = null): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $octaves = null;
        $form = $this->createFormBuilder()
            ->add('selectedFr', ChoiceType::class, array(
                'placeholder' => "<< Selectionner un fournisseur ici >>",
                'choices' => $fournisseurs,
                'choice_label' => function ($fournisseurs) {
                    /** @var fournisseur $fournisseurs */
                    return strtoupper($fournisseurs->getCode());
                },
                'choice_attr' => function ($fournisseurs) {
                    return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
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

        return $this->render('octave/index.html.twig', [
            'form' => $form->createView(),
            'octaves' => $octaves,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/octave/new", name="octave_new", methods="GET|POST")
     */
    function new (Request $request): Response {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();

        $octave = new Octave();
        $form = $this->createForm(OctaveType::class, $octave);
        $form->add('selectedFr', ChoiceType::class, array(
            'placeholder' => "<< Choisir le model d'import ici >>",
            'choices' => $fournisseurs,
            'choice_label' => function ($fournisseurs) {
                return strtoupper($fournisseurs->getCode());
            },
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $octave->getSelectedFr()) {
                $octave->setFournisseurId($octave->getSelectedFr()->getId());
            }
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($octave);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }
            $this->addFlash('success', "Article crée avec succès");
            return $this->redirectToRoute('octave_new');
        }

        return $this->render('octave/new.html.twig', [
            'octave' => $octave,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/octave/{id}/show", name="octave_show", methods="GET")
     */
    public function show(Octave $octave): Response
    {
        return $this->render('octave/show.html.twig', ['octave' => $octave]);
    }

    /**
     * @Route("/octave/{id}/edit", name="octave_edit", methods="GET|POST")
     */
    public function edit(Request $request, Octave $octave): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $form = $this->createForm(OctaveType::class, $octave);
        $form->add('selectedFr', ChoiceType::class, array(
            'placeholder' => "<< Choisir le model d'import ici >>",
            'choices' => $fournisseurs,
            'choice_label' => function ($fournisseurs) {
                return strtoupper($fournisseurs->getCode());
            },
            'choice_attr' => function ($fournisseurs) {
                return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
            },
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $octave->getSelectedFr()) {
                $octave->setFournisseurId($octave->getSelectedFr()->getId());
            }
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "Oups!, erreur d'ajout, des erreurs sont survenues. Veillez réessayer.");
            }
            $this->addFlash('success', "Article mis à jour avec succès");

            return $this->redirectToRoute('octave_edit', ['id' => $octave->getId()]);
        }

        return $this->render('octave/edit.html.twig', [
            'octave' => $octave,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/octave/{id}/delete/{slug}", name="octave_delete", methods="DELETE")
     */
    public function delete(Request $request, Octave $octave, $slug = null): Response
    {
        $fournisseurId = null;

        if ($this->isCsrfTokenValid('delete' . $octave->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $fournisseurId = $octave->getFournisseurId();
            try {
                $em->remove($octave);
                $em->flush();
            } catch (\Exception $e) {
                //$this->addFlash('danger',"Oups!, Impossible de supprimer cet article.");
            }
        }
        if (null !== $slug) {
            return $this->redirectToRoute($slug, ['fournisseurId' => $fournisseurId]);
        } else {
            return $this->redirectToRoute('octave_index', ['fournisseurId' => $fournisseurId]);
        }
    }

    /**
     * @Route("/comparateur/prix/{fournisseurId}", name="comparateur_prix", methods="GET|POST")
     */
    public function comparateurPrix(Request $request, OctaveRepository $octaveRepository, $fournisseurId = null): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $octaves = null;
        $articles = $produits = array();
        $form = $this->createFormBuilder()
            ->add('selectedFr', ChoiceType::class, array(
                'placeholder' => "< Selectionner un fournisseur ici >",
                'choices' => $fournisseurs,
                'choice_label' => function ($fournisseurs) {
                    /** @var fournisseur $fournisseurs */
                    return strtoupper($fournisseurs->getCode());
                },
                'choice_attr' => function ($fournisseurs) {
                    return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
                },
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedFr = $form->get('selectedFr')->getData();
            $fournisseurId = $selectedFr->getId();
        }

        if (isset($fournisseurId)) {
            $octaves = $octaveRepository->comparateurPrix($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }

            $nbr = (int) count($octaves);

            for ($i = 0; $i < $nbr; $i++) {

                $articles[] = $octaves[$i];

                /**
                 * =====================
                 * Calcul du prix de base
                 * En tenant compte des conditions et des markups.
                 * calcul de la difference de prix octave vs tarif
                 * =====================
                 */
                //****** critere1: appliquer markup1 a tous les prix *********
                if ($selectedFr->getCritere() == 1) {
                    if (empty($octaves[$i]['tPrixVente'])) {
                        $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['oPrixVente']);
                    } else {
                        $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['tPrixVente'] * $selectedFr->getMarkup1());
                    }
                    //****** critere2: appliquer markup1 si prix < 4 sinon markup2 ********
                } elseif ($selectedFr->getCritere() == 2) {
                    if (empty($octaves[$i]['tPrixVente'])) {
                        $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['oPrixVente']);
                    } else {
                        if ($octaves[$i]['tPrixVente'] <= 4) {
                            $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['tPrixVente'] * $selectedFr->getMarkup2());
                        } else {
                            $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['tPrixVente'] * $selectedFr->getMarkup1());
                        }
                    }
                    //******* critere3: aucune condition specifique ********
                } elseif ($selectedFr->getCritere() == 100) {
                    if (empty($octaves[$i]['tPrixVente'])) {
                        $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['oPrixVente']);
                    } else {
                        $articles[$i]['prixBaseVente'] = floatval($octaves[$i]['tPrixVente']);
                    }
                }
                //********* dif prix octave vs tarif *********
                $articles[$i]['difVente'] = floatval($octaves[$i]['oPrixVente'] - $articles[$i]['prixBaseVente']);
                /**
                 * =====================
                 * Calcul du prix de base
                 * En tenant compte des conditions et des markups.
                 * calcul de la difference de prix octave vs tarif
                 * calcul de la remise fournisseur
                 * =====================
                 */
                //****** on evite d'avoir des prix vide dans prix de base *********
                if (empty($octaves[$i]['tPrixVente'])) {
                    $articles[$i]['prixBaseAchat'] = floatval($octaves[$i]['oPrixAchat']);
                } else {
                    $articles[$i]['prixBaseAchat'] = floatval($octaves[$i]['tPrixVente']);
                }
                //********* dif prix octave vs tarif *********
                $articles[$i]['difAchat'] = floatval($octaves[$i]['oPrixAchat'] - $articles[$i]['prixBaseAchat']);

                //******** calcul de remise *************
                $articles[$i]['remise'] = 0;
                if (!empty($octaves[$i]['tRemise'])) {
                    $articles[$i]['remise'] = $octaves[$i]['tRemise'];
                } elseif (!empty($selectedFr->getRemise())) {
                    $articles[$i]['remise'] = $selectedFr->getRemise();
                } elseif (!empty($octaves[$i]['tGroupeRemise'])) {
                    $groupeRemise = $this->getDoctrine()->getRepository(GroupeRemise::class)
                        ->findOneBy(array(
                            'nom' => $octaves[$i]['tGroupeRemise'],
                            'fournisseur' => $selectedFr->getId(),
                        ));
                    if (isset($groupeRemise)) {
                        $articles[$i]['remise'] = $groupeRemise->getRemise();
                    }
                } elseif (!empty($octaves[$i]['tPrixAchat'])) {
                    $r = -1 * (($octaves[$i]['tPrixAchat'] - $octaves[$i]['tPrixVente']) / $octaves[$i]['tPrixVente']);
                    $articles[$i]['remise'] = number_format($r, 2, '.', '');
                }
                //====== On rammene les remise en pourcentage ====
                if ($articles[$i]['remise'] > 1) {
                    $articles[$i]['remise'] = $articles[$i]['remise'] / 100;
                } elseif ($articles[$i]['remise'] < 0) {
                    $articles[$i]['remise'] = -1 * $articles[$i]['remise'];
                } else {
                    $articles[$i]['remise'] = $articles[$i]['remise'];
                }
                /**
                 * =========================
                 * Pour chaque ref en #, on ajoute une ligne sans #
                 * =========================
                 */
                if (substr($octaves[$i]['oCode'], -1) === '#') {
                    $produits[$i]['code'] = substr($octaves[$i]['oCode'], 0, -1);
                    $produits[$i]['prix'] = $articles[$i]['prixBaseVente'];
                }
            }
        }

        return $this->render('octave/comparateur_prix.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
            'produits' => $produits,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/octave/mismatchs/{fournisseurId}", name="octave_mismatchs", methods="GET|POST")
     */
    public function mismatchs(Request $request, OctaveRepository $octaveRepository, $fournisseurId = null): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $octaves = null;
        $form = $this->createFormBuilder()
            ->add('selectedFr', ChoiceType::class, array(
                'placeholder' => "< Selectionner un fournisseur ici >",
                'choices' => $fournisseurs,
                'choice_label' => function ($fournisseurs) {
                    /** @var fournisseur $fournisseurs */
                    return strtoupper($fournisseurs->getCode());
                },
                'choice_attr' => function ($fournisseurs) {
                    return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
                },
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedFr = $form->get('selectedFr')->getData();
            $fournisseurId = $selectedFr->getId();
        }

        if (isset($fournisseurId)) {
            $octaves = $octaveRepository->mismatchs($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('octave/mismatchs.html.twig', [
            'form' => $form->createView(),
            'octaves' => $octaves,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/octave/npu/{fournisseurId}", name="octave_npu", methods="GET|POST")
     */
    public function npu(Request $request, OctaveRepository $octaveRepository, $fournisseurId = null): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $octaves = null;
        $form = $this->createFormBuilder()
            ->add('selectedFr', ChoiceType::class, array(
                'placeholder' => "< Selectionner un fournisseur ici >",
                'choices' => $fournisseurs,
                'choice_label' => function ($fournisseurs) {
                    /** @var fournisseur $fournisseurs */
                    return strtoupper($fournisseurs->getCode());
                },
                'choice_attr' => function ($fournisseurs) {
                    return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
                },
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedFr = $form->get('selectedFr')->getData();
            $fournisseurId = $selectedFr->getId();
        }

        if (isset($fournisseurId)) {
            $octaves = $octaveRepository->npu($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('octave/npu.html.twig', [
            'form' => $form->createView(),
            'octaves' => $octaves,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/npu/findestock", name="octave_npu_all", methods="GET|POST")
     */
    public function allNpu(Request $request, OctaveRepository $octaveRepository): Response
    {
        return $this->render('octave/all_npu.html.twig', [
            'octaves' => $octaveRepository->allNpu(),
        ]);
    }

    /**
     * @Route("/article/special", name="article_special", methods="GET|POST")
     */
    public function articleSpecial(Request $request, OctaveRepository $octaveRepository): Response
    {
        return $this->render('octave/all_special.html.twig', [
            'octaves' => $octaveRepository->allSpecial(),
        ]);
    }

    /**
     * @Route("/octave/update/{id}/{slug}", name="octave_update", methods="GET|POST")
     */
    public function octaveUpdate(Request $request, Octave $octave, $slug = null): Response
    {
        $paths = null;

        if ($this->isCsrfTokenValid('edit' . $octave->getId(), $request->request->get('_token'))) {
            $comment = $request->request->get('comment');
            $etat = $request->request->get('etat');
            $refFournisseur = $request->request->get('refFournisseur');
            $isSpecial = $request->request->get('isSpecial');
            $prix2 = (float) $request->request->get('prixTSQ');
            $remise2 = (float) $request->request->get('remiseTSQ');

            if (isset($prix2)) {
                $octave->setPrixTSQ($prix2);
            } elseif (isset($remise2)) {
                $octave->remiseTSQ = $remise2;
            }

            if (isset($comment) && !empty($comment)) {
                $octave->setCommentaire($comment);
            } elseif (isset($etat) && !empty($etat)) {
                $octave->setEtat($etat);
            } elseif (isset($refFournisseur) && !empty($refFournisseur)) {
                $octave->setRefFournisseur($refFournisseur);
            } elseif (isset($isSpecial) && !empty($isSpecial)) {
                $octave->setIsSpecial($isSpecial);
            }

            $em = $this->getDoctrine()->getManager();

            try {
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "Oups!, les erreurs sont survenues, veillez réessayer.");
            }
        }
        if (isset($slug) && $slug === "comparateurPrix") {
            $paths = "comparateur_prix";
        } elseif (isset($slug) && $slug === "mismatchs") {
            $paths = "octave_mismatchs";
        } elseif (isset($slug) && $slug === "npu") {
            $paths = "octave_npu";
        } elseif (isset($slug) && $slug === "octaves") {
            $paths = "octave_index";
        } else {
            $paths = "home";
        }
        return $this->redirectToRoute("$paths", ['fournisseurId' => $octave->getFournisseurId()]);
    }

    /**
     * @Route("/octave/maj/status", name="update_status", methods="GET|POST")
     */
    public function majStatus(Request $request): Response
    {
        $isSpecial = $request->request->get('isSpecial');
        $idOctave = $request->request->get('id');

        if (isset($isSpecial)) {
            $this->getEm()->getRepository(Octave::class)->updateStatus($isSpecial, $idOctave);
        }
        return new Response();
    }

    /**
     * @Route("/octave/maj/etat", name="update_etat", methods="GET|POST")
     */
    public function majEtat(Request $request): Response
    {
        $etat = $request->request->get('npu');
        $idOctave = $request->request->get('id');

        if (isset($etat)) {
            $this->getEm()->getRepository(Octave::class)->updateEtat($etat, $idOctave);
        }
        return new Response();
    }
    /**
     * @Route("/octave/maj/refFournisseur", name="update_ref_fournisseur", methods="GET|POST")
     */
    public function majRefFournisseur(Request $request): Response
    {
        $refFr = $request->request->get('refFr');
        $idOctave = $request->request->get('id');

        if (isset($refFr)) {
            $this->getEm()->getRepository(Octave::class)->updateRefFournisseur($refFr, $idOctave);
        }
        return new Response();
    }
    /**
     * @Route("/octave/maj/prixSpecialVente", name="update_prix_special_vente", methods="GET|POST")
     */
    public function majPrixSpecialVente(Request $request): Response
    {
        $prix = (float) $request->request->get('prix');
        $idOctave = $request->request->get('id');

        if (isset($prix)) {
            $this->getEm()->getRepository(Octave::class)->updatePrixSpecialVente($prix, $idOctave);
        }
        return new Response();
    }
    /**
     * @Route("/octave/maj/prixSpecialAchat", name="update_prix_special_achat", methods="GET|POST")
     */
    public function majPrixSpecialAchat(Request $request): Response
    {
        $prix = (float) $request->request->get('prix');
        $idOctave = $request->request->get('id');

        if (isset($prix)) {
            $this->getEm()->getRepository(Octave::class)->updatePrixSpecialAchat($prix, $idOctave);
        }
        return new Response();
    }
    /**
     * @Route("/octave/maj/remiseSpeciale", name="update_remise_speciale", methods="GET|POST")
     */
    public function majRemiseSpeciale(Request $request): Response
    {
        $remise = (float) $request->request->get('remise');
        $idOctave = $request->request->get('id');

        if (isset($remise)) {
            $this->getEm()->getRepository(Octave::class)->updateRemiseSpeciale($remise, $idOctave);
        }
        return new Response();
    }

    /**
     * @Route("/octave/maj/comment", name="update_comment", methods="GET|POST")
     */
    public function majComment(Request $request): Response
    {
        $comment = $request->request->get('comment');
        $idOctave = $request->request->get('id');

        if (isset($comment)) {
            $this->getEm()->getRepository(Octave::class)->updateCommentaire($comment, $idOctave);
        }
        return new Response();
    }

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
