<?php

namespace Svd\MediaBundle\Validator\Constraints;

use Svd\MediaBundle\Manager\MediaManager;
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

    /** @var MediaManager */
    protected $mediaManager;

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
     * Set media manager
     *
     * @param MediaManager $mediaManager media manager
     *
     * @return self
     */
    public function setMediaManager(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof File && $value->getStatus() == File::STATUS_WAITING) {
            $imageUrl = $this->mediaUrlExtension->getMediaUrl($value->getFilename());
            $imagePath = $this->mediaManager->getTmpPath() . md5(microtime()) . $value->getFilename();
            file_put_contents($imagePath, $imageUrl);

            parent::validate($imagePath, $constraint);
        }
    }
}
