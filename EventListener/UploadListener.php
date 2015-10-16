<?php

namespace Svd\MediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Gaufrette\Filesystem;
use Imagick;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Svd\MediaBundle\Entity\File;

/**
 * EventListener
 */
class UploadListener
{
    /** @var Filesystem */
    protected $filesystem;

    /**
     * Set filesystem
     *
     * @param FilesystemMap $filesystemMap filesystem map
     *
     * @return self
     */
    public function setFilesystem(FilesystemMap $filesystemMap)
    {
        // @TODO: name of filesystem schould be set in configuration of media bundle
        $filesystem = $filesystemMap->get('aws_s3_type');
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * On upload
     *
     * @param LifecycleEventArgs $event event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $file = $event->getEntity();
        if ($file instanceof File) {
            $this->uploadFile($file);
            $file->setStatus(File::STATUS_ACTIVE);
        }
    }

    protected function uploadFile(File $file)
    {
        $tmpPath = sys_get_temp_dir() . '/';
        $images = [
            'default' => [
                'ratio' => 16/9,
                'size' => 1600,
            ],
            'thumbnail' => [
                'ratio' => 16/9,
                'size' => 720,
            ],
        ];

        foreach ($images as $key => $options) {
            $filename = $key . '_' . $file->getFilename();
            $filePath = $key . '/' . $file->getFilename();
            $image = $this->cropImage($file, $options['ratio'], $options['size']);
            $image->writeImage($tmpPath . $filename);
            $image->getImageBlob();

            $this->filesystem->write($filePath, $image);
        }
    }

    protected function cropImage(File $file, $ratio, $size)
    {
        $filePath = sys_get_temp_dir() . '/' . $file->getFilename();
        $im = new Imagick($filePath);

        $originalWidth = $im->getImageWidth();
        $originalHeight = $im->getImageHeight();
        $targetWidth = $targetHeight = min($size, max($originalWidth, $originalHeight));

        if ($ratio < 1) {
            $targetWidth = $targetHeight * $ratio;
        } else {
            $targetHeight = $targetWidth / $ratio;
        }

        $im->cropThumbnailImage($targetWidth, $targetHeight);

        return $im;
    }
}
