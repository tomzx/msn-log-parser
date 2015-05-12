<?php

namespace tomzx\MSNLogParser\test\Parser;

use tomzx\MSNLogParser\Parser\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var \tomzx\MSNLogParser\Parser\Html
	 */
	protected $parser;

	public function setUp()
	{
		parent::setUp();

		date_default_timezone_set('UTC');

		$this->parser = new Html();
	}

	protected function getFixturePath($filename)
	{
		return __DIR__.'/../../../fixtures/'.$filename;
	}

	public function testParse()
	{
		$expected = [
			[
				'date' => 1176940800,
				'participants' => [
					'a@hotmail.com' => 'abcd',
					'01234567890abcdef0@hotmail.com' => '01234567890abcdef0',
				],
				'messages' => [
					[
						'time' => '17:27',
						'email' => '01234567890abcdef0@hotmail.com',
						'partial-nick' => '01234567890abcdef',
						'message' => 'hey?',
					],
				],
			],
			[
				'date' => 1177286400,
				'participants' => [
					'a@hotmail.com' => 'abcd',
					'01234567890abcdef0@hotmail.com' => '01234567890abcdef0',
				],
				'messages' => [
					[
						'time' => '19:13',
						'email' => 'a@hotmail.com',
						'partial-nick' => 'abcd',
						'message' => 'hey',
					],
					[
						'time' => '19:13',
						'email' => '01234567890abcdef0@hotmail.com',
						'partial-nick' => '01234567890abcdef',
						'message' => 'hey',
					],
				],
			]
		];

		$actual = $this->parser->parse($this->getFixturePath('html/basic.html'));
		$this->assertEquals($expected, $actual);
	}
}