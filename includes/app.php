<?php

// Conectarnos a la base de datos
use Model\ActiveRecord;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'functions.php';
require 'database.php';



ActiveRecord::setDB($db);

// Translations
$translations = require 'Config.php';
