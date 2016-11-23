<?php

namespace Svd\MediaBundle\Command;

use DateTime;
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
        $container = $this->getContainer();
        $fileManager = $container->get('svd_media.manager.file');
        $fileRepository = $container->get('svd_media.repository.file');

        $fileNoveltyPeriod = $container->getParameter('svd_media.file_novelty_period');
        $fileNoveltyDeadline = new DateTime(sprintf('now - %d seconds', $fileNoveltyPeriod));

        foreach ($fileRepository->iterateByNotUsed() as $fileSet) {
            /** @var File $file */
            $file = current($fileSet);
            if ($file->getUpdatedAt() < $fileNoveltyDeadline) {
                $fileManager->removeFile($file);
                $this->write(sprintf('File "%s" has been deleted!', $file->getFilename()), Logger::DEBUG);
            }
        }
    }
}
