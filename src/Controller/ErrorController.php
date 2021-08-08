<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ErrorController extends AbstractController
{
    public function __invoke($exception)
    {
        
        return $this->render('error/error'. $exception->getStatusCode() .'.html.twig',
            [
                'message' => $exception->getMessage(),
                'code' => $exception->getStatusCode(),
            ]
        );
    }
}