<?php

namespace App\Controller;

use App\Entity\Responsable;
use App\Form\ResponsableType;
use App\Repository\ResponsableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/responsable')]
class AdminResponsableController extends AbstractController
{
    #[Route('/', name: 'admin_responsable_index')]
    public function index(
        ResponsableRepository $repo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $responsable = new Responsable();

        $form = $this->createForm(ResponsableType::class, $responsable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($responsable);
            $em->flush();

            $this->addFlash('success', 'Responsable ajouté');
            return $this->redirectToRoute('admin_responsable_index');
        }

        return $this->render('admin/responsable/index.html.twig', [
            'responsables' => $repo->findAll(),
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_responsable_edit')]
    public function edit(
        Responsable $responsable,
        ResponsableRepository $repo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $form = $this->createForm(ResponsableType::class, $responsable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Responsable modifié');
            return $this->redirectToRoute('admin_responsable_index');
        }

        return $this->render('admin/responsable/index.html.twig', [
            'responsables' => $repo->findAll(),
            'form' => $form->createView(),
            'editMode' => true,
            'current' => $responsable
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_responsable_delete', requirements: ['id' => '\d+'])]
    public function delete(
        Responsable $responsable,
        EntityManagerInterface $em
    ) {
        // si le responsable est lié à des tickets en cours
        if (!$responsable->getTickets()->isEmpty()) {
            $this->addFlash(
                'danger',
                'Impossible, au moins un ticket en cours est assigné à ce responsable'
            );

            return $this->redirectToRoute('admin_responsable_index');
        }

        // sinon
        $em->remove($responsable);
        $em->flush();

        $this->addFlash('success', 'Responsable supprimé');
        return $this->redirectToRoute('admin_responsable_index');
    }
}