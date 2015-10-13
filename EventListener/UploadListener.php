<?php

namespace Svd\MediaBundle\EventListener;

use Svd\MediaBundle\Entity\File;
use Svd\MediaBundle\Entity\Repository\FileRepository;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Svd\MediaBundle\Twig\MediaUrlExtension;

/**
 * EventListener
 */
class UploadListener
{
    /** @var FileRepository */
    protected $fileRepository;

    /** @var MediaUrlExtension */
    protected $mediaUrlExtension;

    /**
     * Set file repository
     *
     * @param FileRepository $fileRepository file repository
     *
     * @return self
     */
    public function setFileRepository($fileRepository)
    {
        $this->fileRepository = $fileRepository;

        return $this;
    }

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
     * On upload
     *
     * @param PostUploadEvent $event event
     */
    public function onUpload(PostUploadEvent $event)
    {
        /** @var GaufretteFile $file */
        $file = $event->getFile();

        $newFile = new File();
        $newFile->setFilename($file->getName());
        $newFile->setMimeType($file->getMimeType());
        $newFile->setType($event->getType());
        $newFile->setStatus(File::STATUS_WAITING);
        $newFile->setSize($file->getSize());

        $this->fileRepository->insert($newFile, true);


        $response = $event->getResponse();
        $response['id'] = $newFile->getId();
        $response['url'] = $this->mediaUrlExtension->getMediaUrl($newFile->getFilename());
    }
}
