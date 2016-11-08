<?php

namespace Svd\MediaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Svd\CoreBundle\Entity\Repository\BaseRepositoryInterface;
use Svd\CoreBundle\Entity\Repository\BaseRepositoryTrait;

/**
 * Entity repository
 */
class FileRepository extends EntityRepository implements BaseRepositoryInterface
{
    use BaseRepositoryTrait;

    /**
     * Iterate by not used
     *
     * @return IterableResult
     */
    public function iterateByNotUsed()
    {
        $qb = $this->createQueryBuilder('f')
            ->select('f')
            ->where('f.usagesCount <= :count')
            ->setParameter(':count', 0)
            ->getQuery();

        return $qb->iterate();
    }
}
