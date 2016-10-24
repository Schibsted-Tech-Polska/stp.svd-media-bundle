<?php

namespace Svd\MediaBundle\Twig;

use Liip\ImagineBundle\Templating\ImagineExtension;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig
 */
class MediaUrlExtension extends Twig_Extension
{
    /** @var string */
    protected $baseUrl;

    /** @var array */
    protected $liipImagineFilterMapper;

    /** @var ImagineExtension|null */
    protected $liipImagineTwigExtension;

    /**
     * Constructor
     *
     * @param string $baseUrl                 base URL
     * @param array  $liipImagineFilterMapper filter mapper
     */
    public function __construct($baseUrl, array $liipImagineFilterMapper = [])
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->liipImagineFilterMapper = $liipImagineFilterMapper;
    }

    /**
     * Set Liip Imagine Twig extension
     *
     * @param ImagineExtension|null $liipImagineTwigExtension Liip Imagine Twig extension
     *
     * @return self
     */
    public function setLiipImagineTwigExtension($liipImagineTwigExtension)
    {
        $this->liipImagineTwigExtension = $liipImagineTwigExtension;

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
        $fileUrl = sprintf('%s/%s', $this->baseUrl, $fileName);
        if (isset($this->liipImagineTwigExtension) && !empty($filter)) {
            if (array_key_exists($filter, $this->liipImagineFilterMapper)) {
                $filter = $this->liipImagineFilterMapper[$filter];
            }
            $fileUrl = $this->liipImagineTwigExtension->filter($fileUrl, $filter);
        }

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
