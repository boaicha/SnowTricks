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
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Valid;

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
//            ->add("videos", CollectionType::class, [
//                "entry_type" => VideoType::class,
//                'entry_options' => ['label' => false],
//                'allow_add' => true,
//                "required" => false,
//                "label" => false,
//                'constraints' => [
//                    new Valid(),
//                ],
//            ])
            ->add("videos", CollectionType::class, [
                "entry_type" => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                "required" => false,
                "label" => false

            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'enctype' => 'multipart/form-data',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'validation_groups' => ['creation']
        ]);
    }
}
