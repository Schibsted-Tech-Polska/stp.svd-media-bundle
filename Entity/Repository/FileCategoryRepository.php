<?php

namespace Svd\MediaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Svd\CoreBundle\Entity\Repository\BaseRepositoryInterface;
use Svd\CoreBundle\Entity\Repository\BaseRepositoryTrait;

/**
 * Entity repository
 */
class FileCategoryRepository extends EntityRepository implements BaseRepositoryInterface
{
    use BaseRepositoryTrait;
}
