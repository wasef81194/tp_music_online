<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Interprete;
use App\Entity\Musique;
use App\Entity\Style;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',  null,[
                'label' => 'Nom',
                'required' => true
            ])
            ->add('temps', TimeType::class,[
                'widget' => 'single_text',
                'label' => 'Temps',
                'required' => true
            ])
            ->add('interprete', EntityType::class, [
                'class' => Interprete::class,
                'choice_label' => 'nom',
                'required' => true
            ])
            ->add('styles', EntityType::class, [
                'class' => Style::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => true
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
