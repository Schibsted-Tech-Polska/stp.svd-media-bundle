<?php

namespace Svd\MediaBundle\Command;

use Svd\CoreBundle\Command\BaseCommand;
use Svd\MediaBundle\Entity\File;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Input\InputOption;

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

        $this->setName('transform:images')
            ->addOption('replace', 'r', InputOption::VALUE_NONE, 'Replace existing images');
    }

    /**
     * Process
     */
    protected function process()
    {
        $fileRepository = $this->getContainer()
            ->get('svd_media.file.repository');
        $mediaUrlExt = $this->getContainer()
            ->get('svd_media.twig_extension.media_url');
        $mediaManager = $this->getContainer()
            ->get('svd_media.manager.media');

        $replace = $this->input->getOption('replace');

        $files = $fileRepository->getBy([]);

        /** @var File $file */
        foreach ($files as $file) {
            $imageUrl = $mediaUrlExt->getMediaUrl($file->getFilename());
            try {
                $handle = fopen($imageUrl, 'rb');
            } catch (\Exception $e) {
                $this->write($e->getMessage(), Logger::ERROR, [
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                continue;
            }

            file_put_contents($mediaManager->getTmpPath() . $file->getFilename(), $handle);

            $mediaManager->uploadFile($file, $replace);

            $this->write(sprintf('Uploaded %s', $file->getFilename()), Logger::DEBUG);
        }
    }
}
