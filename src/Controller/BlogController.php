<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/show/{slug<^$|[a-z0-9](-?[a-z0-9])*$>}", name="blog_show", defaults={"slug"="Article sans Titre"})
     */
    public function show($slug)
    {
        if (empty($slug))
            $finalSlug = "Article Sans Titre";
        else {
            $replace = str_replace("-", " ", $slug);
            $finalSlug = ucwords($replace);
        }

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            "Titre" => $finalSlug,
        ]);
    }

    /**
     * @Route("/blog", name="blog_showcategory")
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('blog/show.html.twig', [
            'categories' => $categories
        ]);

    }
}
