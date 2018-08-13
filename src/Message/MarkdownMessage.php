<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/10
 * Time: 16:17
 */

namespace Wyrine\DingDing\Message;
use Wyrine\DingDing\DingDingMessage;


/**
 * Class MarkdownMessage
 * @package Wyrine\message
 */
class MarkdownMessage implements DingDingMessage
{
    /**
     * @var string
     */
    private $template = "
        #### %s%s
        ![图片](%s)
    ";

    /**
     * @var array
     */
    private $message = array();

    /**
     * @var
     */
    private $title;

    /**
     * @var
     */
    private $at;

    /**
     * @var
     */
    private $picUrl;

    /**
     * MarkdownMessage constructor.
     */
    public function __construct()
    {
        $this->message['msgtype'] = $this->getMessageType();
        $this->setTitle();
        $this->setTextPicUrl();
    }

    /**
     * @param string $title
     * @return MarkdownMessage
     */
    public function setTitle(string $title = '')
    {
        $this->title = $title;
        $this->message[$this->getMessageType()]['title'] = $this->title;

        $text = sprintf($this->template, $this->title, $this->at, $this->picUrl);
        return $this->setOriginText($text);
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setOriginText(string $text = '')
    {
        $this->message[$this->getMessageType()]['text'] = $text;
        return $this;
    }

    /**
     * @param string $picUrl
     * @return MarkdownMessage
     */
    public function setTextPicUrl(string $picUrl = '')
    {
        $this->picUrl = $picUrl;

        $text = sprintf($this->template, $this->title, $this->at, $this->picUrl);
        return $this->setOriginText($text);
    }

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        return 'markdown';
    }

    /**
     * @param $phone
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

        //update message
        $this->at .= '@' . implode('@', $phone);
        $text = sprintf($this->template, $this->title, $this->at, $this->picUrl);

        return $this->setOriginText($text);
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
        return json_encode($this->trimText($this->getMessage()));
    }

    private function trimText(array $markdown)
    {
        if (isset($markdown['markdown']['text'])) {
            $text = $markdown['markdown']['text'];
            //todo
            //这儿explode只能用"\n" 使用常量PHP_EOL就不行
            $markdown['markdown']['text'] = implode('', array_map('trim', explode("\n", $text)));
        }

        return $markdown;
    }
}