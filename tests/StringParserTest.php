<?php

use TheFox\Imap\StringParser;

class StringParserTest extends PHPUnit_Framework_TestCase{
	
	public function testBasic1(){
		$str = '-ABC.';
		$this->assertEquals('-', $str[0]);
		$this->assertEquals('A', $str[1]);
		$this->assertEquals('.', $str[4]);
	}
	
	public function testBasic2(){
		$str = 'arg1 arg2 arg3 "arg4" ';
		$this->assertEquals(22, strlen($str));
		$this->assertEquals(21, strlen(trim($str)));
	}
	
	public function providerParse(){
		$rv = array();
		
		$expect = array('arg1', 'arg2', 'arg3', 'arg4');
		$rv[] = array('arg1 arg2 arg3 arg4', $expect, null);
		
		$expect = array('arg1', 'arg2', 'arg3 arg4');
		$rv[] = array('arg1 arg2 arg3 arg4', $expect, 3);
		$rv[] = array('arg1  arg2 arg3 arg4', $expect, 3);
		$rv[] = array('arg1 arg2  arg3 arg4', $expect, 3);
		$rv[] = array('arg1  arg2  arg3 arg4', $expect, 3);
		
		$expect = array('arg1', 'arg2', 'arg3  arg4');
		$rv[] = array('arg1 arg2 arg3  arg4', $expect, 3); # 5
		$rv[] = array('arg1  arg2  arg3  arg4', $expect, 3);
		
		$expect = array('arg1', 'arg2', 'arg3', 'arg4');
		$rv[] = array('arg1 arg2 arg3 "arg4"', $expect, 4); # 7
		$rv[] = array('arg1 arg2 "arg3" arg4', $expect, 4);
		$rv[] = array('arg1 arg2  "arg3" arg4', $expect, 4);
		$rv[] = array('arg1 arg2 "arg3"  arg4', $expect, 4);
		$rv[] = array('arg1 arg2  "arg3"  arg4', $expect, 4);
		$rv[] = array('arg1 arg2 arg3 "arg4"', $expect, 4);
		$rv[] = array('arg1 arg2 arg3 "arg4" ', $expect, 4); # 13
		$rv[] = array('arg1 arg2 arg3  "arg4" ', $expect, 4);
		$rv[] = array('arg1 arg2 "arg3" "arg4"', $expect, 4);
		
		$expect = array('arg1', 'arg2', 'arg3  arg4', 'arg5');
		$rv[] = array('arg1  arg2  "arg3  arg4" arg5', $expect, 5); # 16
		
		$expect = array('arg1', 'arg2', 'arg3 arg4');
		$rv[] = array('arg1 arg2 "arg3 arg4"', $expect, 3); #17
		$rv[] = array('arg1 arg2 "arg3 arg4" ', $expect, 3);
		$rv[] = array('arg1 arg2  "arg3 arg4" ', $expect, 3);
		$rv[] = array('arg1 arg2  "arg3 arg4"', $expect, 3);
		
		$expect = array('arg1', 'arg2', '0');
		$rv[] = array('arg1 arg2 0', $expect, 3);
		
		$expect = array('arg1', 'arg2', 0);
		$rv[] = array('arg1 arg2 0', $expect, 3);
		
		$expect = array('arg1', 'arg2', '000');
		$rv[] = array('arg1 arg2 000', $expect, 3);
		
		$expect = array('arg1', 'arg2', '123');
		$rv[] = array('arg1 arg2 123', $expect, 3);
		
		$expect = array('arg1', 'arg2', '0123');
		$rv[] = array('arg1 arg2 0123', $expect, 3);
		
		$expect = array('arg1', 'arg2', 'arg3 (arg4 "arg5 arg6") arg7');
		$rv[] = array('arg1 arg2 arg3 (arg4 "arg5 arg6") arg7', $expect, 3);
		
		$expect = array('arg1', 'arg2', 'arg3 ("arg5 arg6" arg4) arg7');
		$rv[] = array('arg1 arg2 arg3 ("arg5 arg6" arg4) arg7', $expect, 3);
		
		$expect = array('arg1', 'arg2', 'A"arg3"E');
		$rv[] = array('arg1 arg2 A"arg3"E', $expect, 3);
		
		return $rv;
	}
	
	/**
     * @dataProvider providerParse
     * @group large
     */
	public function testParse($msgRaw, $expect, $argsMax = null){
		$str = new StringParser($msgRaw, $argsMax);
		$this->assertEquals($expect, $str->parse());
	}
	
}