<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AddAutoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', null, array(
                'attr' => array('autofocus' => true),
                'label' => 'Brand',
            ))
            ->add('model')
            ->add('price')
            ->add('year')
            ->add('description', TextareaType::class, array(
                'attr' => array('rows' => 20),
                'label' => 'Description',
            ))
            ->add('region', EntityType::class, array(
                'class' => 'AppBundle:Region',
                'choice_label' => 'name',
                'label' => 'Choose your region',
            ))
            ->add('car_condition', EntityType::class, array(
                'class' => 'AppBundle:Car_condition',
                'choice_label' => 'name',
            ))
            ->add('image', FileType::class, array('label' => 'Image (jpg, png file)'))
            ->add('save', SubmitType::class, array('label' => 'Create auto'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Auto',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_auto';
    }

}