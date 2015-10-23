<?php

namespace Svd\MediaBundle\Transformer;

use Imagick;

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
     * @param string  $folder    folder
     * @param boolean $isDefault is default
     * @param float   $ratio     ratio
     * @param integer $size      size
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

    /**
     * Transform
     *
     * @param string $filePath file path
     *
     * @return string
     */
    public function transform($filePath)
    {
        $im = new Imagick($filePath);

        $ret = null;

        if ($this->ratio) {
            $im = $this->cropImage($im);
        }
        if ($this->size) {
            $im = $this->resizeImage($im);
        }

        $ret = $im->getImageBlob();

        return $ret;
    }

    /**
     * Crop image
     *
     * @param Imagick $im im
     *
     * @return Imagick
     */
    public function cropImage(Imagick $im)
    {
        $originalWidth = $im->getImageWidth();
        $originalHeight = $im->getImageHeight();

        $targetWidth = $targetHeight = max($originalWidth, $originalHeight);

        if ($this->ratio < 1) {
            $targetWidth = $targetHeight * $this->ratio;
        } else {
            $targetHeight = $targetWidth / $this->ratio;
        }

        $im->cropThumbnailImage($targetWidth, $targetHeight);

        return $im;
    }

    /**
     * Resize image
     *
     * @param Imagick $im im
     *
     * @return Imagick
     */
    public function resizeImage(Imagick $im)
    {
        $originalWidth = $im->getImageWidth();
        $originalHeight = $im->getImageHeight();

        if ($originalWidth > $originalHeight) {
            $targetWidth = $this->size;
            $targetHeight = 0;
        } else {
            $targetWidth = 0;
            $targetHeight = $this->size;
        }

        $im->scaleImage($targetWidth, $targetHeight);

        return $im;
    }
}
