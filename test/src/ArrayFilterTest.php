<?php

namespace Ailixter\Gears\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2018-05-23 at 10:09:11.
 */
class ArrayFilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ArrayFilter
     */
    protected $object;
    private static $filter;

    public static function setUpBeforeClass()
    {
        self::$filter = new Filter([
            'int8' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'max_range' => 127,
                    'min_range' => -128
                ]
            ]
        ]);
    }

    private static $data = [
        'int123' => 123,
        'float12.3' => 12.3,
        'str456' => '456',
        'str45.6' => '45.6',
        'strYes' => 'yes',
        'strNo' => 'no',
        'boolTrue' => true,
        'boolFalse' => false,
    ];

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ArrayFilter(self::$data, self::$filter);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    public function provideGetSame()
    {
        return [
            [123, 'int', 'int123'],
            ['123', 'str', 'int123'],
            [null, 'bool', 'int123'],
            [123.0, 'float', 'int123'],
            [456, 'int', 'str456'],
            ['456', 'str', 'str456'],
            [456.0, 'float', 'str456'],
            [null, 'bool', 'str456'],
            [123, 'int8', 'int123'],
            [false, 'int8', 'str456'],
            [true, 'bool', 'strYes'],
            [false, 'bool', 'strNo'],
            [true, 'bool', 'boolTrue'],
            [false, 'bool', 'boolFalse'],
            [null, 'bool', 'int123'],
            [12.3, 'float', 'float12.3'],
            [false, 'int', 'float12.3'],
            [0, 'int', 'float12.3', 0],
        ];
    }

    /**
     * @dataProvider provideGetSame
     */
    public function testGetSame($expected, $type, $key, $default = null)
    {
        $this->assertSame($expected, $this->object->get($type, $key, $default));
    }

    public function testCall()
    {
        $this->assertSame(123, $this->object->getInt8('int123'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUndefined()
    {
        $this->object->getUndfined('int123');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArgumentCountError()
    {
        $this->object->getUndefined();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnsupported()
    {
        $this->object->Unsupported('int123');
    }

    public function testGetInt()
    {
        $this->assertSame(456, $this->object->getInt('str456'));
    }

    public function testGetFloat()
    {
        $this->assertSame(45.6, $this->object->getFloat('str45.6'));
    }

    public function testGetBool()
    {
        $this->assertSame(true, $this->object->getBool('strYes'));
    }

    public function testGetStr()
    {
        $this->assertSame('456', $this->object->getStr('str456'));
    }

    public function allCastProvider()
    {
        return [
            [['float12.3' => 'str'], ['float12.3' => '12.3']],
            [
                [
                    'int123' => 'str',
                    'float12.3' => 'int',
                    'str456' => 'int',
                    'str45.6' => 'float',
                    'strYes' => 'bool',
                    'strNo' => 'str',
                    'boolTrue' => 'str',
                    'boolFalse' => 'int',
                    'undefined' => 'int'
                ],
                [
                    'int123' => '123',
                    'float12.3' => false,
                    'str456' => 456,
                    'str45.6' => 45.600000000000001,
                    'strYes' => true,
                    'strNo' => 'no',
                    'boolTrue' => '1',
                    'boolFalse' => false,
                    'undefined' => null,
                ]
            ]
        ];
    }

    /**
     * @dataProvider allCastProvider
     * @param array $formats
     * @param array $expected
     */
    public function testCastAll($formats, $expected)
    {
        self::assertSame($expected, $this->object->castAll($formats));
    }
}
