<?php

namespace App\Controller;


use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{


    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new Users();

        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_movie_login');

        }


        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function login(){
        return $this->render('security/login.html.twig');
    }

    public function logout(){

    }
}
