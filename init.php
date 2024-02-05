<?php
session_start();
include 'config.php';
define('HOST', $host);
define('USER', $username);
define('PASSWORD', $password);
define('DATABASE', $database);
require 'class/Database.php';
require 'class/Site.php';
require 'class/Users.php';
require 'class/Time.php';
require 'class/Recours.php';
require 'class/Departements.php';
require 'class/Notes.php';
require 'class/Type_recours.php';
require 'class/SaisieNotes.php';
require 'class/Stats.php';

$_SESSION['DB'] = strtoupper($database);

$database = new Database;
$site = new Site;
$users = new Users;
$time = new Time;
$departement = new Departement;
$recours = new Recours;
$notes = new EtudiantNotes;
$typerecours = new Type_recours;
$saisie_notes = new SaisieNotes;
$stats = new Stats;

//define('UNIVSERSITY', '3 جامعة الجزائر');
//define('FACULTY', 'كلية العلوم الإقتصادية و العلوم التجارية و علوم التسيير'); 

//define('UNIVERSITY', 'جامعة الجيلالي بونعامة - خميس مليانة');
//define('FACULTY', 'كلية العلوم الإجتماعية والإنسانية');

?>
