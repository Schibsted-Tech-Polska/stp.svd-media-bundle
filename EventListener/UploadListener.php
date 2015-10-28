<?php

namespace Svd\MediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
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
            $file->setUsagesCount($file->getUsagesCount()+1);
        }
    }

    /**
     * On flush
     *
     * @param OnFlushEventArgs $event event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            foreach ($uow->getEntityChangeSet($entity) as $changeSet) {
                if ($changeSet[0] instanceof File) {
                    $oldFile = $changeSet[0];
                    $oldFile->setUsagesCount($oldFile->getUsagesCount()-1);
                    $md = $em->getClassMetadata('Svd\MediaBundle\Entity\File');
                    $em->getUnitOfWork()->recomputeSingleEntityChangeSet($md, $oldFile);
                    $em->persist($oldFile);
                }
            }
        }
    }
}
