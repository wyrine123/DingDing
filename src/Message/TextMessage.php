<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/10
 * Time: 13:00
 */
namespace Wyrine\DingDing\Message;
use Wyrine\DingDing\DingDingMessage;


/**
 * Class TextMessage
 * @package Wyrine\message
 */
class TextMessage implements DingDingMessage
{
    /**
     * @var array
     */
    private $message = array();

    /**
     * TextMessage constructor.
     */
    public function __construct()
    {
        $this->message['msgtype'] = $this->getMessageType();
        $this->setContent();
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content = '')
    {
        $this->message[$this->getMessageType()]['content'] = $content;
        return $this;
    }

    public function getContent() : string
    {
        return $this->message[$this->getMessageType()]['content'];
    }

    /**
     * @return string
     */
    public function getMessageType() :string
    {
        return 'text';
    }


    /**
     * @param string|array $phone  手机号
     * @return $this
     * @throws \TypeError
     */
    public function at($phone)
    {
        if (is_array($phone)) {

        }elseif (is_string($phone)) {
            $phone = array($phone);
        }else {
            throw new \TypeError();
        }
        $this->message['at']['atMobiles'] = isset($this->message['at']['atMobiles'])
            ? array_merge($this->message['at']['atMobiles'], $phone)
            : $phone;

        //update content
        $content = $this->getContent();
        $content .= '@' . implode('@', $phone);
        $this->setContent($content);

        return $this;
    }

    /**
     * @return $this
     */
    public function atAll()
    {
        $this->message['at']['isAtAll'] = true;
        return $this;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return json_encode($this->getMessage());
    }
}