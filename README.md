# DingDing
A component to send message to dingding robot.

It uses guzzlehttp to send request. It simplfies the dingding robot api and you will find it's very simple.

You can use object to create dingding message and the component will help you to do the other things in background.

## Installation
```bash
composer require wyrine/dingding:1.0
```

## Suggest
I suggest you to install a log component(for example [monolog](https://github.com/Seldaek/monolog)) to record the request and response.

## Basic Usage
Before you go on,you **have to** know [how to get the dingding robot webhook url](https://open-doc.dingtalk.com/docs/doc.htm?treeId=257&articleId=105735&docType=1#s3) and config your won dingding robot webhook url.

Now,the component is only support two format messages.

### Text Message
```php
<?php
use Wyrine\DingDing\DingDing;
use Wyrine\DingDing\Message\TextMessage;

$dingding = new DingDing("https://oapi.dingtalk.com/robot/send?access_token=b8b1b3d5193d6caffec98a4ce2b8676a32415a7da7b1f9bacccdf75d4ee5ee2c");
$textMessage = new TextMessage();
$textMessage->setContent('Hello World')->at('110');

$dingding->send($textMessage);
```

### Markdown Message
```php
<?php
use Wyrine\DingDing\DingDing;
use Wyrine\DingDing\Message\MarkdownMessage;

$dingding = new DingDing("https://oapi.dingtalk.com/robot/send?access_token=b8b1b3d5193d6caffec98a4ce2b8676a32415a7da7b1f9bacccdf75d4ee5ee2c");
$markdownMessage = new MarkdownMessage();
$markdownMessage
    ->setTitle('Hello World')
    ->setTextPicUrl('http://i01.lw.aliimg.com/media/lALPBbCc1ZhJGIvNAkzNBLA_1200_588.png')
    ->at('110');
$dingding->send($markdownMessage);
```
That's all! it's really simple,isn't it?  

By default,the ```$dingding->send()``` will return some message to you. when request send success, it will return ```'httpcode: httpbody'```,for example ```200: OK```.it will return string message to you when something wrong.

And you can customize your own callback logic.  

**The code below assume you have install monolog.**
```php
<?php
use Wyrine\DingDing\DingDing;
use Wyrine\DingDing\Message\MarkdownMessage;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

//create log instance
$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../log/my_app.log', Logger::DEBUG));

//create dingding object
$dingding = new DingDing("https://oapi.dingtalk.com/robot/send?access_token=b8b1b3d5193d6caffec98a4ce2b8676a32415a7da7b1f9bacccdf75d4ee5ee2c");
//make your own request
$markdownMessage = new MarkdownMessage();
$markdownMessage
    ->setTitle('Hello World')
    ->setTextPicUrl('http://i01.lw.aliimg.com/media/lALPBbCc1ZhJGIvNAkzNBLA_1200_588.png')
    ->at('110');

//request send success callback
$sucCallable = function (ResponseInterface $response) use ($logger){
   $logger->info($response->getStatusCode() . ' :' . $response->getBody());
};

//something wrong callback
$exceptionCallable = function (RequestException $exception) use($logger){
   $logger->error($exception->getMessage());
};
$dingding->send($markdownMessage, $sucCallable, $exceptionCallable);
```

You can achieve your callback in anonymous function body, you can use use() function to pass variable and use it in function body.
