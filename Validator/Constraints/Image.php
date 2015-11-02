<?php

namespace Svd\MediaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Image as Constraint;

/**
 * Validator constraints
 */
class Image extends Constraint
{
    /**
     * Validated by
     *
     * @return string
     */
    public function validatedBy()
    {
        return 'svdmedia_image_validator';
    }
}
