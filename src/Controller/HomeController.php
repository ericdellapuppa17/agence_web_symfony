<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TicketPublicType;
use App\Entity\Ticket;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // formulaire de saisie de ticket client
        $ticket = new Ticket();

        $form = $this->createForm(TicketPublicType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Date d'ouverture et statut 'Ouvert' déjà géré dans __construct
            $em->persist($ticket);
            $em->flush();

            $this->addFlash('success', 'Votre demande a bien été enregistrée !');

            return $this->redirectToRoute('home');
        } else {
            dump($form->getErrors(true, false));
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            // 'form' => $form,
        ]);
    }
}
