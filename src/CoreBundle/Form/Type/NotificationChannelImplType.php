<?php

namespace Flower\CoreBundle\Form\Type;

use Flower\CoreBundle\Entity\NotificationChannelImpl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotificationChannelImplType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', 'choice', array(
                'choices' => array(
                    NotificationChannelImpl::TYPE_EMAIL => NotificationChannelImpl::TYPE_EMAIL,
                    NotificationChannelImpl::TYPE_WEB => NotificationChannelImpl::TYPE_WEB,
                ),
            ));
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\CoreBundle\Entity\NotificationChannelImpl',
            'translation_domain' => 'NotificationChannelImpl',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'notificationchannel';
    }
}
