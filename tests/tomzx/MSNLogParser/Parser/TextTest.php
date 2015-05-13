<?php

namespace tomzx\MSNLogParser\test\Parser;

use tomzx\MSNLogParser\Parser\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var \tomzx\MSNLogParser\Parser\Text
	 */
	protected $parser;

	public function setUp()
	{
		parent::setUp();

		date_default_timezone_set('UTC');

		$this->parser = new Text();
	}

	protected function getFixturePath($filename)
	{
		return __DIR__.'/../../../fixtures/'.$filename;
	}

	public function testParse()
	{
		$expected = [
			[
				'date' => 1072396800,
				'participants' => [
					'abc@hotmail.com' => 'a1b2c3d4e5f6g7',
					'def@hotmail.com' => 'b2c3d4e5f6g7h8',
				],
				'messages' => [
					[
						'time' => '23:00:21',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b2c3d4e5f6g7',
						'message' => 'test',
					],
					[
						'time' => '23:00:32',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b2c3d4e5f6g7h8',
						'message' => '* b2c3d4e5f6g7h8 est maintenant Absent(e)',
					],
					[
						'time' => '23:01:35',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'a1b2c3d4e5f6',
						'message' => 'something funny',
					],
					[
						'time' => '23:45:18',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b2c3d4e5f6g7h8',
						'message' => '* b2c3d4e5f6g7h8 est maintenant Hors ligne',
					],
				],
			],
			[
				'date' => 1072483200,
				'participants' => [
					'abc@hotmail.com' => 'a1b2c3d4e5f6g7',
					'def@hotmail.com' => 'b2c3d4e5f6g7h8',
				],
				'messages' => [
					[
						'time' => '01:00:36',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'a1b2c3d4e5f6',
						'message' => 'a?',
					],
					[
						'time' => '01:00:47',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b2c3d4e5f6g7',
						'message' => 'b',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('txt/basic.txt'));
		$this->assertEquals($expected, $actual);
	}

	public function testParseSimpleNick()
	{
		$expected = [
			[
				'date' => 1072396800,
				'participants' => [
					'abc@hotmail.com' => 'a',
					'def@hotmail.com' => 'b',
				],
				'messages' => [
					[
						'time' => '23:00:21',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'a',
						'message' => 'test',
					],
					[
						'time' => '23:00:22',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b',
						'message' => 'test',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('txt/simple-nick.txt'));
		$this->assertEquals($expected, $actual);
	}

	public function testParseSameNick()
	{
		$expected = [
			[
				'date' => 1072396800,
				'participants' => [
					'abc@hotmail.com' => 'a',
					'def@hotmail.com' => 'a',
				],
				'messages' => [
					[
						'time' => '23:00:21',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'a',
						'message' => 'test',
					],
					[
						'time' => '23:00:22',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'a',
						'message' => 'test',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('txt/same-nick.txt'));
		$this->assertEquals($expected, $actual);
	}

	public function testParseLongNick()
	{
		$expected = [
			[
				'date' => 1072396800,
				'participants' => [
					'abc@hotmail.com' => '... some extremely long nickname that is long',
					'def@hotmail.com' => 'b',
				],
				'messages' => [
					[
						'time' => '23:00:21',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'this is what',
						'message' => 'test',
					],
					[
						'time' => '23:00:22',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b',
						'message' => 'test',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('txt/long-nick.txt'));
		$this->assertEquals($expected, $actual);
	}

	public function testParsePartialLongNick()
	{
		$expected = [
			[
				'date' => 1072396800,
				'participants' => [
					'abc@hotmail.com' => '... some extremely long nickname that is long',
					'def@hotmail.com' => 'b',
				],
				'messages' => [
					[
						'time' => '23:00:21',
						'email' => 'abc@hotmail.com',
						'partial-nick' => 'this is some',
						'message' => 'test',
					],
					[
						'time' => '23:00:22',
						'email' => 'def@hotmail.com',
						'partial-nick' => 'b',
						'message' => 'test',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('txt/partial-long-nick.txt'));
		$this->assertEquals($expected, $actual);
	}
}
