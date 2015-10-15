<?php

namespace Svd\MediaBundle\Form\Type;

use InvalidArgumentException;
use Svd\MediaBundle\Form\DataTransformer\IdToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type
 */
class MediaType extends AbstractType
{
    /** @var IdToFileTransformer */
    protected $idToFileTransformer;

    /**
     * Set id to file transformer
     *
     * @param IdToFileTransformer $idToFileTransformer Id to file transformer
     *
     * @return self
     */
    public function setIdToFileTransformer(IdToFileTransformer $idToFileTransformer)
    {
        $this->idToFileTransformer = $idToFileTransformer;

        return $this;
    }

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder builder
     * @param array                $options options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->idToFileTransformer);
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            dump($event); die;
        });
    }

    /**
     * Build view
     *
     * @param FormView      $view    view
     * @param FormInterface $form    form
     * @param array         $options options
     *
     * @throws InvalidArgumentException
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!empty($options['ratio']) && !preg_match('#^\d+\:\d+$#', $options['ratio'])) {
            throw new InvalidArgumentException('Bad format of ratio option. I should be i.e 16:9.');
        }
        $view->vars['ratio'] = $options['ratio'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'ratio' => null,
        ));
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'media';
    }
}
