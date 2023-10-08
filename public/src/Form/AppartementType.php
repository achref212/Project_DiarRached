<?php

namespace App\Form;

use App\Entity\Appartement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('etage', ChoiceType::class, [
                'choices' => [
                    'RDC' => 'RDC',
                    'Etage 1' => 'etage 1',
                    'Etage 2' => 'etage 2',
                    'Etage 3' => 'etage 3',
                    'Etage 4' => 'etage 4',
                    'Etage 5' => 'etage 5',
                    'Etage 6' => 'etage 6',
                    'Etage 7' => 'etage 7',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'S+1' => 'S+1',
                    'S+2' => 'S+2',
                    'S+3' => 'S+3',
                    'Commercial' => 'Commercial',
                    'Bureau' => 'Bureau',
                    'Jardin d\'enfant' => 'Jardin d\'enfant',
                ],
            ])
            ->add('surface')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Réservé' => 'Réservé',
                    'Vendu' => 'Vendu',
                ],
            ])
            ->add('imageFile', VichImageType::class)
            ->add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appartement::class,
        ]);
    }
}
