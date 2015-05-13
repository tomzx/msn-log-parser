<?php

require 'vendor/autoload.php';

$htmlParser = new tomzx\MSNLogParser\Parser\Html();
echo json_encode($htmlParser->parse($argv[1]), JSON_PRETTY_PRINT);
