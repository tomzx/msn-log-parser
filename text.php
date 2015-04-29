<?php

require 'vendor/autoload.php';

$textParser = new tomzx\MSNLogParser\Parser\Text();
$textParser->parse($argv[1]);