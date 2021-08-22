<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class BookingType extends ApplicationType
{
    /**
     * @var FrenchToDateTimeTransformer
     */
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                //DateType::class,
                TextType::class,
                [
                    'label' => 'Date d\'arrivée',
                    //'widget' => 'single_text',
                   // 'html5'  => false,
                    'attr' => [
                     //   'class' => 'js-datepicker',
                        'placeholder' => 'Date à laquelle vous comptez arriver',
                    ],
                ]
            )
            ->add(
                'endDate',
                //DateType::class,
                TextType::class,
                [
                    'label' => 'Date de départ',
                    //'widget' => 'single_text',
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

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
