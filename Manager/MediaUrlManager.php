<?php

namespace Svd\MediaBundle\Manager;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig
 */
class MediaUrlManager
{
    /** @var string */
    protected $baseUrl;

    /** @var array */
    protected $liipImagineFilterMapper;

    /** @var CacheManager|null */
    protected $liipImagineCacheManager;

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
     * Set Liip Imagine cache manager
     *
     * @param CacheManager|null $liipImagineCacheManager Liip Imagine cache manager
     *
     * @return self
     */
    public function setLiipImagineCacheManager(CacheManager $liipImagineCacheManager = null)
    {
        $this->liipImagineCacheManager = $liipImagineCacheManager;

        return $this;
    }

    /**
     * Get media URL
     *
     * @param string      $fileName      file name
     * @param string|null $filter        filter
     * @param array       $runtimeConfig runtime config
     *
     * @return string
     */
    public function getMediaUrl($fileName, $filter = null, array $runtimeConfig = [])
    {
        if (isset($this->liipImagineCacheManager) && !empty($filter)) {
            if (array_key_exists($filter, $this->liipImagineFilterMapper)) {
                $filter = $this->liipImagineFilterMapper[$filter];
            }
            $fileUrl = $this->liipImagineCacheManager->getBrowserPath($fileName, $filter, $runtimeConfig);
        } else {
            $fileUrl = sprintf('%s/%s', $this->baseUrl, $fileName);
        }

        return $fileUrl;
    }
}
