<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration('Titre', 'Titre de l\'annonce')
            )
            ->add(
                'slug', 
                TextType::class, 
                $this->getConfiguration('URL', 'url (automatique)', false)
            )
            ->add(
                'coverImage', 
                UrlType::class, 
                $this->getConfiguration('URL Image', 'URL de l\'image')
            )
            ->add(
                'introduction', 
                TextType::class, 
                $this->getConfiguration('Introduction', 'Description globale de l\'annonce')
            )
            ->add(
                'description', 
                TextareaType::class, 
                $this->getConfiguration('Description', 'Détails sur l\'annonce')
            )
            ->add(
                'rooms', 
                IntegerType::class, 
                $this->getConfiguration('Nombre de chambre', 'Nombre de chambre')
            )
            ->add(
                'price', 
                MoneyType::class, 
                $this->getConfiguration('Prix', 'Prix par nuit')
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type'   => ImageType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
