<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{
    /**
     * @Route("/video", name="video")
     * @param Request $request
     * @param VideoRepository $videoRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function videoRegister(Request $request, VideoRepository $videoRepository)
    {

        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();

        }


        $videos = $videoRepository->findAll();

        return $this->render('video/index.html.twig', array(

            'form'=>$form->createView(),
            'controller_name'=>"presley",
            'video' => $videos
        ));
    }
}
