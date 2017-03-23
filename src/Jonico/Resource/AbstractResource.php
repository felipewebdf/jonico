<?php
namespace Jonico\Resource;

use ZF\Rest\AbstractResourceListener;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of AbsctractResource
 *
 * @author fsilva
 */
class AbstractResource extends AbstractResourceListener
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $sm;

    /**
     *
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->sm = $serviceManager;
    }
}
