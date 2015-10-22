<?php

namespace Svd\MediaBundle\Transformer;

/**
 * Transformer
 */
class ImageTransformer
{
    /** @var string */
    protected $folder;

    /** @var boolean */
    protected $default;

    /** @var float */
    protected $ratio;

    /** @var integer */
    protected $size;

    public function __construct($folder, $isDefault, $ratio = null, $size = null)
    {
        $this->folder = $folder;
        $this->default = $isDefault;
        $this->ratio = $ratio;
        $this->size = $size;
    }

    /**
     * Is default
     *
     * @return bool
     */
    public function isDefault()
    {
        return $this->default;
    }
}
