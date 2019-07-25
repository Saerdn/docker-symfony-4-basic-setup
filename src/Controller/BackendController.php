<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    /**
     * @Route("/backend", name="backend_index")
     */
    public function index()
    {
        return $this->render("backend/index.html.twig");
    }
    /**
     * @Route("/backend/anleitung", name="backend_intro")
     */
    public function intro()
    {
        return $this->render("backend/intro.html.twig");
    }
}