<?php

namespace App\Form;

use App\Entity\Tarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('refFournisseur')
            ->add('refCommande')
            ->add('reference')
            ->add('prixAchat')
            ->add('prixVente')
            ->add('remise')
            ->add('marque')
            ->add('groupeRemise')
            ->add('poids')
            ->add('codebarre')
            ->add('hscode')
            ->add('paysOrigine')            
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tarif::class,
        ]);
    }
}
