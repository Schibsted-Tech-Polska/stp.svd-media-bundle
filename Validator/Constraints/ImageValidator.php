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
