<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $posts = $postRepository->findAll();
        // echo "<pre>";
        // var_dump($post);
        // echo "</pre>";

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
    */
    public function create(Request $request) {
        $post = new Post();

        // $post->setTitle("Hi This is new title");

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();

            $file = $request->files->get('post')['attachment'];

            if ($file) {

                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
                
                $file->move(
                        $this->getParameter('upload_dir'),
                        $filename
                );

                $post->setImage($filename);
                # code...
                $em->persist($post);

                $em->flush();
            }
            // var_dump($post);
            
            return $this->redirect($this->generateUrl('post.index'));
        }

        

        // return new Response('Post was created');
        
        return $this->render('post/create.html.twig', [
            'form'  =>  $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id?}", name="show")
     * @param Post $post
     * @return Response
    */
    public function show(Post $post) {
        // $posts = $postRepository->find($id);
        // dump($post); die;
        return $this->render('post/show.html.twig', [
            'post'  =>  $post
        ]);
    }

       /**
     * @Route("/delete/{id?}", name="delete")
     * @param Post $post
     * @return Response
    */
    public function delete(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post was removed!');
        return $this->redirect($this->generateUrl('post.index'));
    }

}
