<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use App\Repository\StatutRepository;
use App\Repository\ResponsableRepository;
use App\Form\AdminTicketType;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/ticket')]
final class AdminTicketController extends AbstractController
{
    #[Route('/', name: 'admin_ticket_index')]
    public function index(
        Request $request,
        TicketRepository $ticketRepository,
        StatutRepository $statutRepo,
        ResponsableRepository $responsableRepo
        ): Response
    {
        $statutId = $request->query->get('statut');
        $responsableId = $request->query->get('responsable');

        // Normalisation
        if ($statutId === 'Tous') {
            $statutId = null;
        }
        if ($responsableId === 'Tous') {
            $responsableId = null;
        }

        // filtrage
        $tickets = $ticketRepository->findByFilters(
            $statutId ? (int)$statutId : null,
            $responsableId ? (int)$responsableId : null
        );

        // comptage des tickets
        $totalTickets = $ticketRepository->count([]);

        return $this->render('admin/ticket/index.html.twig', [
            'tickets' => $tickets,
            'statuts' => $statutRepo->findAll(),
            'responsables' => $responsableRepo->findAll(),
            'statut' => $statutId,
            'responsable' => $responsableId,
            'totalTickets' => $totalTickets,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_ticket_edit')]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdminTicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //date de cloture si statut = 'Fermé'
            if ($ticket->getStatut() && $ticket->getStatut()->getLibelle() === 'Fermé') {
                if ($ticket->getDateCloture() === null) {
                    $ticket->setDateCloture(new \DateTime());
                }
            }

            // inversement
            if ($ticket->getStatut() && $ticket->getStatut()->getLibelle() !== 'Fermé') {
                $ticket->setDateCloture(null);
            }
            
            $em->flush();

            $this->addFlash('success', 'Ticket mis à jour !');
            return $this->redirectToRoute(('admin_ticket_index'));
        }

        return $this->render('admin/ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
}
