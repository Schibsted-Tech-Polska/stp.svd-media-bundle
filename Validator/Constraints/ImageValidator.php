<?php

namespace Svd\MediaBundle\Validator\Constraints;

use Svd\MediaBundle\Model\File;
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
     * Set media url extension
     *
     * @param MediaUrlExtension $mediaUrlExtension media url extension
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
            $imagePath = sys_get_temp_dir() . '/' . $value->getFilename();
            parent::validate($imagePath, $constraint);
        }
    }
}
