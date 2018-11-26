<?php
/**
 * Blog controller file
 *
 * PHP Version 7.2
 *
 * @category Controller
 * @package  Controller
 * @author   Gaëtan Rolé-Dubruille <gaetan@wildcodeschool.fr>
 */
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Blog controller.
 *
 * @Route("blog")
 *
 * @category Controller
 * @package  Controller
 * @author   Gaëtan Rolé-Dubruille <gaetan@wildcodeschool.fr>
 */
class BlogController extends AbstractController
{
    /**
     * Show all row from article's entity
     *
     * @Route("/", name="blog_index")
     * @return     Response A response instance
     */
    public function index()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * Show a formatted argument
     *
     * @param string $slug The slugger
     *
     * @Route("/{slug<^[a-z0-9-]*$>}",
     *     name="blog_show")
     * @return                         Response A response instance
     */
    public function show($slug): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException(
                'No slug has been sent to find article in article\'s table.'
            );
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'slug' => $slug,
                'article' => $article
            ]
        );
    }

    /**
     * Show articles according to categories
     *
     * @Route("/category/{category}", name="blog_show_category")
     *
     * @ParamConverter("category", options={"mapping": {"category": "name"}})
     * @return                     Response A response instance
     */
    public function showByCategory(Category $category)
    {
        if (!$category) {
            return $this->redirectToRoute("blog_index");
        }

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found for '.$category->getName().'.'
            );
        }

        return $this->render(
            'blog/category.html.twig',
            ['category' => $category,
                'articles' => $articles]
        );
    }

    /**
     * Show articles according to categories with bidirectional
     *
     * @Route("/category/{category}/all", name="blog_show_all_category")
     *
     * @ParamConverter("category", options={"mapping": {"category": "name"}})
     * @return                     Response A response instance
     */
    public function showAllByCategory(Category $category)
    {
        return $this->render(
            'blog/category.html.twig',
            ['category' => $category,
                'articles' => $category->getArticles()]
        );
    }
}