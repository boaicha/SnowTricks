<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Video;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('idCategory', EntityType::class, [
                'class' => Category::class
            ])
            ->add('description')
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class, // Utilisez VideoType pour les vidéos
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                // Autres options pour le champ vidéos si nécessaire
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
