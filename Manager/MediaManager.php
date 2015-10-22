<?php

namespace Svd\MediaBundle\Manager;

use Gaufrette\Filesystem;
use Knp\Bundle\GaufretteBundle\FilesystemMap;

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
}
