<?php

namespace Svd\MediaBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Svd\CoreBundle\Model\CreatedAtTrait;
use Svd\CoreBundle\Model\IdTrait;
use Svd\CoreBundle\Model\ModelInterface;
use Svd\CoreBundle\Model\UpdatedAtTrait;

/**
 * Model
 */
class FileCategory implements ModelInterface
{
    use IdTrait, CreatedAtTrait, UpdatedAtTrait;

    /** @var string */
    protected $name;

    /** @var string */
    protected $slug;

    /** @var int */
    protected $lft;

    /** @var int */
    protected $rgt;

    /** @var int */
    protected $level;

    /** @var int */
    protected $root;

    /** @var FileCategory */
    protected $parent;

    /** @var ArrayCollection */
    protected $children;

    /** @var ArrayCollection */
    protected $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeCollections();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set level
     *
     * @param int $level level
     *
     * @return self
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Is main
     *
     * @return bool
     */
    public function isMain()
    {
        return $this->level == 0;
    }

    /**
     * Get parent
     *
     * @return FileCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param FileCategory $parent parent
     *
     * @return self
     */
    public function setParent(FileCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get children
     *
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param ArrayCollection $children children
     *
     * @return self
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add child
     *
     * @param FileCategory $child child
     *
     * @return self
     */
    public function addChild(FileCategory $child)
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
        }

        return $this;
    }

    /**
     * Remove child
     *
     * @param FileCategory $child child
     *
     * @return self
     */
    public function removeChild(FileCategory $child)
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
        }

        return $this;
    }

    /**
     * Get files
     *
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set files
     *
     * @param ArrayCollection $files files
     *
     * @return self
     */
    public function setFiles(ArrayCollection $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Add file
     *
     * @param File $file file
     *
     * @return self
     */
    public function addFile(File $file)
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }

        return $this;
    }

    /**
     * Remove file
     *
     * @param File $file file
     *
     * @return self
     */
    public function removeFile(File $file)
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
        }

        return $this;
    }

    /**
     * Initialize collections
     */
    public function initializeCollections()
    {
        // @HACK: used to initialize collections when Doctrine finishes loading an object
        if (!($this->getChildren() instanceof Collection)) {
            $this->setChildren(new ArrayCollection());
        }
        if (!($this->getFiles() instanceof Collection)) {
            $this->setFiles(new ArrayCollection());
        }
    }
}
