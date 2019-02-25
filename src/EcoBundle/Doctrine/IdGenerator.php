<?php
/**
 * Created by PhpStorm.
 * User: Mehdi Mheni
 * Date: 25/02/2019
 * Time: 04:30
 */

namespace EcoBundle\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
 /**
 * {@inheritdoc}
 */
class IdGenerator extends AbstractIdGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(EntityManager $em, $entity)
    {
        $uuid = Uuid::uuid4()->getHex();

        if (null !== $em->getRepository(get_class($entity))->findOneBy(['id' => $uuid])) {
            $uuid = $this->generate($em, $entity);
        }

        return $uuid;
    }
}