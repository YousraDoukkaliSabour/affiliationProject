<?php

namespace App\Form;

use App\Entity\AffiliateLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AffiliateLinkType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('plan1', NumberType::class)
            ->add('plan2', NumberType::class)
            ->add('plan3', NumberType::class)
            ->add('plan4', NumberType::class)
            ->add('plan5', NumberType::class)
            ->add('plan6', NumberType::class)
            ->add('plan7', NumberType::class)
            ->add('plan8', NumberType::class);




        }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AffiliateLink::class,
        ]);
    }

}