<?php
include 'init.php';

if(!empty($_POST['action']) && $_POST['action'] == 'listSaisieNotes') {
    /*$_SESSION['mat_code'] = '*';
    $_SESSION['ctc_code'] = '*';
    $_SESSION['sec_code'] = '0';
    $_SESSION['grp_code'] = '0';
    */
    if(isset($_POST['mat_code']) && $_POST['mat_code'] !== NULL ){ 
        $_SESSION['mat_code'] = $_POST['mat_code'];
    }
    
    if( isset($_POST['mat_code']) && $_POST['ctc_code'] !== NULL ){ 
        //$_SESSION['ctc_code'] = $_POST['ctc_code'];
    }
    if( isset($_POST['sec_code']) && $_POST['sec_code'] !== NULL ){ 
        $_SESSION['sec_code'] = $_POST['sec_code'];
    }
    if( isset($_POST['grp_code']) && $_POST['grp_code'] !== NULL ){ 
        $_SESSION['grp_code'] = $_POST['grp_code'];
    }
    
$saisie_notes->listSaisieNotes();
$saisie_notes->Firsttime();
}

if(!empty($_POST['action']) && $_POST['action'] == 'ExportNotesExcel') {
	//$departement->departementId = $_POST["departementId"];
	$saisie_notes->ExportNotesExcel();
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

if(!empty($_POST['action']) && $_POST['action'] == 'getEnseignantMatiereCtcs') {
	$_SESSION['mat_code'] = $_POST["mat_code"];
	$saisie_notes->getEnseignantMatiereCtcs();
                 // $saisie_notes->getEnseignantMatiereSections();
}

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
//sauvegarde de la note
if(!empty($_POST['action']) && $_POST['action'] == 'Save_Note'){ 

	if($_POST['id']) {
		$id = $_POST['id'];
		$fieldname = $_POST['fieldname'];
		$oldValue = $_POST['oldValue'];
		$newValue = $_POST['newValue'];
		
    	//$sql_q//$sql_query = "UPDATE gpw_ctc_nots SET $update_field WHERE id='" . $input['id'] . "'";
    	//mysqli_query(conn, $sql_query) or die("database error:". mysqli_error($conn));
    	$saisie_notes->SaveNote($id, $fieldname, $oldValue, $newValue);
	}	
}


if(!empty($_POST['action']) && $_POST['action'] == 'PrintEnseignantPVM'){ 
	$parcours = $_POST['parcours'];
	$matiere = $_POST['matiere'];
	$examen = $_POST['examen'];
	$section = $_POST['section'];
	$groupe = $_POST['groupe'];
	$saisie_notes->PrintEnseignantPVM($parcours, $matiere, $examen, $section, $groupe);
}

if(!empty($_POST['action']) && $_POST['action'] == 'get_StopSaisie'){ 
   //echo json_encode($_SESSION['stop_saisie']);
    //echo json_encode(array("mode"=>$_SESSION['stop_saisie']));
    $saisie_notes->get_StopSaisie();
	
}

?>