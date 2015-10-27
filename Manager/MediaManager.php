<?php

namespace Svd\MediaBundle\Manager;

use Gaufrette\Adapter\MetadataSupporter;
use Gaufrette\Filesystem;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Svd\MediaBundle\Entity\File;
use Svd\MediaBundle\Transformer\ImageTransformer;

/**
 * Manager
 */
class MediaManager
{
    /** @var Filesystem */
    protected $adapter;

    /** @var array */
    protected $transformers;

    /** @var string */
    protected $defaultTransformer;

    /**
     * Construct
     *
     * @param string $defaultTransformer default transformer
     */
    public function __construct($defaultTransformer)
    {
        $this->defaultTransformer = $defaultTransformer;
    }

    /**
     * Set adapter
     *
     * @param FilesystemMap $map     map
     * @param string        $adapter adapter
     *
     * @return self
     */
    public function setAdapter(FilesystemMap $map, $adapter)
    {
        $adapter = $map->get($adapter);
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Get adapter
     *
     * @return Filesystem
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set transformers
     *
     * @param array $transformers transformers
     *
     * @return self
     */
    public function setTransformers(array $transformers)
    {
        $this->transformers = $transformers;

        return $this;
    }

    /**
     * Add transformer
     *
     * @param string           $name        name
     * @param ImageTransformer $transformer transformer
     *
     * @return self
     */
    public function addTransformer($name, ImageTransformer $transformer)
    {
        if (!array_key_exists($name, $this->transformers)) {
            $this->transformers[$name] = $transformer;
        }

        return $this;
    }

    /**
     * Get transformers
     *
     * @return ImageTransformer[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    /**
     * Get transformer
     *
     * @param string $name name
     *
     * @return ImageTransformer|null
     */
    public function getTransformer($name)
    {
        $ret = null;
        if (array_key_exists($name, $this->transformers)) {
            $ret = $this->transformers[$name];
        }

        return $ret;
    }

    /**
     * Get tmp path
     *
     * @return string
     */
    public function getTmpPath()
    {
        return sys_get_temp_dir() . '/';
    }

    /**
     * Get default transformer
     *
     * @return ImageTransformer
     */
    public function getDefaultTransformer()
    {
        return $this->transformers[$this->defaultTransformer];
    }

    /**
     * Upload file
     *
     * @param File $file file
     */
    public function uploadFile(File $file, $replace = false)
    {
        foreach ($this->getTransformers() as $transformer) {
            $adapter = $this->adapter->getAdapter();
            $filePath = $transformer->getFolder() . '/' . $file->getFilename();

            if ($adapter->exists($filePath) && !$replace) {
                continue;
            }

            $newImage = $transformer->transform($this->getTmpPath() . $file->getFilename());

            if ($adapter->exists($filePath) && $replace) {
                $adapter->delete($filePath);
            }

            if ($adapter instanceof MetadataSupporter) {
                $adapter->setMetadata($filePath, ['contentType' => $file->getMimeType()]);
            }

            $this->adapter->write($filePath, $newImage);
        }
    }
}
