<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /** 
     * @Route("/hello/{prenom}", name="hello")
     */
    public function hello($prenom = "World !") {
        return new Response("Hello " . $prenom);
    }
    
    
    /** 
     * @Route("/", name="homepage")
     */
    public function home() {
        $prenoms = ['Tayeb' => 30 , 'Ayoub' => 22, 'Tarak' => 27];

        return $this->render('home.html.twig',
            [
                'title'   => 'Bonjour à tous !',
                'age'     =>  12,
                'prenoms' => $prenoms
            ]
        );
    }

}
?>