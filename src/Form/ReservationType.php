<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le Prenom ne peut pas être vide.',
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('telephone', TelType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le numéro de téléphone ne peut pas être vide.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de téléphone doit comporter des chiffres.',
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'adresse email ne peut pas être vide.',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse email n\'est pas valide.',
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le message ne peut pas être vide.',
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('residence')
            ->add('appartement');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
