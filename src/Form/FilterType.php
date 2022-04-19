<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false
            ])
            ->add('dateOrder', ChoiceType::class, [
                'choices' => [
                    'Croissant' => true,
                    'Décroissant' => false
                ],
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary m-4']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
