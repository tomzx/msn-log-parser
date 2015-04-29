<?php

namespace tomzx\MSNLogParser\Parser;

class Text
{
	/**
	 * @param string $file
	 * @return array
	 */
	public function parse($file)
	{
		$content = file_get_contents($file);
		$content = iconv('Windows-1252', 'UTF-8', $content);
		$lines = preg_split("/\r\n|\r|\n/", $content);

		$sessionDate = null;
		$participants = [];
		$messages = [];
		$state = 'init';

		foreach ($lines as $line) {
			$initialState = $state;
			switch ($state) {
				case 'init':
					$sessionDate = null;
					$participants = [];
					$messages = [];
					$state = 'header-date';
				case 'header-date':
					if (preg_match('/Début de la session : (?<day>\S+) (?<month>\S+) (?<year>\S+)/', $line, $matches)) {
						$formattedDate = $matches['year'].'-'.$this->fullMonthNameToNumber($matches['month']).'-'.$matches['day'];
						$sessionDate = strtotime($formattedDate); // Convert month to number
						$state = 'header-participants';
					}
					break;
				case 'header-participants':
					if (preg_match('/^\|    (?<nick>.*) \((?<email>.*)\)/', $line, $matches)) {
						$partialNick = $matches['nick'];
						$email = $matches['email'];
						$participants[$email] = $partialNick;
					} elseif (preg_match('/Participants :/', $line, $matches)) {
						// Stay in the same state
					} else {
						$state = 'body';
					}
					break;
				case 'body':
					if (preg_match('/\[(?<time>\S+)\] (?<nick>.*): (?<message>.*)/', $line, $matches)) {
						$time = $matches['time'];
						$nick = $matches['nick'];
						$message = $matches['message'];

						if ( ! empty($messages)) {
							$entry = $messages[sizeof($messages) - 1];
							echo '['.date('Y-m-d', $sessionDate).'T'.$entry['time'].'] '.$entry['nick'].': '.$entry['message'].PHP_EOL;
						}

						$messages[] = [
							'time' => $time,
							'nick' => $nick,
							'message' => $message
						];
					} elseif (strpos($line, '.--------------------------------------------------------------------.') !== false) {
						$state = 'init';
					} elseif (preg_match('/           (?<message>.*)/', $line, $matches)) {
						// continuation of previous message
						$message = $matches['message'];
						$messages[sizeof($messages) - 1]['message'] .= ' '.$message;
					}
					break;
			}
			// echo $initialState.' -> '.$state.PHP_EOL;
		}

		if ( ! empty($messages)) {
			$entry = $messages[sizeof($messages) - 1];
			echo '['.date('Y-m-d', $sessionDate).'T'.$entry['time'].'] '.$entry['nick'].': '.$entry['message'].PHP_EOL;
		}
	}

	/**
	 * @param string $month
	 * @return string|null
	 */
	protected function fullMonthNameToNumber($month)
	{
		$mapping = [
			'janvier' => '01',
			'février' => '02',
			'mars' => '03',
			'avril' => '04',
			'mai' => '05',
			'juin' => '06',
			'juillet' => '07',
			'août' => '08',
			'septembre' => '09',
			'octobre' => '10',
			'novembre' => '11',
			'décembre' => '12',
		];

		return isset($mapping[$month]) ? $mapping[$month] : null;
	}
}
