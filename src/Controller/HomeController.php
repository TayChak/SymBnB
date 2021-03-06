<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{   
    /** 
     * @Route("/", name="homepage")
     * 
     * @param AdRepository $adRepo
     * @param UserRepository $userRepo
     * 
     * @return Response
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo): Response
    {
        return $this->render('home.html.twig',
            [
                'ads'   => $adRepo->findBestAds(3),
                'users' => $userRepo->findBestUsers(2),
            ]
        );
    }

}
?>