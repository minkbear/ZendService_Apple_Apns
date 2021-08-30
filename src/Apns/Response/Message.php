<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link       http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright  Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */

namespace ZendService\Apple\Apns\Response;

use ZendService\Apple\Exception;

/**
 * Message Response
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class Message
{
    /**
     * Response Codes (see https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/handling_notification_responses_from_apns?language=objc)
     * @var int
     */
    const RESULT_OK = 200;
    const RESULT_BAD_REQUEST = 400;
    const RESULT_BAD_AUTH = 403;
    const RESULT_INVALID_PATH = 404;
    const RESULT_INVALID_METHOD = 405;
    const RESULT_INVALID_TOKEN = 410;
    const RESULT_INVALID_PAYLOAD_SIZE = 413;
    const RESULT_TOO_MANY_REQUESTS = 429;
    const RESULT_INTERNAL_SERVER_ERROR = 500;
    const RESULT_SERVER_UNAVAILABLE = 503;

    // old consts
    //const RESULT_OK = 0;
    //const RESULT_PROCESSING_ERROR = 1;
    //const RESULT_MISSING_TOKEN = 2;
    //const RESULT_MISSING_TOPIC = 3;
    //const RESULT_MISSING_PAYLOAD = 4;
    //const RESULT_INVALID_TOKEN_SIZE = 5;
    //const RESULT_INVALID_TOPIC_SIZE = 6;
    //const RESULT_INVALID_PAYLOAD_SIZE = 7;
    //const RESULT_INVALID_TOKEN = 8;
    //const RESULT_UNKNOWN_ERROR = 255;

    /**
     * Identifier
     * @var string
     */
    protected $id;

    /**
     * Result Code
     * @var int
     */
    protected $code;

    /**
     * Result JSON body
     * @var string
     */
    protected $body;

    /**
     * Constructor
     *
     * @param  string  $rawResponse
     * @return Message
     */
    public function __construct($rawResponse = null)
    {
        if ($rawResponse !== null) {
            $this->parseRawResponse($rawResponse);
        }
    }

    /**
     * Get Code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set Code
     *
     * @param  int     $code
     * @return Message
     */
    public function setCode($code)
    {
        if ($code < 200 || $code > 503) {
            throw new Exception\InvalidArgumentException('Code must be between 200 and 503');
        }
        $this->code = $code;

        return $this;
    }

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Identifier
     *
     * @param  string  $id
     * @return Message
     */
    public function setId($id)
    {
        if (! is_scalar($id)) {
            throw new Exception\InvalidArgumentException('Identifier must be a scalar value');
        }
        $this->id = $id;

        return $this;
    }

    /**
     * Return Response Body
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * set Response JSON Body
     * @param  string  $body
     * @return Message
     */
    public function setBody($body)
    {
        if (! is_string($body)) {
            throw new Exception\InvalidArgumentException('Body must be a string value');
        }
        $this->body = $body;
        return $this;
    }

    /**
     * Parse Raw Response
     *
     * @param  string  $rawResponse
     * @return Message
     */
    public function parseRawResponse($rawResponse)
    {
        if (! is_scalar($rawResponse)) {
            throw new Exception\InvalidArgumentException('Response must be a scalar value');
        }

        if (strlen($rawResponse) === 0) {
            $this->code = self::RESULT_OK;

            return $this;
        }
        $response = unpack('Ccmd/Cerrno/Nid', $rawResponse);
        $this->setId($response['id']);
        $this->setCode($response['errno']);

        return $this;
    }
}
