<?php
include 'init.php';

if(!empty($_POST['action']) && $_POST['action'] == 'stats_SaisieNotes') {
    
    if(isset($_POST['eta_code']) && $_POST['eta_code'] !== NULL ){ 
        $_SESSION['eta_code'] = $_POST['eta_code'];
    }
    
    if( isset($_POST['cycle']) && $_POST['cycle'] !== NULL ){ 
        $_SESSION['cycle'] = $_POST['cycle'];
    }
    if( isset($_POST['par_code']) && $_POST['par_code'] !== NULL ){ 
        $_SESSION['par_code'] = $_POST['par_code'];
    }
    if( isset($_POST['ann_code']) && $_POST['ann_code'] !== NULL ){ 
        $_SESSION['ann_code'] = $_POST['ann_code'];
    }
    if( isset($_POST['sem_code']) && $_POST['sem_code'] !== NULL ){ 
        $_SESSION['sem_code'] = $_POST['sem_code'];
    }
    if( isset($_POST['mat_code']) && $_POST['mat_code'] !== NULL ){ 
        $_SESSION['mat_code'] = $_POST['mat_code'];
    }
    if( isset($_POST['ctc_code']) && $_POST['ctc_code'] !== NULL ){ 
        $_SESSION['ctc_code'] = $_POST['ctc_code'];
    }
    if( isset($_POST['matricule_ens']) && $_POST['matricule_ens'] !== NULL ){ 
        $_SESSION['matricule_ens'] = $_POST['matricule_ens'];
    }
$stats->stats_SaisieNotes();
}


if(!empty($_POST['action']) && $_POST['action'] == 'get_Cycles') {
	$_SESSION['eta_code'] = $_POST["eta_code"];
	$stats->get_Cycles();
}                

if(!empty($_POST['action']) && $_POST['action'] == 'get_Parcours') {
	$_SESSION['cycle'] = $_POST["cycle"];
	$stats->get_Parcours();
}                

if(!empty($_POST['action']) && $_POST['action'] == 'get_Annees') {
	$_SESSION['par_code'] = $_POST["par_code"];
	$stats->get_Annees();
}                

if(!empty($_POST['action']) && $_POST['action'] == 'get_Semestres') {
	$_SESSION['ann_code'] = $_POST["ann_code"];
	$stats->get_Semestres();
}                

if(!empty($_POST['action']) && $_POST['action'] == 'get_Matieres') {
	$_SESSION['sem_code'] = $_POST["sem_code"];
	$stats->get_Matieres();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_Examens') {
	$_SESSION['mat_code'] = $_POST["mat_code"];
	$stats->get_Examens();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_Enseignants') {
	$_SESSION['ctc_code'] = $_POST["ctc_code"];
	$stats->get_Enseignants();
}

//----------------------------------------------------------
if(!empty($_POST['action']) && $_POST['action'] == 'getEnseignantMatiereSections') {
	$_SESSION['ctc_code'] = $_POST["ctc_code"];
	$saisie_notes->getEnseignantMatiereSections();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getEnseignantMatiereGroupes') {
	$_SESSION['sec_code'] = $_POST["sec_code"];
	$saisie_notes->getEnseignantMatiereGroupes();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_EnseignantMatieres'){ 
                 $_SESSION['par_code'] = $_POST["par_code"];
	$saisie_notes->get_EnseignantMatieres();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_EnseignantMatiereCtcs'){ 
                 $mat_code =  $_POST["mat_code"]; 
	$_SESSION['mat_code'] = $_POST["mat_code"];
	$saisie_notes->get_EnseignantMatiereCtcs($mat_code);
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_EnseignantMatiereCtcSections'){ 
                 $_SESSION['ctc_code'] = $_POST["ctc_code"];
	$saisie_notes->get_EnseignantMatiereCtcSections();
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_EnseignantMatiereCtcGroupes'){ 
                 $_SESSION['sec_code'] = $_POST["sec_code"];
	$saisie_notes->get_EnseignantMatiereCtcGroupes();
}
?>