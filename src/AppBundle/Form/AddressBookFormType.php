<?php

declare(strict_types=1);

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\AddressBook;

class AddressBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,

            ])
            ->add('lastName')
            ->add('street')
            ->add('houseNo')
            ->add('zip')
            ->add('city')
            ->add('country')
            ->add('phone')
            ->add('birthDate', DateType::class,[
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false,
            ])
            ->add('email', EmailType::class)
            ->add('picture', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressBook::class
        ]);
    }
}
