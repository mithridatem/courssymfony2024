<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'attr'=>['class'=>''],
                'required'=>true,
                'label'=> 'Saisir votre prénom :',
                'label_attr'=>['class'=>'']
                ]
            )
            ->add('lastname', TextType::class,[
                'attr'=>['class'=>''],
                'required'=>true,
                'label'=> 'Saisir votre nom :',
                'label_attr'=>['class'=>'']
                ]
            )
            ->add('email', EmailType::class,[
                'attr'=>['class'=>''],
                'required'=>true,
                'label'=> 'Saisir votre email :',
                'label_attr'=>['class'=>'']
                ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :',
                'hash_property_path' => 'password',],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
