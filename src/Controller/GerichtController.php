<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Gericht;
use App\Form\GerichtType;
use App\Repository\GerichtRepository;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/gericht', name: 'gericht.')]
class GerichtController extends AbstractController {



    #[Route('/', name: 'bearbeiten')]
    public function index(GerichtRepository $gr) {
        $gerichte = $gr->findAll();

        return $this->render('gericht/index.html.twig', [
            'gerichte' => $gerichte
        ]);
    }

    #[Route('/anlegen', name: 'anlegen')]
    public function anlegen(Request $request, ManagerRegistry $doctrine)  {
        $gericht = new Gericht();

        //Formular
        $form = $this->createForm(GerichtType::class, $gericht);
        $form ->handleRequest($request);

        //Speichern mit Entity Manager und Weiterleiten
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($gericht);
            $em->flush();

            return $this->redirect($this->generateUrl('gericht.bearbeiten'));
        }

        return $this->render('gericht/anlegen.html.twig', [
            'anlegenForm' => $form->createView()
        ]);

    }


    #[Route('/entfernen/{id}', name: 'entfernen')]
    public function entfernen($id, GerichtRepository $gr, ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $gericht = $gr->find($id);
        $em->remove($gericht);
        $em->flush();

        //message
        $this->addFlash('erfolg', 'Gericht wurde erfolgreich entfernt');

        return $this->redirect($this->generateUrl('gericht.bearbeiten'));
    }

}
