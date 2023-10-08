<?php

namespace App\Form;

// src/Form/SearchFormType.php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'Search',
                'attr' => [
                    'placeholder' => 'Search',
                    'class' => 'form-control',
                ],
            ]);
    }
}
