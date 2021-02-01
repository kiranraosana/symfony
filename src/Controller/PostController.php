<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/post", name="post.")
*/
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        $post = $postRepository->findAll();
        // echo "<pre>";
        // var_dump($post);
        // echo "</pre>";

        return $this->render('post/index.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
    */

    public function create(Request $request) {
        $post = new Post();

        $post->setTitle("Hi This is new title");

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);

        $em->flush();

        return new Response('Post was created');
    }
}
