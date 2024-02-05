<?php
//include_once("db_connect.php");
include 'init.php';
//$id = 1;
//$note = "";
$delete = "0";
//$absent = "0";
//$exclu = "0";
//$obs = "";
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
    if(isset($input['note'])) {
        $note = $input['note'];
    } else if(isset($input['absent'])) {
        $absent = $input['absent'];
    } else if(isset($input['exclu'])) {
        $exclu = $input['exclu'];
    } else if(isset($input['deleted'])) {
        $delete = $input['deleted'];
    } else if(isset($input['obs'])) {
        $obs = $input['obs'];
    }
    
    $update_fields='';
    if(isset($input['note'])) {
        $update_fields.= "note=".$input['note']."";
    } else if(isset($input['absent'])) {
        $update_fields.= "absent='".$input['absent']."'";
    } else if(isset($input['exclu'])) {
        $update_fields.= "exclu='".$input['exclu']."'";
    } else if(isset($input['obs'])) {
    $update_fields.= "obs='".$input['obs']."'";
    }
    //else if(isset($input['designation'])) {
    //$update_fields.= "designation='".$input['designation']."'";
    

    if($input['id']) {
    //if($update_field && $input['id']) {
    //$sql_q//$sql_query = "UPDATE gpw_ctc_nots SET $update_field WHERE id='" . $input['id'] . "'";
    //mysqli_query(conn, $sql_query) or die("database error:". mysqli_error($conn));
    $saisie_notes->SaveNote($input['id'], $note, $absent, $exclu, $deleted, $obs, $update_fields);
    }
}
?>