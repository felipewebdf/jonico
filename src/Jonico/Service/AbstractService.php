<?php
namespace Jonico\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of AbstractService
 *
 * @author fsilva
 */
class AbstractService
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    /**
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * Persist entity
     */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
