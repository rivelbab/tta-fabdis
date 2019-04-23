<?php
namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\GroupeRemise;
use App\Entity\Article;
use App\Entity\Commerce;
use App\Entity\Media;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommerceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/accueil/{fournisseurId}", name="article_index", methods="GET|POST")
     */
    public function index(Request $request, ArticleRepository $articleRepository, CommerceRepository $commerceRepository, $fournisseurId = null): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $articles = null;
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
            $articles = $articleRepository->findByFournisseurId($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('article/index.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
            'fournisseur' => $selectedFr,
        ]);
    }

    /**
     * @Route("/article/{id}/delete/{slug}", name="article_delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article, $slug = null): Response
    {
        $fournisseurId = null;

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $fournisseurId = $article->getFournisseurId();
            try {
                $em->remove($article);
                $em->flush();
            } catch (\Exception $e) {
                //$this->addFlash('danger',"Oups!, Impossible de supprimer cet article.");
            }
        }
        if (null !== $slug) {
            return $this->redirectToRoute($slug, ['fournisseurId' => $fournisseurId]);
        } else {
            return $this->redirectToRoute('article_index', ['fournisseurId' => $fournisseurId]);
        }
    }

    /**
     * @Route("/article/maj/code", name="update_code", methods="GET|POST")
     */
    public function majCode(Request $request): Response
    {
        $code = $request->request->get('code');
        $idarticle = $request->request->get('id');

        if (isset($code)) {
            $this->getEm()->getRepository(Article::class)->updateCode($code, $idarticle);
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
