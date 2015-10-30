<?php

namespace Svd\MediaBundle\Command;

use Svd\CoreBundle\Command\BaseCommand;
use Svd\MediaBundle\Entity\File;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command
 */
class DeleteUnusedFilesCommand extends BaseCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('delete:unused:files');
    }

    /**
     * Process
     */
    protected function process()
    {
        $fileRepository = $this->getContainer()
            ->get('svd_media.file.repository');
        $mediaManager = $this->getContainer()
            ->get('svd_media.manager.media');

        $files = $fileRepository->iterateByNotUsed();

        foreach ($files as $file) {
            /** @var File $file */
            $file = current($file);
            $mediaManager->deleteFile($file);
            $fileRepository->delete($file, true);

            $this->write(sprintf('File \'%s\' has been deleted!', $file->getFilename()), Logger::DEBUG);
        }
    }
}
