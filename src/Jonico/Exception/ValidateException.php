<?php
namespace Jonico\Exception;
/**
 * Description of ValidateException
 *
 * @author fsilva
 */
class ValidateException extends \Exception
{
    /**
     *
     * @param string $message
     * @param int $code default 422
     * @param \Throwable $previous
     */
    public function __construct(string $message = "", int $code = 422, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
