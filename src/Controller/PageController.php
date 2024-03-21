<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('page/home.html.twig');
    }

    /**
     * @Route("/query/", name="query")
     */
    public function query(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('page/query.html.twig');
    }

    /**
     * @Route("/whatis/", name="whatis")
     */
    public function whatis(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('page/whatis.html.twig');
    }

    /**
     * @Route("/history/", name="history")
     */
    public function history(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('page/history.html.twig');
    }

    /**
     * @Route("/contact/", name="contact")
     */
    public function contact(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('page/contact.html.twig');
    }

    /**
     * @Route("/literature/", name="literature")
     */
    public function literature(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('page/literature.html.twig');
    }
}
