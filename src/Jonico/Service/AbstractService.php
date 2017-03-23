<?php
namespace Jonico\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zend\ServiceManager\ServiceManager;
use Jonico\Validate\AbstractValidate;
use Jonico\Exception\ValidateException;

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
     * @var ServiceManager
     */
    protected $sm;
    protected $services;
    /**
     *
     * @var AbstractValidate
     */
    protected $validate;
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
    /**
     * Setar Service manager
     * @param ServiceManager $sm
     */
    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
    }
    /**
     * Retornar instancia service manager
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->sm;
    }
    /**
     * Retornar o servico solicitado
     * @param string $name
     * @return mixed
     */
    public function getService($name)
    {
        if (!isset($this->services[$name])) {
            $this->services[$name] = $this->getServiceManager()->get($name);
        }
        return $this->services[$name];
    }
    /**
     *
     * @param AbstractValidate $validate
     */
    public function setValidate(AbstractValidate $validate)
    {
        $this->validate = $validate;
    }
    /**
     * Launch exception
     * @param string $inputName
     * @param string $message
     * @param integer $code
     * @throws ValidateException
     */
    protected function validateException($inputName, $message, $code=422)
    {
        $json = json_encode(['validation_messages' => [$inputName => [$message]]], true);
        throw new ValidateException($json, $code);
    }
}
