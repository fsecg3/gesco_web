<?php
include 'init.php';

if(!empty($_POST['action']) && $_POST['action'] == 'listTypeRecours') {
	$typerecours->listTypeRecours();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getTypeRecoursDetails') {
//	$typerecours->typerecoursId = $_POST["typerecoursId"];
	$typerecours->getTypeRecoursDetails($_POST["typerecoursId"]);
}

if(!empty($_POST['action']) && $_POST['action'] == 'addTypeRecours') {
	$typerecours->ldc_code = $_POST["ldc_code"];
	$typerecours->ldc_type = "REC_TYPE";
	$typerecours->ldc_desl = $_POST["ldc_desl"];
	$typerecours->ldc_desl_ar = $_POST["ldc_desl_ar"];
	//$departement->status = $_POST["status"];    
	$typerecours->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateTypeRecours') {
	$typerecours->typerecoursId = $_POST["typerecoursId"]; 
	$typerecours->ldc_code = $_POST["ldc_code"]; 
	$typerecours->dep_desl = $_POST["ldc_desl"];
	$typerecours->dep_desl_ar = $_POST["ldc_desl_ar"];
	//$typerecours->status = $_POST["status"]; 
	$typerecours->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteTypeRecours') {
	$typerecours->typerecoursId = $_POST["typerecoursId"];
	$typerecours->delete();
}

?>