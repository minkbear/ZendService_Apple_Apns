<?php

namespace ZendServiceTest\Apple\Apns;

require '../../src/Exception/InvalidArgumentException.php';
require '../../src/Exception/RuntimeException.php';
require '../../src/Exception/StreamSocketClientException.php';
require '../../src/Apns/Client/AbstractClient.php';
require '../../src/Apns/Client/Message.php';
require '../../src/Apns/Message.php';
require '../../src/Apns/Message/Alert.php';
require '../../src/Apns/Response/Message.php';

use PHPUnit\Framework\TestCase;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Response\Message as Response;
use ZendService\Apple\Exception\RuntimeException;

class MessageSendTest extends TestCase
{
    public function setUp()
    {
    }

    public function testSend()
    {

        $bundleId = 'your.bundle.id';
        $cert = 'TestAsset/certificate.pem';
        $pwd = 'CertPwd';
        $token = 'a65222627d25e8afe14a6f03f673f6594b36f69db736bc9768c30498edbda1f4';

        $client = new Client();
        $client->open(Client::SANDBOX_URI, $cert, $pwd);

        $message = new Message();
        $message->setId('1');
        $message->setBundleId($bundleId);
        $message->setToken($token);
        $message->setBadge(0);
        $message->setSound('default');

        $message->setAlert('Push notification test');

        try {
            $response = $client->send($message);
        } catch (RuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(1);
        }
        $client->close();

        if ($response->getCode() != Response::RESULT_OK) {
            switch ($response->getCode()) {
                case Response::RESULT_BAD_REQUEST:
                    // check response body for more info
                    $this->assertTrue(false, 'Bad request. Check response JSON body: ' . $response->getBody());
                    break;
                case Response::RESULT_BAD_AUTH:
                    // There was an error with the certificate or with the provider’s authentication token.
                    $this->assertTrue(false, 'you were missing a token');
                    break;
                case Response::RESULT_INVALID_PATH:
                    // The request contained an invalid :path value.
                    $this->assertTrue(false, 'you are missing a message id');
                    break;
                case Response::RESULT_INVALID_METHOD:
                    // The request used an invalid :method value. Only POST requests are supported.
                    $this->assertTrue(false, 'Invalid method value');
                    break;
                case Response::RESULT_INVALID_TOKEN:
                    // The device token is no longer active for the topic.
                    $this->assertTrue(false, 'The device token is no longer active for the topic');
                    break;
                case Response::RESULT_INVALID_PAYLOAD_SIZE:
                    // the payload was too large
                    $this->assertTrue(false, 'the payload was too large');
                    break;
                case Response::RESULT_TOO_MANY_REQUESTS:
                    // The server received too many requests for the same device token.
                    $this->assertTrue(false, 'The server received too many requests for the same device token.');
                    break;
                case Response::RESULT_INTERNAL_SERVER_ERROR:
                    // Internal server error.
                    $this->assertTrue(false, 'Internal server error.');
                    break;
                case Response::RESULT_SERVER_UNAVAILABLE:
                    // The server is shutting down and unavailable.
                    $this->assertTrue(false, 'The server is shutting down and unavailable.');
                    break;
            }
        }
    }
}
