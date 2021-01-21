<?php

namespace App\Controller;


use App\Entity\Blog;
use App\Form\BlogType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findAll();
        //Afficher tout les articles
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/blog/add", name="blog_add")
     */
    public function add(Request $request): Response
    {
        $article = new Blog();
        $form = $this->createForm(BlogType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('blog');
        }

        //Afficher un article specifique
        return $this->render('blog/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show($id): Response
    {
        $article = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->find($id);
        //Afficher un article specifique
        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/blog/delete/{id}", name="blog_delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->find($id);
        
        $entityManager->remove($article);
        $entityManager->flush();
        //Afficher un article specifique
        return $this->redirectToRoute('blog');
    }
}
