<?php

namespace Svd\MediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Svd\MediaBundle\Entity\File;
use Svd\MediaBundle\Manager\MediaManager;

/**
 * EventListener
 */
class UploadListener
{

    /** @var MediaManager */
    protected $mediaManager;

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
     * On upload
     *
     * @param LifecycleEventArgs $event event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $file = $event->getEntity();
        if ($file instanceof File) {
            $this->mediaManager->uploadFile($file);
            $file->setStatus(File::STATUS_ACTIVE);
        }
    }
}
