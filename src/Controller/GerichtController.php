<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Gericht;


#[Route('/gericht', name: 'gericht.')]
class GerichtController extends AbstractController
{
    #[Route('/', name: 'bearbeiten')]
    public function index(): Response
    {
        return $this->render('gericht/index.html.twig', [
            'controller_name' => 'GerichtController',
        ]);
    }

    #[Route('/anlegen', name: 'anlegen')]
    public function anlegen(Request $request)  {
        $gericht = new Gericht();
        $gericht ->setName('Pizza');

        $em = $this->getDoctrine()->getManager();
        $em->persists($gericht);
        $em->flush();

        return new Response("Gericht wurde angelegt...");

    }

}
