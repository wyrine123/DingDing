<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/10
 * Time: 12:09
 */
namespace Wyrine\DingDing;

use GuzzleHttp\Client;
use Wyrine\DingDing\DingDingMessage;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Class DingDing
 * @package Wyrine
 */
class DingDing
{

    /**
     * dingding default request method
     */
    const REQUEST_METHOD = 'POST';

    /**
     * http header
     */
    const HEADERS = array(
        'Content-Type' => 'application/json',
        'charset' => 'utf-8',
    );

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $webHookUri;

    /**
     * @var bool
     */
    private $certVerify = false;

    /**
     * DingDing constructor.
     * @param string $webHookUri
     * @param float $timeout
     */
    public function __construct(string $webHookUri, float $timeout = 3.0)
    {
        $this->webHookUri = $webHookUri;
        //setup timeout
        $this->httpClient = new Client(array(
            'timeout' => $timeout
        ));
    }

    /**
     * @param bool $bool
     * @param string $path
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setCertVerify(bool $bool = false, string $path = '')
    {
        if ($bool && empty($path)) {
            throw new \InvalidArgumentException();
        }
        $this->certVerify = $bool;
        return $this;
    }

    /**
     * @param DingDingMessage $message
     * @param callable $fulfillCallable
     * @param callable $rejectCallable
     * @return string
     */
    public function send(DingDingMessage $message, callable $fulfillCallable = null, callable $rejectCallable = null)
    {
        $fulfillCallable = $this->registerFulFillCallback($fulfillCallable);
        $rejectCallable = $this->registerRejectCallback($rejectCallable);

        $request = new Request(
            self::REQUEST_METHOD,
            $this->webHookUri,
            self::HEADERS,
            $message->toString()
        );

        return $this->httpClient
            ->sendAsync($request, ['verify' => $this->certVerify])
            ->then($fulfillCallable, $rejectCallable)
            ->wait();
    }

    /**
     * @param callable|null $callable
     * @return callable|\Closure
     */
    private function registerFulFillCallback(callable $callable = null)
    {
        if ($callable === null) {
            $callable = function (ResponseInterface $response) {
                return $response->getStatusCode() . ": " . $response->getBody();
            };
        }

        return $callable;
    }

    /**
     * @param callable|null $callable
     * @return callable|\Closure
     */
    private function registerRejectCallback(callable $callable = null)
    {
        if ($callable === null) {
            $callable = function (RequestException $exception) {
                return $exception->getMessage();
            };
        }

        return $callable;
    }
}