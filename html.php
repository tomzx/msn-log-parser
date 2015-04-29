<?php

require 'vendor/autoload.php';

$htmlParser = new tomzx\MSNLogParser\Parser\Html();
$htmlParser->parse($argv[1]);