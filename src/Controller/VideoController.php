<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{
    //Partie pour enregistrer une video
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
            $video->setUser($this->getUser());
            $video->setCreateAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirectToRoute('home');

        }


        $videos = $videoRepository->findAll();

        return $this->render('video/index.html.twig', array(

            'form'=>$form->createView(),
            'controller_name'=>"presley",
            'video' =>$videos
        ));
    }

//Partie pour supprimer une video
    /**
     * @Route("/video/remove/{id}", name="video_remove")
     * @ParamConverter("video", options={"mapping"={"id"="id"}})
     * @param Video $video
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function remove(Video $video, EntityManagerInterface $entityManager){

        if ($video->getUser() == $this->getUser() or $this->isGranted('ROLE_ADMIN')) {
            $entityManager->remove($video);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        else{
            $this->addFlash('alert', 'Désolé mais vous ne pouvez pas suprimer cette video!');
            return $this->redirectToRoute('home');
        }

    }

}
