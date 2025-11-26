<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/statut')]
class AdminStatutController extends AbstractController
{
    #[Route('/', name: 'admin_statut_index')]
    public function index(
        StatutRepository $repo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $statut = new Statut();

        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($statut);
            $em->flush();

            $this->addFlash('success', 'Statut ajouté');
            return $this->redirectToRoute('admin_statut_index');
        }

        return $this->render('admin/statut/index.html.twig', [
            'statuts' => $repo->findAll(),
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_statut_edit')]
    public function edit(
        Statut $statut,
        StatutRepository $repo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Statut modifié');
            return $this->redirectToRoute('admin_statut_index');
        }

        return $this->render('admin/statut/index.html.twig', [
            'statuts' => $repo->findAll(),
            'form' => $form->createView(),
            'editMode' => true,
            'current' => $statut
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_statut_delete', requirements: ['id' => '\d+'])]
    public function delete(
        Statut $statut,
        EntityManagerInterface $em
    ) {
        // si le statut est lié à des tickets en cours
        if (!$statut->getTickets()->isEmpty()) {
            $this->addFlash(
                'danger',
                'Impossible, au moins un ticket en cours possède ce statut'
            );

            return $this->redirectToRoute('admin_statut_index');
        }

        // sinon
        $em->remove($statut);
        $em->flush();

        $this->addFlash('success', 'Statut supprimé');
        return $this->redirectToRoute('admin_statut_index');
    }
}