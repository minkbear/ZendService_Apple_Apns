<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendServiceTest\Apple\Apns\TestAsset;

use ZendService\Apple\Apns\Exception;
use ZendService\Apple\Apns\Client\Message as ZfMessageClient;

/**
 * Message Client Proxy
 * This class is utilized for unit testing purposes
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class MessageClient extends ZfMessageClient
{
    /**
     * Read Response
     *
     * @var string
     */
    protected $readResponse;

    /**
     * Write Response
     *
     * @var mixed
     */
    protected $writeResponse;

    /**
     * Set the Response
     *
     * @param  string        $str
     * @return MessageClient
     */
    public function setReadResponse($str)
    {
        $this->readResponse = $str;

        return $this;
    }

    /**
     * Set the write response
     *
     * @param  mixed         $resp
     * @return MessageClient
     */
    public function setWriteResponse($resp)
    {
        $this->writeResponse = $resp;

        return $this;
    }

    /**
     * Connect to Host
     *
     * @return MessageClient
     */
    protected function connect($host, array $ssl)
    {
        return $this;
    }

    /**
     * Return Response
     *
     * @param  string $length
     * @return string
     */
    protected function read($length = 1024)
    {
        if (! $this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to reading data');
        }
        $ret = substr($this->readResponse, 0, $length);
        $this->readResponse = null;

        return $ret;
    }

    /**
     * @param string $app_bundle_id    the app bundle id
     * @param string $payload          the payload to send (JSON)
     * @param string $token            the token of the device
     * @return mixed                   the status code
     */
    protected function write($app_bundle_id, $payload, $token)
    {
        if (! $this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to writing data');
        }
        $ret = $this->writeResponse;
        $this->writeResponse = null;

        return (null === $ret) ? strlen($payload) : $ret;
    }
}
