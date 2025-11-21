<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\AgentTicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/agent/ticket', name: 'agent_ticket_')]
final class AgentTicketController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, TicketRepository $repo): Response
    {
        $statut = $request->query->get('statut');
        $responsable = $request->query->get('responsable');
        $totalTickets = $repo->count([]);
        
        // Normaliser les valeurs Tous
        if ($statut === 'Tous') {
            $statut = null;
        }

        if ($responsable === 'Tous') {
            $responsable = null;
        }

        $tickets = $repo->findByFilters($statut, $responsable);

        // remplir le menu déroulant des responsables
        $responsables = $repo->findDistinctResponsables();

        return $this->render('agent_ticket/index.html.twig', [
            'tickets' => $tickets,
            'statut' => $statut,
            'responsable' => $responsable,
            'responsables' => $responsables,
            'totalTickets' => $totalTickets,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Ticket $ticket, Request $request, EntityManagerInterface $em): Response
    {
        // Création du formulaire agent ( avec uniquement statut )
        $form = $this->createForm(AgentTicketType::class, $ticket);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // mise à jour automatique de la date de cloture si le choix est 'Fermé'
            if ($ticket->getStatut() === 'Fermé') {
                $ticket->setDateCloture(new \DateTime());
            } else {
                // cas où le ticket était fermé mais on le modifie
                $ticket->setDateCloture(null);
            }

            $em->flush();

            $this->addFlash('success', 'Statut du ticket mis à jour !');

            return $this->redirectToRoute('agent_ticket_index');
        }

        return $this->render('agent_ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
}
