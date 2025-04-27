<?php
// src/Controller/ArticleController.php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType; // ✅ Ajouté ici !
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_list')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'edit_article', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/delete/{id}', name: 'delete_article', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_list');
    }

    #[Route('/category/new', name: 'new_category', methods: ['GET', 'POST'])]
    public function newCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category); // ✅ nécessite le use
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('article_list'); // ou autre
        }

        return $this->render('articles/newCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
