<?php

namespace Svd\MediaBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig
 */
class MediaUrlExtension extends Twig_Extension
{
    /** @var string */
    protected $mediaBaseUrl;

    /**
     * Set media base url
     *
     * @param String $mediaBaseUrl
     */
    public function setMediaBaseUrl($mediaBaseUrl)
    {
        $this->mediaBaseUrl = rtrim($mediaBaseUrl, '/');
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
     * Get media url
     *
     * @param string $filename filename
     *
     * @return string
     */
    public function getMediaUrl($filename)
    {
        return sprintf('%s/%s', $this->mediaBaseUrl, $filename);
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
