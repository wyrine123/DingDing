<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/13
 * Time: 11:22
 */
require "../vendor/autoload.php";

/**
 * If you want to log the request.
 * I suggest use monolog/monolog component.
 * Example are list below
 *
 **/

//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;
use Wyrine\DingDing\DingDing;
use Wyrine\DingDing\Message\MarkdownMessage;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

//创建log实例
//$logger = new Logger('my_logger');
//$logger->pushHandler(new StreamHandler(__DIR__ . '/../log/my_app.log', Logger::DEBUG));

$dingding = new DingDing("https://oapi.dingtalk.com/robot/send?access_token=b8b1b3d5193d6caffec98a4ce2b8676a32415a7da7b1f9bacccdf75d4ee5ee2c");
$markdownMessage = new MarkdownMessage();
$markdownMessage
    ->setTitle('Hello World')
    ->setTextPicUrl('http://i01.lw.aliimg.com/media/lALPBbCc1ZhJGIvNAkzNBLA_1200_588.png')
    ->at('18168065081');


//$sucCallable = function (ResponseInterface $response) use ($logger){
//    $logger->info($response->getStatusCode() . ' :' . $response->getBody());
//};
//
//$exceptionCallable = function (RequestException $exception) use($logger){
//    $logger->error($exception->getMessage());
//};

$sucCallable = $exceptionCallable = null;

$dingding->send($markdownMessage, $sucCallable, $exceptionCallable);
echo 'Done!' . PHP_EOL;
