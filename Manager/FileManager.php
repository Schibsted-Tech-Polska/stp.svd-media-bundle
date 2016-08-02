<?php

namespace Svd\MediaBundle\Manager;

use Doctrine\ORM\EntityManager;
use Svd\MediaBundle\Entity\File as FileEntity;
use Svd\MediaBundle\Manager\MediaManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

class FileManager
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var MediaManager */
    protected $mediaManager;

    /** @var File */
    protected $tempFile;

    /**
     * Set entity manager
     *
     * @param EntityManager $entityManager entity manager
     *
     * @return self
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

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
     * Save file
     *
     * @param FileBag $bag file bag
     *
     * @return array saved file information
     */
    public function saveFile(FileBag $bag)
    {
        $file = $this->getFile($bag);

        $this->addTempFile($file);

        $newFile = new FileEntity();
        $newFile->setFilename($this->tempFile->getFilename());
        $newFile->setStatus(FileEntity::STATUS_WAITING);
        $newFile->setMimeType($this->tempFile->getMimeType());
        $newFile->setSize($this->tempFile->getSize());
        $newFile->setUsagesCount(0);

        $this->mediaManager->uploadFile($newFile);

        $this->entityManager->persist($newFile);
        $this->entityManager->flush();

        $savedFile = [
            'id' => $newFile->getId(),
            'pathname' => $this->tempFile->getPathname(),
            'originalName' => $file->getClientOriginalName(),
            'originalExtension' => $file->getClientOriginalExtension(),
        ];

        return $savedFile;
    }

    /**
     * Get files
     *
     * @param FileBag $bag bag
     *
     * @return UploadedFile|null
     */
    protected function getFile(FileBag $bag)
    {
        $files = [];
        $fileBag = $bag->all();
        foreach ($fileBag as $file) {
            if (is_array($file) || null === $file) {
                continue;
            }
            $files[] = $file;
        }

        $ret = null;
        if (count($files) > 0) {
            $ret = $files[0];
        }

        return $ret;
    }

    /**
     * Add temporary file
     *
     * @param $file
     *
     * @return self
     */
    protected function addTempFile(UploadedFile $file)
    {
        $filesystem = new Filesystem();

        $path = sys_get_temp_dir() . '/' . $this->generateName($file);
        $filesystem->copy($file->getPathname(), $path);

        $this->tempFile  = new File($path);

        return $this;
    }

    /**
     * Generate name
     *
     * @param UploadedFile $file file
     *
     * @return string
     */
    protected function generateName(UploadedFile $file)
    {
        $name = md5(microtime());
        $name .= '.' . $file->getClientOriginalExtension();

        return $name;
    }
}
