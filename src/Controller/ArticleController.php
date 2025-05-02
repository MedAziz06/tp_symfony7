<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\PropertySearch; // Ajouté
use App\Entity\CategorySearch; // Ajouté
use App\Entity\PriceSearch; // Ajouté
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\PropertySearchType; // Ajouté
use App\Form\CategorySearchType; // Ajouté
use App\Form\PriceSearchType; // Ajouté
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_list')]
    public function home(Request $request, ArticleRepository $articleRepository): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
        
        $articles = [];
        
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $propertySearch->getNom();
            $articles = $nom 
                ? $articleRepository->findBy(['nom' => $nom])
                : $articleRepository->findAll();
        }
        
        return $this->render('articles/index.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles
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
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/newCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    

    #[Route('/art_cat', name: 'article_par_cat')]
    public function articlesParCategorie(Request $request, ArticleRepository $articleRepository): Response
    {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class, $categorySearch);
        $form->handleRequest($request);
    
        // Initialisez avec un tableau vide au lieu de tous les articles
        $articles = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();
            
            if ($category) {
                $articles = $articleRepository->findBy(['category' => $category]);
                // Alternative si vous voulez utiliser la relation directe :
                // $articles = $category->getArticles()->toArray();
            } else {
                $articles = $articleRepository->findAll();
            }
        }
    
        return $this->render('articles/articlesParCategorie.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles
        ]);
    }
    #[Route('/art_prix', name: 'article_par_prix')]
public function articlesParPrix(Request $request, ArticleRepository $articleRepository): Response
{
    $priceSearch = new PriceSearch();
    $form = $this->createForm(PriceSearchType::class, $priceSearch);
    $form->handleRequest($request);

    // Debug: affichez les valeurs reçues
    dump($priceSearch);

    $articles = [];
    
    if ($form->isSubmitted() && $form->isValid()) {
        $minPrice = $priceSearch->getMinPrice();
        $maxPrice = $priceSearch->getMaxPrice();
        
        $articles = $articleRepository->findByPriceRange($minPrice, $maxPrice);
        
        // Debug: affichez les résultats
        dump($articles);
    }

    return $this->render('articles/articlesParPrix.html.twig', [
        'form' => $form->createView(),
        'articles' => $articles
    ]);
}
    public function index()
{
    $response = $this->render(...);
    // dump($data); // Uniquement si nécessaire
    return $response;
}
}