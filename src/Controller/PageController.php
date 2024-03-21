<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('page/home.html.twig');
    }

    /**
     * @Route("/query/", name="query")
     */
    public function query(): Response
    {
        return $this->render('page/query.html.twig');
    }

    /**
     * @Route("/whatis/", name="whatis")
     */
    public function whatis(): Response
    {
        return $this->render('page/whatis.html.twig');
    }

    /**
     * @Route("/history/", name="history")
     */
    public function history(): Response
    {
        return $this->render('page/history.html.twig');
    }

    /**
     * @Route("/contact/", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig');
    }

    /**
     * @Route("/literature/", name="literature")
     */
    public function literature(): Response
    {
        return $this->render('page/literature.html.twig');
    }
}
