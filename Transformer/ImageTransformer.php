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

    /**
     * Construct
     *
     * @param string  $folder
     * @param boolean $isDefault
     * @param float   $ratio
     * @param integer $size
     */
    public function __construct($folder, $isDefault, $ratio = null, $size = null)
    {
        $this->folder = $folder;
        $this->default = $isDefault;
        $this->ratio = $ratio;
        $this->size = $size;
    }

    /**
     * Get folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
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

    /**
     * Get ratio
     *
     * @return float|null
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Get size
     *
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }
}
