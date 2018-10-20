<?php

namespace Ailixter\Gears\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2018-05-23 at 12:37:05.
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Filter
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Filter;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Ailixter\Gears\Filter\Filter::cast
     * @todo   Implement testCast().
     *
    public function testCast()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Ailixter\Gears\Filter\Filter::castItem
     * @todo   Implement testCastItem().
     *
    public function testCastItem()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
     * 
     */

    public function testCastInput()
    {
        $this->assertInternalType('string', $this->object->castInput(FILTER_DEFAULT, INPUT_SERVER, 'PHP_SELF'));
    }

    public function provideCastArray()
    {
        $int1 = FILTER_VALIDATE_INT;
        $int2 = [
            'filter' => FILTER_VALIDATE_INT,
            //'flags' => FILTER_FORCE_ARRAY,//FILTER_REQUIRE_SCALAR,
            'options' => [//???
                'max_range' => 12,
                'min_range' => 0,
                'default'   => -1
            ]
        ];
        return [
            [
                ['a' => 123,   'b' => 456,   'c' => true,   'd' => null],
                ['a' => $int1, 'b' => 'int', 'c' => 'bool', 'd' => 'int'],
                ['a' => '123', 'b' => '456', 'c' => 'yes']
            ],
//            [
//                ['a' => false, 'b' => 456,   'c' => true,   'd' => null],
//                ['a' => $int2, 'b' => 'int', 'c' => 'bool', 'd' => 'int'],
//                ['a' => '123', 'b' => '456', 'c' => 'yes']
//            ],
            [
                ['a' => '123', 'b' => 456,   'c' => true,   'd' => 1],
                ['a' => 'str', 'b' => 'int', 'c' => 'bool', 'd' => 'int'],
                ['a' => 123,   'b' => '456', 'c' => 'yes',  'd' => true]
            ],
        ];
    }

    /**
     * @dataProvider provideCastArray
     * @param type $expected
     * @param type $casts
     * @param type $array
     */
    public function testCastArray($expected, $casts, $array)
    {
        $this->assertSame($expected, $this->object->castArray($casts, $array));
    }
}