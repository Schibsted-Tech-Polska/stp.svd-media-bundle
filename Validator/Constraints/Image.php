<?php

namespace Svd\MediaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Image as Constraint;

/**
 * Validator constraints
 */
class Image extends Constraint
{
    public function validateBy()
    {
        return 'svdmedia_image_validator';
    }
}
