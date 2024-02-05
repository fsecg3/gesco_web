<?php
//global $semestre_code;
include 'init.php';

if(!empty($_POST['action']) && $_POST['action'] == 'listNotes') {
    $notes->listNotes();
}

if(!empty($_POST['action']) && $_POST['action'] == 'listNotesSemestre') {
    $_SESSION["sem_code"] = $_POST["sem_code"];
    $notes->listNotes();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getRecoursDetails') {
	$notes->getRecoursDetails();
}
if(!empty($_POST['action']) && $_POST['action'] == 'createRecours') {
                //$_SESSION['mat_code']  = $_POST['uem_ckey'];
                $_SESSION['OK'] = "OK";
                $_POST['action'] = "";
	$notes->createRecours();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_EtudiantSemestres') {
                $_SESSION['par_code']  = $_POST['par_code'];
                $_SESSION['ann_code']  = $_POST['ann_code'];
                $_SESSION['cycle']  = $_POST['cycle'];
                $notes->get_EtudiantSemestres();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_EnseignantRecours') {
                $_SESSION['eta_code']  = $_POST['eta_code'];
                $_SESSION['uem_ckey']  = $_POST['uem_ckey'];
                $_SESSION['ctc_code']  = $_POST['ctc_code'];
                if($_SESSION['ctc_code'] == 'CTC') {$_SESSION['ctc_code'] = 'CC1';}
	$notes->get_EnseignantRecours();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateRecours') {
	$notes->updateRecours($_POST["recoursId"]);
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateRedirectRecours') {
	$notes->updateRedirectRecours($_POST["recoursId"]);
}

if(!empty($_POST['action']) && $_POST['action'] == 'getRecoursDetails') {
///	$notes->recoursId = $_POST["recoursId"];
	$notes->getrecoursDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getRedirectRecoursDetails') {
///	$notes->recoursId = $_POST["recoursId"];
	$notes->getRedirectRecoursDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteRecours') {
    $notes->deleteRecours();
    }
    

?>