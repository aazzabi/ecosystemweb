<?php

namespace EcoBundle\Repository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllSociete()
    {
        $query=$this->createQueryBuilder('g');
        $query->where(" g.type=:type")
            ->setParameter('type', 'societe');
        return $query->getQuery()->getResult();
    }

    public function findAllAssociation()
    {
        $query=$this->createQueryBuilder('g');
        $query->where(" g.type=:type")
            ->setParameter('type', 'association');
        return $query->getQuery()->getResult();
    }
}
