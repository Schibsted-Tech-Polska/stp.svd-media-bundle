<?php

namespace Svd\MediaBundle\Command;

use Svd\CoreBundle\Command\BaseCommand;
use Svd\MediaBundle\Entity\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Command
 */
class TransformImagesCommand extends BaseCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('transform:images');
    }

    /**
     * Process
     */
    protected function process()
    {
        $dir = $this->getContainer()
            ->get('kernel')
            ->getRootDir();

        $mediaManager = $this->getContainer()
            ->get('svd_media.manager.media');

        $image = new File();
        $image->setFilename(md5(microtime()) . '.jpg');

        $fs = new Filesystem();
        $imageDir = $dir . '/../public_html/uploads/test.jpg';
        $fs->copy($imageDir, $mediaManager->getTmpPath() . $image->getFilename());

        $mediaManager->uploadFile($image);

        dump($image->getFilename());

    }
}
