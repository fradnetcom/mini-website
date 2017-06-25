<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomePageController extends Controller
{

    public function indexAction()
    {
        return $this->render('full/home_page.html.twig', [

        ]);
    }
}
