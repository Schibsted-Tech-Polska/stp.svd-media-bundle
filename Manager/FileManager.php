<?php

namespace Svd\MediaBundle\Manager;

use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Adapter\MetadataSupporter;
use Gaufrette\Filesystem;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Svd\MediaBundle\Entity\File as FileEntity;
use Svd\MediaBundle\Entity\Repository\FileRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

class FileManager
{
    /** @var Filesystem */
    protected $remoteFilesystem;

    /** @var FileRepository */
    protected $fileRepository;

    /**
     * Set remote filesystem
     *
     * @param FilesystemMap $map            map
     * @param string        $filesystemName filesystem name
     *
     * @return self
     */
    public function setRemoteFilesystem(FilesystemMap $map, $filesystemName)
    {
        $this->remoteFilesystem = $map->get($filesystemName);

        return $this;
    }

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
     * Save file
     *
     * @param FileBag $fileBag file bag
     *
     * @return FileEntity
     */
    public function saveFile(FileBag $fileBag)
    {
        $uploadedFile = $this->getFirstUploadedFile($fileBag);
        $file = $this->uploadFile($uploadedFile);
        $this->fileRepository->insert($file, true);

        return $file;
    }

    /**
     * Remove file
     *
     * @param FileEntity $file file
     */
    public function removeFile(FileEntity $file)
    {
        $this->deleteFile($file);
        $this->fileRepository->delete($file, true);
    }

    /**
     * Get uploaded files
     *
     * @param FileBag $fileBag file bag
     *
     * @return UploadedFile[]
     */
    protected function getUploadedFiles(FileBag $fileBag)
    {
        $files = array_filter($fileBag->all(), function ($file) {
            return $file instanceof UploadedFile;
        });

        return $files;
    }

    /**
     * Get first uploaded file
     *
     * @param FileBag $fileBag file bag
     *
     * @return UploadedFile|null
     */
    protected function getFirstUploadedFile(FileBag $fileBag)
    {
        $files = $this->getUploadedFiles($fileBag);
        $firstFile = array_shift($files);

        return $firstFile;
    }

    /**
     * Generate name
     *
     * @param UploadedFile $uploadedFile uploaded file
     *
     * @return string
     */
    protected function generateName(UploadedFile $uploadedFile)
    {
        $name = md5(microtime()) . '.' . $uploadedFile->getClientOriginalExtension();

        return $name;
    }

    /**
     * Upload file
     *
     * @param UploadedFile $uploadedFile uploaded file
     * @param bool         $replace      replace
     *
     * @return FileEntity
     */
    public function uploadFile(UploadedFile $uploadedFile, $replace = false)
    {
        $localFileSystem = new Filesystem(new LocalAdapter($uploadedFile->getPath()));
        $file = new File($uploadedFile->getPathname());
        $content = $localFileSystem->read($uploadedFile->getFilename());

        $adapter = $this->remoteFilesystem->getAdapter();
        $fileName = $this->generateName($uploadedFile);
        if ($adapter instanceof MetadataSupporter) {
            $adapter->setMetadata($fileName, [
                'contentType' => $file->getMimeType(),
            ]);
        }
        $this->remoteFilesystem->write($fileName, $content);

        $fileEntity = new FileEntity();
        $fileEntity->setFilename($fileName)
            ->setMimeType($file->getMimeType())
            ->setSize($file->getSize())
            ->setStatus(FileEntity::STATUS_WAITING);

        return $fileEntity;
    }

    /**
     * Delete file
     *
     * @param FileEntity $file file
     */
    public function deleteFile(FileEntity $file)
    {
        $adapter = $this->remoteFilesystem->getAdapter();

        if ($adapter->exists($file->getFilename())) {
            $adapter->delete($file->getFilename());
        }
    }
}
