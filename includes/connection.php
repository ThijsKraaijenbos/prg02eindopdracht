<?php
// Gegevens voor de connectie
$host       = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'eindopdracht_PRG02';

// Stap 1: Verbinding leggen met de database
// Stap 2: Foutafhandeling. Als verbinding niet gelukt is, wordt
//         "or die" uitgevoerd. Deze stopt de code en toont de
//         foutmelding op het scherm
$db = mysqli_connect($host, $username, $password, $database)
or die('Error: '.mysqli_connect_error());