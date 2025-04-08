<?php

namespace App\Form;

use App\Entity\TrainingSession;
use App\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('training', EntityType::class, [
                'class' => Training::class,
                'choice_label' => 'name',
                'label' => 'Kies een oefening',
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Datum en tijd',
            ])
            ->add('sets', IntegerType::class, ['label' => 'Sets'])
            ->add('reps', IntegerType::class, ['label' => 'Reps'])
            ->add('weight', IntegerType::class, ['label' => 'Gewicht (kg)']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingSession::class,
        ]);
    }
}

