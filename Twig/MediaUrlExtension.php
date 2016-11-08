<?php

namespace Svd\MediaBundle\Twig;

use Svd\MediaBundle\Manager\MediaUrlManager;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig
 */
class MediaUrlExtension extends Twig_Extension
{
    /** @var MediaUrlManager */
    protected $mediaUrlManager;

    /**
     * Set media URL manager
     *
     * @param MediaUrlManager $mediaUrlManager media URL manager
     *
     * @return self
     */
    public function setMediaUrlManager(MediaUrlManager $mediaUrlManager)
    {
        $this->mediaUrlManager = $mediaUrlManager;

        return $this;
    }

    /**
     * Get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('mediaUrl', array($this, 'getMediaUrl')),
        ];
    }

    /**
     * Get media URL
     *
     * @param string      $fileName file name
     * @param string|null $filter   filter
     *
     * @return string
     */
    public function getMediaUrl($fileName, $filter = null)
    {
        $fileUrl = $this->mediaUrlManager->getMediaUrl($fileName, $filter);

        return $fileUrl;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'svd_media_url';
    }
}
