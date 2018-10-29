<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{slug<^$|[a-z0-9](-?[a-z0-9])*$>}", name="blog", defaults={"slug"="Article sans Titre"})
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
}
