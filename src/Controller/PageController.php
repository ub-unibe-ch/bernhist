<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    public function home(): Response
    {
        return $this->render('page/home.html.twig');
    }

    #[Route(path: '/whatis/', name: 'whatis')]
    public function whatis(): Response
    {
        return $this->render('page/whatis.html.twig');
    }

    #[Route(path: '/history/', name: 'history')]
    public function history(): Response
    {
        return $this->render('page/history.html.twig');
    }

    #[Route(path: '/contact/', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig');
    }

    #[Route(path: '/literature/', name: 'literature')]
    public function literature(): Response
    {
        return $this->render('page/literature.html.twig');
    }
}
