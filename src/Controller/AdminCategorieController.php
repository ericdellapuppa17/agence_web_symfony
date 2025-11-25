<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categorie')]
class AdminCategorieController extends AbstractController
{
    #[Route('/', name: 'admin_categorie_index')]
    public function index(
        CategorieRepository $repo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $repo->findAll(),
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_categorie_edit')]
    public function edit(
        Categorie $categorie,
        CategorieRepository $repo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $repo->findAll(),
            'form' => $form->createView(),
            'editMode' => true,
            'current' => $categorie
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_categorie_delete', requirements: ['id' => '\d+'])]
    public function delete(
        Categorie $categorie,
        EntityManagerInterface $em
    ) {
        // si la catégorie est liée à des tickets en cours
        if (!$categorie->getTickets()->isEmpty()) {
            $this->addFlash(
                'danger',
                'Impossible, au moins un ticket en cours possède cette catégorie'
            );

            return $this->redirectToRoute('admin_categorie_index');
        }

        // sinon
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée');
        return $this->redirectToRoute('admin_categorie_index');
    }
}