<?php

namespace App\Form;

use App\Entity\Octave;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OctaveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('refFournisseur')
            ->add('refCommande')
            ->add('reference')
            ->add('prixAchat')
            ->add('prixVente')
            ->add('remise')
            ->add('marque')
            ->add('poids')
            ->add('codebarre')
            ->add('hscode')
            ->add('commentaire')
            ->add('libelle')
            ->add('etat', ChoiceType::class, array(
                'choices'  => array(
                    "Actif" => 0,
                    "NPU" => 1                    
                )
            ))
            ->add('isSpecial', ChoiceType::class, array(
                'choices'  => array(
                    "NON" => 0,
                    "OUI" => 1
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Octave::class,
        ]);
    }
}
