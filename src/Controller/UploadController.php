<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Commerce;
use App\Entity\Media;
use App\Entity\Article;
use App\Entity\Upload;
use App\Entity\Codebarre;
use App\Form\UploadType;

use App\Repository\ModelimportRepository;
use App\Repository\UploadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use \PhpOffice\PhpSpreadsheet\IOFactory;

class UploadController extends AbstractController
{
    private $em;

    /**
     * @Route("/upload/accueil", name="upload_index", methods="GET|POST")
     */
    public function index(Request $request, UploadRepository $uploadRepository): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();

        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        $form->add('fournisseur', ChoiceType::class, array(
            'placeholder' => "<< Selectionner un fournisseur ici >>",
            'choices' => $fournisseurs,
            'choice_label' => function ($fournisseurs) {
                /** @var fournisseur $fournisseurs */
                return strtoupper($fournisseurs->getCode());
            },
            'choice_attr' => function ($fournisseurs) {
                return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
            },
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $upload->setStatut(0);

            try {
                $this->getEm()->persist($upload);
                $this->getEm()->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "Oups!, erreur d'import.Vérifier votre fichier et réessayer.");
                return $this->redirectToRoute('upload_index');
            }

            return $this->redirectToRoute('upload_check', ['id' => $upload->getId()]);
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
            'uploads' => $uploadRepository->findAll(),
        ]);
    }

    /**
     * @Route("/upload/{id}/check", name="upload_check", methods="GET|POST")
     */
    public function check(Request $request, Upload $upload, ModelimportRepository $modelimportRepository, UploaderHelper $helper): Response
    {
        //======= initialisation =====
        $dataArray = array();
        $commerce = new Commerce();
        $media = new Media();
        $article = new Article();
        $codebarre = new Codebarre();
        $worksheet = null;
        $fournisseur = $upload->getFournisseur();
        $fr_mi = null;
        $fr_mi_fields = array();
        $isDoublon = false;
        $isError = false;
        $trigramme = $fournisseur->getTrigramme();

        /**
         * =========
         * Si on importe l'onglet media, on prend le miMedia 
         * sinon on prend le miCommerce porté par le fournisseur
         * Ensuite on transforme les fields en tableaux pour mieux
         * les utiliser dans la boucle
         * =========
         */
        if ($upload->getIsMedia()) {
            $fr_mi = $fournisseur->getMiMedia();
        } else {
            $fr_mi = $fournisseur->getMiCommerce();
        }
        $fields = array_map('trim', explode(',', $fr_mi->getFields()));
        $i = 'A';

        foreach ($fields as $value) {
            $fr_mi_fields[$i] = $value;
            $i++;
        }
        /**
         * ========
         * preparation du fichier excel
         * ========
         */
        $inputFileName = $helper->asset($upload, 'tarifFile');
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);

        /**
         * =========
         * Choix du worksheet
         * =========
         */
        if ($upload->getIsMedia()) {
            try {
                $worksheet = $spreadsheet->getSheetByName('03_MEDIA');
                if ($worksheet == null) {
                    $worksheet = $spreadsheet->getSheetByName('Média');
                }
            } catch (\Throwable $th) {
                $this->addFlash('warning', "Votre fichier ne contient pas d'onglet 03_MEDIA ni Média");
            }
        } elseif($upload->getIsGoogleShopping()) {
            try {
                $worksheet = $spreadsheet->getSheet(0);
            } catch (\Throwable $th) {
                $this->addFlash('warning', "Votre fichier ne contient pas d'onglet 03_MEDIA ni Média");
            }
        } else {
            try {
                $worksheet = $spreadsheet->getSheetByName('01_COMMERCE');
                if ($worksheet == null) {
                    $worksheet = $spreadsheet->getSheetByName('Commerce');
                }
            } catch (\Throwable $th) {
                $this->addFlash('warning', "Votre fichier ne contient pas d'onglet 01_COMMERCE ni Commerce");
            }
        }

        /**
         * =========
         * Lecture du fichier ligne par ligne
         * =========
         */
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $row = $row->getRowIndex();
            foreach ($cellIterator as $cell) {
                $col = $cell->getColumn();
                $dataArray[$row][$col] = $cell->getCalculatedValue();
            }
        }
        /**
         * ==========
         * Creation du formulaire de verification
         *===========
         */
        $startReading = $endReading = 0;
        $form = $this->createFormBuilder()
            ->add('startReading', IntegerType::class)
            ->add('endReading', IntegerType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $startReading = $form->get('startReading')->getData();
            $endReading = $form->get('endReading')->getData();
            /**
             * ==========
             * On parcourt tout le tableau et en fonction
             * du model d'import, on remplie l'entite
             * ==========
             */
            for ($row = $startReading; $row <= $endReading; $row++) {
                foreach ($fr_mi_fields as $key => $value) {
                    if ($upload->getIsMedia()) {

                        //***** configuration des données médias *****
                        $media->$value = $dataArray[$row][$key];
                        $media->setFournisseurId($fournisseur->getId());
                        if (null !== $media->getRefciale()) {
                            $media->setRefciale(trim($media->getRefciale(), " \t\n\r"));
                        }
                    } elseif ($upload->getIsGoogleShopping()) {

                        //****** insertion des donnees google shopping ******/
                        $codebarre->$value = $dataArray[$row][$key];
                        $codebarre->setFournisseurId($fournisseur->getId());
                    } else {
                        //****** configuration tarif articles ******
                        $commerce->$value = $dataArray[$row][$key];
                        $commerce->setFournisseurId($fournisseur->getId());
                        if (null !== $commerce->getRefciale()) {
                            $commerce->setRefciale(trim($commerce->getRefciale(), " \t\n\r"));
                        }
                        //******* ajout dans articles ********** */
                        $code = $trigramme . $commerce->getRefciale();
                        $article->setCode($code);
                        $article->setReference($commerce->getRefciale());
                        $article->setDescription($commerce->getLibelle80());
                        $article->setFournisseur($fournisseur->getCode());
                        $article->setMarque($commerce->getMarque());
                        $article->setEan($commerce->getGtin13());
                        $article->setTextcourt($commerce->getLibelle240());
                        $article->setTextlong($commerce->getLibelle240());
                        $article->setStock(1);
                        $article->setGarantie($commerce->getDug());
                        $article->setRefcommande($commerce->getRefciale());
                        $article->setPrixvente($commerce->getTarif());
                        $article->setPrixachat($commerce->getTarif());
                        $article->setReffournisseur($commerce->getRefciale());
                        $article->setDesignationfr($commerce->getLibelle80());
                        $article->setFournisseurId($fournisseur->getId());
                    }
                }
                /**
                 * ===================
                 * Si tout est ok, on ajoute dans la BD
                 * S'il y a des doublons, on les met a jour
                 * ===================
                 */
                try {
                    if ($upload->getIsMedia()) {
                        $media_dup = $this->getEm()->getRepository(Media::class)->findOneBy(['refciale' => $media->getRefciale()]);
                        if ($media_dup !== null) {
                            foreach ($fr_mi_fields as $key => $value) {
                                if ($value !== "refciale") {
                                    $media_dup->$value = $media->$value;
                                    $date = new \DateTime('now');
                                    $media_dup->setUpdatedAt($date);                                    
                                }
                                if (null !== $media->getRefciale()) {
                                    $media_dup->setRefciale(trim($media->getRefciale(), " \t\n\r"));
                                }
                            }
                            $upload->setStatut(2);
                            $this->getEm()->flush();
                        } else {
                            $upload->setStatut(2);
                            $this->getEm()->persist($media);
                            $this->getEm()->flush();
                        }

                    } elseif ($upload->getIsGoogleShopping()) {
                        $media_dup = $this->getEm()->getRepository(Codebarre::class)->findOneBy(['item' => $codebarre->getItem()]);
                        if ($media_dup !== null) {
                            foreach ($fr_mi_fields as $key => $value) {
                                if ($value !== "item") {
                                    $media_dup->$value = $codebarre->$value;                                    
                                }
                                if (null !== $codebarre->getItem()) {
                                    $media_dup->setItem(trim($codebarre->getItem(), " \t\n\r"));
                                }
                            }
                            $upload->setStatut(2);
                            $this->getEm()->flush();
                        } else {
                            $upload->setStatut(2);
                            $this->getEm()->persist($codebarre);
                            $this->getEm()->flush();
                        }

                    } else {
                        $commerce_dup = $this->getEm()->getRepository(Commerce::class)->findOneBy(array('refciale' => $commerce->getRefciale(), 'fournisseurId' => $fournisseur->getId()));
                        if ($commerce_dup !== null) {
                            foreach ($fr_mi_fields as $key => $value) {
                                if ($value !== "refciale") {
                                    $commerce_dup->$value = $commerce->$value;
                                    $date = new \DateTime('now');
                                    $commerce_dup->setUpdatedAt($date);
                                    $article_dup = $this->getEm()->getRepository(Article::class)->findOneBy(array('reffournisseur' => $commerce->getRefciale(), 'fournisseurId' => $fournisseur->getId()));
                                    $code = $trigramme . $commerce->getRefciale();
                                    $article_dup->setCode($code);
                                    $article_dup->setReference($commerce->getRefciale());
                                    $article_dup->setDescription($commerce->getLibelle80());
                                    $article_dup->setFournisseur($fournisseur->getCode());
                                    $article_dup->setMarque($commerce->getMarque());
                                    $article_dup->setEan($commerce->getGtin13());
                                    $article_dup->setTextcourt($commerce->getLibelle240());
                                    $article_dup->setTextlong($commerce->getLibelle240());
                                    $article_dup->setStock(1);
                                    $article_dup->setGarantie($commerce->getDug());
                                    $article_dup->setRefcommande($commerce->getRefciale());
                                    $article_dup->setPrixvente($commerce->getTarif());
                                    $article_dup->setPrixachat($commerce->getTarif());
                                    $article_dup->setReffournisseur($commerce->getRefciale());
                                    $article_dup->setDesignationfr($commerce->getLibelle80());
                                }
                            }
                            $upload->setStatut(2);
                            $this->getEm()->flush();
                        } else {
                            $upload->setStatut(2);
                            $this->getEm()->persist($commerce);
                            $this->getEm()->persist($article);
                            $this->getEm()->flush();
                        }
                    }

                } catch (\Doctrine\DBAL\DBALException $e) {
                    $isDoublon = true;
                } catch (\Exception $e) {
                    $isError = true;
                }
                $this->getEm()->clear();
            }

            if ($isError) {
                $this->addFlash('warning', "Import terminé, des erreurs ont été rencontré, consulter les logs");
            } elseif ($isDoublon) {
                $this->addFlash('warning', "Import terminé, des doublons ont été détecté et mis à jour");
            } else {
                $this->addFlash('success', "Import terminé avec succès");
            }

            if ($upload->getIsMedia()) {
                return $this->redirectToRoute('media_index', ['fournisseurId' => $fournisseur->getId()]);
            } elseif ($upload->getIsGoogleShopping()) {
                return $this->redirectToRoute('codebarre_show', ['fournisseurId' => $fournisseur->getId()]);
            } else {
                return $this->redirectToRoute('commerce_index', ['fournisseurId' => $fournisseur->getId()]);
            }
        }

        return $this->render('upload/check.html.twig', [
            'fournisseur' => $fournisseur,
            'mi' => $fr_mi,
            'dataArray' => $dataArray,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/upload/show", name="upload_show", methods="GET")
     */
    public function show(UploadRepository $uploadRepository): Response
    {
        return $this->render('upload/show.html.twig', ['uploads' => $uploadRepository->findAll()]);
    }

    /**
     * @Route("upload/{id}/delete", name="upload_delete", methods="DELETE")
     */
    public function delete(Request $request, Upload $upload): Response
    {
        if ($this->isCsrfTokenValid('delete' . $upload->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($upload);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "Oups!, Impossible de supprimer cette ligne");
            }
        }

        return $this->redirectToRoute('upload_index');
    }

    /**
     * @Route("uploads/{tarifName}", name="download_file")
     */
    public function downloadFile(Upload $upload, DownloadHandler $downloadHandler): Response
    {
        return $downloadHandler->downloadObject($upload, $fileField = 'tarifFile', $objectClass = null, $fileName = null, $forceDownload = false);
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
