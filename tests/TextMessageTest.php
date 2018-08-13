<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/8/10
 * Time: 14:02
 */

namespace Test;


use PHPUnit\Framework\TestCase;
use Wyrine\DingDing\Message\TextMessage;

class TextMessageTest extends TestCase
{
    public function testGetMessageType()
    {
        //Arrange
        $textMessage = new TextMessage();
        $expected = 'text';

        //Act
        $actual = $textMessage->getMessageType();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testGetMessageType
     */
    public function testGetMessage()
    {
        //Arrange
        $textMessage = new TextMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => ''
            )
        );

        //Act
        $actual = $textMessage->getMessage();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testGetMessage
     */
    public function testSetContent()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $actual = $textMessage->setContent('Hello World')->getMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => 'Hello World'
            )
        );

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testGetMessage
     * @throws \TypeError
     */
    public function testAtUseString()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $actual = $textMessage->at('110')->getMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => '@110'
            ),
            'at' => array(
                'atMobiles' => array('110')
            )
        );

        //Assert
        $this->assertEquals($expected, $actual, 'Fist call at() failed');

        //Act
        $actual = $textMessage->at('120')->getMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => '@110@120'
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120'
                )
            )
        );

        //Assert
        $this->assertEquals($expected, $actual, 'Second call at() failed');
    }

    /**
     * @depends testGetMessage
     * @throws \TypeError
     */
    public function testAtUseArray()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $actual = $textMessage->at(array('110', '120'))->getMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => '@110@120'
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120'
                )
            )
        );

        //Assert
        $this->assertEquals($expected, $actual, 'First call at() failed');

        //Act
        $actual = $textMessage->at(array('911'))->getMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => '@110@120@911'
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120',
                    '911'
                )
            )
        );

        //Assert
        $this->assertEquals($expected, $actual, 'Second call at() failed');
    }

    /**
     * @depends testGetMessage
     * @depends testAtUseString
     * @depends testAtUseArray
     * @throws \TypeError
     */
    public function testAtUseStringAndArray()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $actual = $textMessage->at('110')->at(array('120', '911'))->getMessage();
        $expected = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => '@110@120@911'
            ),
            'at' => array(
                'atMobiles' => array(
                    '110',
                    '120',
                    '911'
                )
            )
        );

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \TypeError
     * @throws \TypeError
     */
    public function testAtUseOtherType()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $textMessage->at(new \stdClass());
    }

    /**
     * @depends testGetMessage
     */
    public function testToString()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $actual = $textMessage->toString();
        $expected = json_encode(array(
            'msgtype' => 'text',
            'text' => array(
                'content' => ''
            )
        ));

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testSetContent
     */
    public function testGetContent()
    {
        //Arrange
        $textMessage = new TextMessage();

        //Act
        $expected = 'Hello World';
        $actual = $textMessage->setContent($expected)->getContent();

        //Assert
        $this->assertEquals($expected, $actual);
    }
}