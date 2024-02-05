<?php
include 'init.php';
//$act = $_POST['action'];
if(!empty($_POST['action']) && $_POST['action'] == 'auth') {
	$users->login();
}
if(!empty($_POST['action']) && $_POST['action'] == 'listRecours') {
	$recours->showRecours();
}

if(!empty($_POST['action']) && $_POST['action'] == 'refreshRecours') {
    $_SESSION['type_recours'] = $_POST['type_recours'];
//	$recours->showRecours();
}

if(!empty($_POST['action']) && $_POST['action'] == 'refreshRecours_Cycle') {
    $_SESSION['cycle_recours'] = $_POST['cycle'];
//	$recours->showRecours();
}

if(!empty($_POST['action']) && $_POST['action'] == 'createRecours1') {
	$recours->createRecours();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getRecoursDetails') {
	$recours->getRecoursDetails();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateRecours') {
	$recours->updateRecours();
}
if(!empty($_POST['action']) && $_POST['action'] == 'closeRecours') {
	$recours->closeRecours();
}
if(!empty($_POST['action']) && $_POST['action'] == 'saveRecoursReplies') {
	$recours->saveRecoursReplies();
}
if(!empty($_POST['action']) && $_POST['action'] == 'cancelRecoursReplies') {
	$recours->saveRecoursReplies();
}

?>