<?php

namespace Svd\MediaBundle\Form\Type;

use InvalidArgumentException;
use Svd\MediaBundle\Form\DataTransformer\IdToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
}
