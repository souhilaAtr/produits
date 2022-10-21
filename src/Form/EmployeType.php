<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    "Femme" => "Femme",
                    "Homme" => "Homme"
                ]
            ])
            // totoType,[]
            ->add('workedAt', DateType::class,["widget"=>"single_text"])
            ->add('salaire')
            ->add('image', FileType::class, ['label' => "image(JPG,PNG)", 'data_class' => null, "required" => false])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
