<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Statut;
use App\Entity\Responsable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
                'choice_label' => 'libelle',
                'label' => 'Statut',
                'placeholder' => 'Choisir un statut',
            ])
            ->add('responsable', EntityType::class, [
                'class' => Responsable::class,
                'choice_label' => 'nom',
                'label' => 'Responsable',
                'placeholder' => 'Aucun responsable',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
