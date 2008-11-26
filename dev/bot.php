<?php
// Backup argv, otherwise stripped by agavi
$args = $_SERVER['argv'];

require('../vendor/agavi/agavi.php');
require('../app/config.php');

Agavi::bootstrap('development-digitarald');

// Prevent PHP from stopping the script after 30 sec
set_time_limit(0);

class Bot
{
	protected $_options = array(
		'server' => 'irc.freenode.net',
		'port' => 6667,
		'username' => 'RedRacer',
		'hostname' => 'redracer.org',
		'servername' => 'RedRacer',
		'realname' => 'RedRacer Bot',
		'nick' => 'RedRacer',
		'channels' => array('#agavi', '#mootools')
	);

	protected $_socket;

	public function connect()
	{
		// Open the socket to the IRC server
		$this->_socket = fsockopen($this->_options['server'], $this->_options['port']);

		if (is_readable('log.txt')) {
			unlink('log.txt');
		}

		sleep(1);

		// Doctrine_Manager::connection('sqlite::memory:');

		// Send auth info
		$this->execute('USER %s %s %s :%s', array(
		   $this->_options['username'],
		   $this->_options['hostname'],
		   $this->_options['servername'],
		   $this->_options['realname']
		));

		$this->execute('NICK %s', array($this->_options['nick']));

		foreach ($this->_options['channels'] as $channel) {
			$this->join($channel);
		}
		$this->say('#mootools', 'Moorning');
		$this->say('#agavi', 'Huomenta');
	}
	public function execute($command, array $args = array())
	{
		$command = vsprintf($command, $args);

		fputs($this->_socket, $command . "\n");

		$this->log('> ' . $command);
	}
	public function log($command)
	{
		$fp = fopen('log.txt', 'a+');

		fwrite($fp, $command . "\n");

		fclose($fp);
	}
	public function disconnect($msg = '')
	{
		$this->execute('QUIT');

		fclose($this->_socket);
	}
   // IRC Functions [BEGIN]

	// Joins channel
	public function join($channel)
	{
		$this->execute('JOIN %s', array($channel));
	}

	// Leaves the channel
	public function part($channel){
		$this->execute('PART %s', array($channel));
	}

	// send message to channel/user
	public function say($to, $msg){
		$this->execute('PRIVMSG %s :%s', array($to, $msg));
	}

	// modes: +o, -o, +v, -v, etc.
	public function setMode($user, $mode){
		$this->execute('MODE %s %s %s', array($this->channel, $mode, $user));
	}
	// kicks user from the channel
	public function kick($user, $from, $reason = "")
	{
		$this->execute('KICK %s %s :%s', array($from, $user, $reason));
	}
	// changes the channel topic
	public function topic($channel, $topic)
	{
		$this->execute('TOPIC %s :%s', array($channel, $topic));
	}
	public function run()
	{
		$this->connect();
		// Force an endless while

		while( ! feof($this->_socket)) {

			// Continue the rest of the script here
			$data = fgets($this->_socket, 4096);

			print $data . "";
			// Separate all data
			$ex = explode(' ', $data);

			// Send PONG back to the server
			if ($ex[0] == 'PING') {
				$this->execute('PONG %s', array($ex[1]));
			}
			//$this->log($data);

			// Say something in the channel
			$command = str_replace(array(chr(10), chr(13)), '', $ex[3]);

			// strip out ':'
			$command = substr($command, 1);

			array_shift($ex);
			array_shift($ex);
			$scope = array_shift($ex);
			array_shift($ex);

			$argsStr = implode(' ', $ex);

			$this->log($command . ' ' . $scope);

			switch ($command) {
				case '!r.quit':
					$this->disconnect('redracer.org');
					exit;
				case '!r.mootools':
					$this->say($scope, 'MooTools is a compact, modular, Object-Oriented JavaScript framework designed for the intermediate to advanced JavaScript developer. It allows you to write powerful, flexible, and cross-browser code with its elegant, well documented, and coherent API.');
					break;
				case '!r.who':
					$this->say($scope, 'RedRacer is a web-based source code repository, aggregating community contributions. [ Agavi ' . AgaviConfig::get('agavi.version') . ' ]');
					break;
				case '!r.status':
					$this->say($scope, 'Check http://www.redracer.org for commits and updates. Don`t ask the authors every day!');
					break;
			}
		}
	}
}

$bot = new Bot();
$bot->run();
