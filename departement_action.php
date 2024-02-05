<?php
include 'init.php';

if(!empty($_POST['action']) && $_POST['action'] == 'listDepartement') {
	$departement->listDepartement();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getDepartementDetails') {
	$departement->departementId = $_POST["departementId"];
	$departement->getDepartementDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addDepartement') {
	$departement->dep_desl = $_POST["dep_desl"];
	$departement->dep_desl_ar = $_POST["dep_desl_ar"];
	$departement->status = $_POST["status"];    
	$departement->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateDepartement') {
	$departement->departementId = $_POST["departementId"]; 
	$departement->dep_desl = $_POST["dep_desl"];
	$departement->dep_desl_ar = $_POST["dep_desl_ar"];
	$departement->status = $_POST["status"]; 
	$departement->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteDepartement') {
	$departement->departementId = $_POST["departementId"];
	$departement->delete();
}

?>