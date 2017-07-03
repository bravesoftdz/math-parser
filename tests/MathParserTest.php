<?php

class MathParserTest extends PHPUnit_Framework_TestCase
{

    private $testList = [
        ['formula' => "40*-1", 'result' => '-40'],
        ['formula' => "100*2-14*54-55", 'result' => '-611'],
        ['formula' => "100*(2-1)*4*(4-55)*12-(15-2)", 'result' => '-244813'],
        ['formula' => "(40*-1)", 'result' => '-40'],
        ['formula' => "-40-10", 'result' => '-50'],
        ['formula' => "(-10-(9+1))", 'result' => '-20'],
        ['formula' => "(((1+1)*(2+2))/2)*5-(-9-1)", 'result' => '10'],
        ['formula' => "(1+3+(46+2*1*2))-(2+3+(2+3))", 'result' => '44'],
    ];

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testMathParser()
    {
        $calc = new mathParser();
        foreach ($this->testList as $i => $item) {
            $result = $calc->calc($item['formula']);
            $this->assertTrue($result == $item['result']);
        }
    }
}
