<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Session;
use App\Entity\Formation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, array('label' => 'nom',))
            ->add('start', DateTimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text',
            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
                'input' => 'datetime_immutable', 
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
                'input' => 'datetime_immutable', 
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('theorieHours', NumberType::class, ['label' => 'Heures Théoriques'])
            ->add('stageHours', NumberType::class, ['label' => 'Heures de Stages'])
            ->add('totalHours', NumberType::class, ['label' => 'Nombre d\'heures totales'])
            ->add('minHoursPerDay', NumberType::class, ['label' => 'Heures minimum par jour'])
            ->add('maxHoursPerDay', NumberType::class, ['label' => 'Heures maximum par jour'])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'label' => "Formation",
                'choice_label' =>'name',
                'multiple' => false,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
