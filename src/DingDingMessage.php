<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/10
 * Time: 12:57
 */
namespace Wyrine\DingDing;

interface DingDingMessage
{
    public function getMessageType() : string ;

    public function at($phone);

    public function atAll();

    public function getMessage() : array ;

    public function toString() : string ;
}