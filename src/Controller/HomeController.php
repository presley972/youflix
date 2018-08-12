<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @param UserRepository $userRepository
     * @param VideoRepository $videoRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserRepository $userRepository, VideoRepository $videoRepository, Request $request,
                          EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findAll();
        $video = $videoRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users'=>$user,
            'videos'=> $video,
        ]);
    }


}

// Controller de la home elle nous permets de listé toutes les utilisateur et leurs videos