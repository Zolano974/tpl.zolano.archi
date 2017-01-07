<?php

namespace FirstBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('number', IntegerType::class)
            ->add('field', EntityType::class, array(
                                            'class'         =>'FirstBundle:Field',
                                            'choice_label'  => 'name',
                                            'multiple'      => false,
                                            'expanded'      => false,
                                            //pour personnaliser la liste
//                                            'querybuilder'  => function(FirstBundle\Repository\FieldRepository $r){
//                                                return $r->getCustomList();
//                                            },                
            ));
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FirstBundle\Entity\Item'
        ));
    }
}
