<?php

require 'vendor/autoload.php';

$textParser = new tomzx\MSNLogParser\Parser\Text();
echo json_encode($textParser->parse($argv[1]), JSON_PRETTY_PRINT);