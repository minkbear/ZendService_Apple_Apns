<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */

namespace ZendService\Apple\Apns\Client;

use ZendService\Apple\Apns\Message as ApnsMessage;
use ZendService\Apple\Apns\Response\Message as MessageResponse;
use ZendService\Apple\Exception;

/**
 * Message Client
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class Message extends AbstractClient
{
    /**
     * Send Message
     *
     * @param ApnsMessage $message
     * @return MessageResponse
     */
    public function send(ApnsMessage $message)
    {
        if (! $this->isConnected()) {
            throw new Exception\RuntimeException('You must first open the connection by calling open()');
        }

        $ret = $this->write(
            $message->getBundleId(),
            $message->getPayloadJson(),
            $message->getToken()
        );
        if ($ret === false) {
            throw new Exception\RuntimeException('Server is unavailable; please retry later');
        }

        $response = new MessageResponse();
        $response->setCode($this->responseStatus);
        $response->setId($this->responseId);
        $response->setBody($this->responseBody);
        return $response;
    }
}
