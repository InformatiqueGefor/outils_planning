<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('start',DateTimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text',            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
                'input' => 'datetime_immutable', 
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('end',DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
                'input' => 'datetime_immutable', 
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('session', EntityType::class, [
                'class' => Session::class,
                'label' => "Session",
                'choice_label' =>'name',
                'multiple' => false,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
