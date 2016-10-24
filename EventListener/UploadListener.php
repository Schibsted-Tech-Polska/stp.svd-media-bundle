<?php

namespace Svd\MediaBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Svd\MediaBundle\Entity\File;

/**
 * EventListener
 *
 * @TODO: needs to be repaired (it doesn't work properly)
 */
class UploadListener
{
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
                    $this->decreaseUsagesCount($em, $changeSet[0]);
                }
                if ($changeSet[1] instanceof File) {
                    $this->increaseUsagesCount($em, $changeSet[1]);
                }
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            foreach ($this->findFilesInEntity($uow, $entity) as $file) {
                $this->decreaseUsagesCount($em, $file);
            }
        }
    }

    /**
     * Pre soft delete
     * 
     * @param LifecycleEventArgs $event event
     */
    public function preSoftDelete(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($this->findFilesInEntity($uow, $event->getEntity()) as $file) {
            $this->decreaseUsagesCount($em, $file);
        }
    }

    /**
     * Find files in entity
     *
     * @param UnitOfWork $uow    unit of work
     * @param object     $entity entity
     *
     * @return array
     */
    protected function findFilesInEntity(UnitOfWork $uow, $entity)
    {
        $files = [];
        $fields = $uow->getOriginalEntityData($entity);
        if (count($fields) !== 0) {
            foreach ($fields as $field) {
                if ($field instanceof File) {
                    $files[] = $field;
                }
            }
        }

        return $files;
    }

    /**
     * Decrease usages count
     *
     * @param EntityManager $em   em
     * @param File          $file file
     */
    protected function decreaseUsagesCount(EntityManager $em, File $file)
    {
        $this->changeUsagesCount($em, $file, -1);
    }

    /**
     * Increase usages count
     *
     * @param EntityManager $em   em
     * @param File          $file file
     */
    protected function increaseUsagesCount(EntityManager $em, File $file)
    {
        $this->changeUsagesCount($em, $file, 1);
    }

    /**
     * Change usages count
     *
     * @param EntityManager $em    em
     * @param File          $file  file
     * @param int           $value value
     */
    protected function changeUsagesCount(EntityManager $em, File $file, $value)
    {
        $classMetadata = $em->getClassMetadata('Svd\MediaBundle\Entity\File');
        $file->setUsagesCount($file->getUsagesCount() + $value);

        $em->getUnitOfWork()
            ->recomputeSingleEntityChangeSet($classMetadata, $file);
        $em->persist($file);
    }
}
