<?php

namespace FFN\MonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ControlHeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('headerKey')
            ->add('headerValue')
            ->add('type')
            //->add('control')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FFN\MonBundle\Entity\ControlHeader'
        ));
    }

    public function getName()
    {
        return 'ffn_monbundle_controlheadertype';
    }
}
