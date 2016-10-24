<?php

namespace Svd\MediaBundle\Model;

use Svd\CoreBundle\Model\CreatedAtTrait;
use Svd\CoreBundle\Model\IdTrait;
use Svd\CoreBundle\Model\ModelInterface;
use Svd\CoreBundle\Model\UpdatedAtTrait;

/**
 * Model
 */
class File implements ModelInterface
{
    use IdTrait, CreatedAtTrait, UpdatedAtTrait;

    /** @const integer */
    const STATUS_WAITING = 0;

    /** @const integer */
    const STATUS_ACTIVE = 1;

    /** @const integer */
    const STATUS_INACTIVE = 2;

    /** @var string */
    protected $filename;

    /** @var integer */
    protected $status;

    /** @var string */
    protected $mimeType;

    /** @var integer */
    protected $usagesCount = 0;

    /** @var integer */
    protected $size;

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filename
     *
     * @param string $filename filename
     *
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param integer $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set mime type
     *
     * @param string $mimeType mime type
     *
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get usages count
     *
     * @return integer
     */
    public function getUsagesCount()
    {
        return $this->usagesCount;
    }

    /**
     * Set usages count
     *
     * @param integer $usagesCount usages count
     *
     * @return self
     */
    public function setUsagesCount($usagesCount)
    {
        $this->usagesCount = $usagesCount;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size
     *
     * @param integer $size size
     *
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }
}
