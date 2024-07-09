<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Interprete;
use App\Entity\Musique;
use App\Entity\Style;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('temps')
            ->add('interprete', EntityType::class, [
                'class' => Interprete::class,
                'choice_label' => 'id',
            ])
            ->add('album', EntityType::class, [
                'class' => Album::class,
                'choice_label' => 'id',
            ])
            ->add('styles', EntityType::class, [
                'class' => Style::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musique::class,
        ]);
    }
}
