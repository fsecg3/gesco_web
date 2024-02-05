<?php
include 'init.php';

if(!empty($_POST['action']) && $_POST['action'] == 'listUser') {
	//echo '<script>alert("listUser-actio")</script>';
	$users->listUser();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getUserDetails') {
	$users->id = $_POST["userId"];
	$users->getUserDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addUser') {
	$users->userNomPrenom = $_POST["nom_ar"] . " " . $_POST["prenom_ar"];
	$users->username = $_POST["username"];
	$users->nom = $_POST["nom"];
	$users->prenom = $_POST["prenom"];
	$users->nom_ar = $_POST["nom_ar"];
	$users->prenom_ar = $_POST["prenom_ar"];
	$users->sexe = $_POST["sexe"];
			
	$users->email = $_POST["email"];
    $users->departement = $_POST["departement"];
	$users->cycle = $_POST["cycle"];        
	$users->user_type = $_POST["user_type"];
	$users->password = $_POST["password"];
        $users->status = '1'; //$_POST["status"];   
	$users->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateUser') {
	$users->updateUserId = $_POST["userId"]; 
	$users->userNomPrenom = $_POST["nom_ar"] . " " . $_POST["prenom_ar"];
	$users->username = $_POST["username"];
	$users->nom = $_POST["nom"];
	$users->prenom = $_POST["prenom"];
	$users->nom_ar = $_POST["nom_ar"];
	$users->prenom_ar = $_POST["prenom_ar"];
	$users->sexe = $_POST["sexe"];
	$users->email = $_POST["email"];
	$users->departement = $_POST["departement"];
	$users->cycle = $_POST["cycle"];
	$users->user_type = $_POST["user_type"];
	$users->password = $_POST["password"];
    $users->status = '1'; //$_POST["status"]; 
	$users->update();
	//echo '<script>alert("Update User")</script>';
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
	$users->deleteUserId = $_POST["userId"];
	$users->delete();
}

?>