<?php
namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Commerce;
use App\Form\CommerceType;
use App\Repository\CommerceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommerceController extends AbstractController
{
    /**
     * @Route("/commerce/accueil/{fournisseurId}", name="commerce_index", methods="GET|POST")
     */
    public function index(Request $request, CommerceRepository $commerceRepository, $fournisseurId = null): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $commerce = null;
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
            $commerce = $commerceRepository->findByFournisseurId($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('commerce/index.html.twig', [
            'form' => $form->createView(),
            'commerces' => $commerce,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/commerce/{id}/delete/{slug}", name="commerce_delete", methods="DELETE")
     */
    public function delete(Request $request, Commerce $commerce, $slug = null): Response
    {
        $fournisseurId = null;

        if ($this->isCsrfTokenValid('delete' . $commerce->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $fournisseurId = $commerce->getFournisseurId();
            try {
                $em->remove($commerce);
                $em->flush();
            } catch (\Exception $e) {
                //$this->addFlash('danger',"Oups!, Impossible de supprimer cet article.");
            }
        }
        if (null !== $slug) {
            return $this->redirectToRoute($slug, ['fournisseurId' => $fournisseurId]);
        } else {
            return $this->redirectToRoute('commerce_index', ['fournisseurId' => $fournisseurId]);
        }
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
