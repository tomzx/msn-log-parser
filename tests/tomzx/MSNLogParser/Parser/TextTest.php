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
				'date' => 1072414800,
				'participants' => [
					'abc@hotmail.com' => 'a1b2c3d4e5f6g7',
					'def@hotmail.com' => 'b2c3d4e5f6g7h8',
				],
				'messages' => [
					[
						'time' => '23:00:21',
						'email' => null,
						'partial-nick' => 'b2c3d4e5f6g7',
						'message' => 'test',
					],
					[
						'time' => '23:00:32',
						'email' => null,
						'partial-nick' => 'b2c3d4e5f6g7h8',
						'message' => '* b2c3d4e5f6g7h8 est maintenant Absent(e)',
					],
					[
						'time' => '23:01:35',
						'email' => null,
						'partial-nick' => 'a1b2c3d4e5f6',
						'message' => 'something funny',
					],
					[
						'time' => '23:45:18',
						'email' => null,
						'partial-nick' => 'b2c3d4e5f6g7',
						'message' => '* b2c3d4e5f6g7 est maintenant Hors ligne',
					],
				],
			],
			[
				'date' => 1072501200,
				'participants' => [
					'abc@hotmail.com' => 'a1b2c3d4e5f6g7',
					'def@hotmail.com' => 'b2c3d4e5f6g7h8',
				],
				'messages' => [
					[
						'time' => '01:00:36',
						'email' => null,
						'partial-nick' => 'a1b2c3d4e5f6',
						'message' => 'a?',
					],
					[
						'time' => '01:00:47',
						'email' => null,
						'partial-nick' => 'b2c3d4e5f6g7h8',
						'message' => 'b',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('txt/basic.txt'));
		$this->assertEquals($expected, $actual);
	}
}