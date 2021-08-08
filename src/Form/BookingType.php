<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                DateType::class,
                [
                    'label' => 'Date d\'arrivée',
                    'widget' => 'single_text',
                   // 'html5'  => false,
                    'attr' => [
                     //   'class' => 'js-datepicker',
                        'placeholder' => 'Date à laquelle vous comptez arriver',
                    ],
                ]
            )
            ->add(
                'endDate',
                DateType::class,
                [
                    'label' => 'Date de départ',
                    'widget' => 'single_text',
                    //'html5'  => false,
                    'attr' => [
                        //'class' => 'js-datepicker',
                        'placeholder' => 'Date à laquelle vous quittez les lieux',
                    ],
                ]
            )
            ->add(
                'comment',
                TextType::class,
                $this->getConfiguration(' ', 'Rédigez votre commentaire ici !!', false)
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
