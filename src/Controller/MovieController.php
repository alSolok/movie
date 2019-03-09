<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\CommentType;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MovieController extends AbstractController
{

    public function index(MovieRepository $repo)
    {

       // $repo = $this->getDoctrine()->getRepository(Movie::class);

        $movies = $repo->findAll();

        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
            'movies' => $movies
        ]);

    }

    public function home()
    {
        return $this->render('movie/home.html.twig',[
            'title' => "Bienvenue sur ma page Movie"
        ]);
    }

    public function form(Movie $movie = null, Request $request, ObjectManager $manager)
    {
        if(!$movie){

            $movie = new Movie();

        }

        $form = $this->createForm(MovieType::class, $movie);

        $form ->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            if(!$movie->getId()){

                $movie ->setCreatedAt(new \DateTime());
            }

            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/create.html.twig',[
            'formMovie' => $form->createView(),
            'editMovie' => $movie->getId() !== null
        ]);
    }

    public function show(Movie $movie, Request $request, ObjectManager $manager)
    {

//        $repo = $this->getDoctrine()->getRepository(Movie::class);
//
//        $movie = $repo->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setCreatedAt(new \DateTime())
                    ->setMovie($movie);

            $manager->persist($comment);
            $manager->flush();


            return $this->redirectToRoute('app_movie_show', [ 'id' => $movie->getId()]);
        }

        return $this->render('movie/show.html.twig',[
            'movie' => $movie,
            'commentForm' => $form->createView()
        ]);
    }
}
