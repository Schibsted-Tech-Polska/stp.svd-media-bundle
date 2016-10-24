<?php

namespace Svd\MediaBundle\Command;

use Svd\CoreBundle\Command\BaseCommand;
use Svd\MediaBundle\Entity\File;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command
 */
class RemoveUnusedMediaCommand extends BaseCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('media:remove-unused');
    }

    /**
     * Process
     */
    protected function process()
    {
        $fileRepository = $this->getContainer()
            ->get('svd_media.repository.file');
        $fileManager = $this->getContainer()
            ->get('svd_media.manager.file');

        foreach ($fileRepository->iterateByNotUsed() as $fileSet) {
            /** @var File $file */
            $file = current($fileSet);
            $fileManager->removeFile($file);

            $this->write(sprintf('File "%s" has been deleted!', $file->getFilename()), Logger::DEBUG);
        }
    }
}
