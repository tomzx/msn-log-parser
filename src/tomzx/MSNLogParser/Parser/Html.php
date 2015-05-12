<?php

namespace tomzx\MSNLogParser\Parser;

class Html
{
	/**
	 * @param string $file
	 * @return array
	 */
	public function parse($file)
	{
		$content = file_get_contents($file);
		$content = iconv('UTF-16LE', 'UTF-8', $content);
		$lines = preg_split("/\r\n|\r|\n/", $content);

		$sessionDate = null;
		$participants = [];
		$mappedParticipants = [];
		$sessions = [];
		$session = [];
		$state = 'init';

		foreach ($lines as $line) {
			switch ($state) {
				case 'init':
					$sessionDate = null;
					$participants = [];
					$mappedParticipants = [];
					$session = [];
					$state = 'header-date';
				case 'header-date':
					if (preg_match('/<div class="mplsession" id="Session_(?<year>.*)-(?<month>.*)-(?<day>.*)T/', $line, $matches)) {
						$sessionDate = mktime(0, 0, 0, $matches['month'], $matches['day'], $matches['year']);
						$session['date'] = $sessionDate;
						$state = 'header-participants';
					}
					break;
				case 'header-participants':
					if (preg_match('/<li.*>(?<nick>.*) <span>\((?<email>.*)\)/', $line, $matches)) {
						$nick = $matches['nick'];
						$email = $matches['email'];
						$participants[$email] = $nick;
						$session['participants'][$email] = $nick;
					} elseif (strpos($line, '</ul>') !== false) {
						$state = 'body';
					}
					break;
				case 'body':
					if (preg_match('/time">\((?<time>.*)\)<\/span> (?<partialNick>.*) :<\/th>.*?>(?<message>.*)<\/td>/', $line, $matches)) {
						$partialNick = $matches['partialNick'];
						$mappedParticipants = $this->mapParticipant($mappedParticipants, $participants, $partialNick);

						$matches['message'] = str_replace('&nbsp;', ' ', $matches['message']);
						$matches['message'] = preg_replace('/<img.*alt="(.*)"\/>/', '$1', $matches['message']);

						//echo '['.date('Y-m-d', $sessionDate).'T'.$matches['time'].'] ('.$mappedParticipants[$partialNick].') '.$matches['partialNick'].' : '.$matches['message'].PHP_EOL;
						$session['messages'][] = [
							'time' => $matches['time'],
							'email' => $mappedParticipants[$partialNick],
							'partial-nick' => $matches['partialNick'],
							'message' => $matches['message'],
						];
					} elseif (strpos($line, '</div>') !== false) {
						$sessions[] = $session;
						$state = 'init';
					}
					break;
			}
		}

		return $sessions;
	}

	protected static function mapParticipant(array $currentMapping, array $participants, $partialNick)
	{
		if (isset($currentMapping[$partialNick])) {
			return $currentMapping;
		}

		// TODO: We could manage a list of remaining participants instead of trying to match through all of them
		foreach ($participants as $email => $nick) {
			if (strpos($nick, $partialNick) === 0) {
				$currentMapping[$partialNick] = $email;
				break;
			}
		}

		return $currentMapping;
	}
}
