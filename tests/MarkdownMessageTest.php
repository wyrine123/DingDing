<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/11
 * Time: 14:58
 */

namespace Test;


use PHPUnit\Framework\TestCase;
use Wyrine\DingDing\Message\MarkdownMessage;

class MarkdownMessageTest extends TestCase
{
    /**
     *
     */
    public function testGetMessageType()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();
        $expected = 'markdown';

        //Act
        $actual = $markdownMessage->getMessageType();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testGetMessageType
     */
    public function testConstruct()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### 
                    ![二维码]()
                '
            )
        );
        $expected = $this->trimText($expected);

        //Act
        $actual = $markdownMessage->getMessage();
        $actual = $this->trimText($actual);

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testConstruct
     */
    public function testSetTitle()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();
        $title = 'Test';
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => $title,
                'text' => '
                    #### ' . $title . '
                    ![二维码]()
                '
            )
        );
        $expected = $this->trimText($expected);

        //Act
        $actual = $markdownMessage->setTitle($title)->getMessage();
        $actual = $this->trimText($actual);

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testConstruct
     */
    public function testSetTextPicUrl()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();
        $picUrl = 'https://www.flytothemoon.com';
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### 
                    ![二维码](' . $picUrl . ')
                '
            )
        );
        $expected = $this->trimText($expected);

        //Act
        $actual = $markdownMessage->setTextPicUrl($picUrl)->getMessage();
        $actual = $this->trimText($actual);

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testConstruct
     */
    public function testSetOriginText()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();
        $text = 'Hello World';
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => $text
            )
        );
        $expected = $this->trimText($expected);

        //Act
        $actual = $markdownMessage->setOriginText($text)->getMessage();
        $actual = $this->trimText($actual);

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testConstruct
     * @depends testSetOriginText
     */
    public function testAtUseString()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();

        //Act
        $actual = $markdownMessage->at('110')->getMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### @110
                    ![二维码]()
                '
            ),
            'at' => array(
                'atMobiles' => array(
                    '110'
                )
            )
        );
        $actual = $this->trimText($actual);
        $expected = $this->trimText($expected);

        //Assert
        $this->assertEquals($expected, $actual, 'testAtUseString() test 1 failed');

        //Act
        $actual = $markdownMessage->at('120')->getMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### @110@120
                    ![二维码]()
                '
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120'
                )
            )
        );
        $actual = $this->trimText($actual);
        $expected = $this->trimText($expected);

        //Assert
        $this->assertEquals($expected, $actual, 'testAtUseString() test 2 failed');

    }

    /**
     * @expectedException \TypeError
     */
    public function testAtUseWrongParam()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();

        //Act
        $markdownMessage->at(new \stdClass());
    }

    public function testAtUseArray()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();

        //Act
        $actual = $markdownMessage->at(array('110', '120'))->getMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### @110@120
                    ![二维码]()
                '
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120'
                )
            )
        );
        $actual = $this->trimText($actual);
        $expected = $this->trimText($expected);

        //Assert
        $this->assertEquals($expected, $actual, 'testAtUseArray() test 1 failed');

        //Act
        $actual = $markdownMessage->at(array('911', '12306'))->getMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### @110@120@911@12306
                    ![二维码]()
                '
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120',
                    '911',
                    '12306'
                )
            )
        );
        $actual = $this->trimText($actual);
        $expected = $this->trimText($expected);

        //Assert
        $this->assertEquals($expected, $actual, 'testAtUseArray() test 2 failed');
    }

    /**
     * @depends testAtUseString
     * @depends testAtUseArray
     */
    public function testAtUseStringAndArray()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();

        //Act
        $actual = $markdownMessage->at('110')->at(array('120', '911'))->getMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### @110@120@911
                    ![二维码]()
                '
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120',
                    '911'
                )
            )
        );
        $actual = $this->trimText($actual);
        $expected = $this->trimText($expected);

        //Assert
        $this->assertEquals($expected, $actual);
    }


    public function testAtAll()
    {
        //Arrange
        $markdownMessage = new MarkdownMessage();

        //Act
        $markdownMessage = new MarkdownMessage();

        //Act
        $actual = $markdownMessage->atAll()->getMessage();
        $expected = array(
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => '',
                'text' => '
                    #### 
                    ![二维码]()
                '
            ),
            'at' => array(
                'isAtAll' => true
            )
        );
        $actual = $this->trimText($actual);
        $expected = $this->trimText($expected);

        //Assert
        $this->assertEquals($expected, $actual);
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