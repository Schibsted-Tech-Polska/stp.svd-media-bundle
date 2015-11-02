<?php

namespace Svd\MediaBundle\Form\DataTransformer;

use Svd\MediaBundle\Entity\File as FileEntity;
use Svd\MediaBundle\Entity\Repository\FileRepository;
use Svd\MediaBundle\Model\File as ModelFile;
use Svd\MediaBundle\Twig\MediaUrlExtension;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Form DataTransformer
 */
class IdToFileTransformer implements DataTransformerInterface
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
    public function setFileRepository(FileRepository $fileRepository)
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
     * Transform
     * 
     * @param File|null $file file
     *
     * @return array|null
     */
    public function transform($file)
    {
        $ret = null;
        if ($file instanceof ModelFile) {
            $url = $this->mediaUrlExtension->getMediaUrl($file->getFilename());
            $ret = [
                'id' => $file->getId(),
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'mimetype' => $file->getMimeType(),
                'url' => $url,
            ];
        }
        return $ret;
    }

    /**
     * Reverse tranform
     *
     * @param mixed $id id
     *
     * @return null|File
     */
    public function reverseTransform($id)
    {
        $ret = null;
        if (!empty($id)) {
            if (is_numeric($id)) {
                $ret = $this->fileRepository->getOneBy([
                    'id' => $id,
                ]);
            } elseif (is_file($id)) {
//                $file = new File($id);
//                $newFile = new FileEntity();
//                $newFile->setFilename($file->getFilename());
//                $newFile->setStatus(FileEntity::STATUS_WAITING);
//                $newFile->setMimeType($file->getMimeType());
//                $newFile->setSize($file->getSize());
//
//                $ret = $newFile;
            }
        }

        return $ret;
    }
}
