<?php

namespace App\Form;

use App\Entity\Anuncios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo' )
            //->add('likes')
            ->add('fotos', FileType::class,[
                'label' => 'Selecciona una imagen','mapped' => false,'required' => false])
            //->add('fecha')
            ->add('contenido', TextareaType::class)
            //->add('usuario')
            ->add('guardar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Anuncios::class,
        ]);
    }
}
