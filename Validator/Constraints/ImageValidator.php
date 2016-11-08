<?php

namespace Svd\MediaBundle\Validator\Constraints;

use Svd\MediaBundle\Manager\FileManager;
use Svd\MediaBundle\Model\File;
use Svd\MediaBundle\Twig\MediaUrlExtension;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ImageValidator as Validator;

/**
 * Validator constraints
 */
class ImageValidator extends Validator
{
    /** @var MediaUrlExtension */
    protected $mediaUrlExtension;

    /**
     * Set media URL extension
     *
     * @param MediaUrlExtension $mediaUrlExtension media URL extension
     *
     * @return self
     */
    public function setMediaUrlExtension(MediaUrlExtension $mediaUrlExtension)
    {
        $this->mediaUrlExtension = $mediaUrlExtension;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof File && $value->getStatus() == File::STATUS_WAITING) {
            $imageUrl = $this->mediaUrlExtension->getMediaUrl($value->getFilename());
            $imagePath = sys_get_temp_dir() . '/' . md5(microtime()) . $value->getFilename();
            file_put_contents($imagePath, $imageUrl);

            parent::validate($imagePath, $constraint);

            unlink($imagePath);
        }
    }
}
