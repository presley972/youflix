<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, UserRepository $userRepository)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', array(
                'controller_name' => 'UserController',
                'form' => $form->createView(),
                'user' => $users
        )
        );
    }

//Partie pour voir toute les videos d'un utilisateur
    /**
     * @Route("/user/{byFirstname}", name="user_firstname")
     * @ParamConverter("user", options={"mapping"={"byFirstname"="firstname"}})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function firstname(Request $request, UserRepository $userRepository, User $user){

        return $this->render('user/user.html.twig',[
                'user'=> $user,
            ]

        );

    }

    //Partie pour suprimer un utilisateur par un admin
    /**
     * @Route("/user/remove/{id}", name="user_remove")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(User $user, EntityManagerInterface $entityManager)
    {
        $videos = $user->getVideos();
        foreach ($videos as $video){
            $video->setUser(null);
        }

        $entityManager->remove($user);
        $entityManager ->flush();
        return $this->redirectToRoute('home');
    }
}
