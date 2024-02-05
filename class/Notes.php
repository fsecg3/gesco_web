<?php
//$semestre_code = "1";

class EtudiantNotes extends Database {  
    
	private $notesTable = 'rdn_notes';
	private $departementsTable = 'gpw_etas';
	private $recoursTable = 'gpw_recs';
	private $usersTable = 'gpw_users';

	private $dbConnect = false;
	private $uem_ckey = ''; 
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	public function listNotes(){
                $par_code = $_SESSION["par_code"];     $ann_code = $_SESSION["ann_code"];
                $sem_code = $_SESSION["sem_code"];
                $cycle = $_SESSION["cycle"];
                $matricule = $_SESSION["matricule"];
                
        $hide_mg_uems_epas = $this->getSiteParams('UEM', $par_code, $ann_code, $sem_code, $cycle);
        $hide_mg_sems_epas = $this->getSiteParams('SEM', $par_code, $ann_code, $sem_code, $cycle);
        $hide_mg_anns_epas = $this->getSiteParams('ANN', $par_code, $ann_code, $sem_code, $cycle);
        $hide_dec_sems_epas = '0'; //$hide_mg_sems_epas; // '0';
        $hide_dec_anns_epas = '0'; //$hide_mg_anns_epas; //'0';
        
        $hide_dec_sems_epas = $this->get_SitePars('hide_dec_sems', $par_code, $ann_code, $sem_code, $cycle, '1');
        $hide_dec_anns_epas = $this->get_SitePars('hide_dec_anns', $par_code, $ann_code, $sem_code, $cycle, '1');
        
        $stop_recours_td_exa = $this->get_SitePars('stop_recours', $par_code, $ann_code, $sem_code, $cycle, '1');
        $stop_recours_rat = $this->get_SitePars('stop_recours', $par_code, $ann_code, $sem_code, $cycle, '2');
        
        $hide_cc1 = '0';
        if($_SESSION['DB'] == 'UFC_WEB'){
            $hide_cc1 = $this->get_SitePars('hide_cc1', $par_code, $ann_code, $sem_code, $cycle, '1');
        }
         
		$sqlQuery = "SELECT * 
			FROM ".$this->notesTable;
		$sqlQuery .= " WHERE matricule='".$matricule."'";
		$sqlQuery .= " AND par_code='".$par_code."'";
                                    $sqlQuery .= " AND ann_code='".$ann_code."'";
		$sqlQuery .= " AND sem_code='".$sem_code."'";
                                    $sqlQuery .= " AND cycle='".$cycle."'";
		$sqlQuery .= " AND ann_univ='".$_SESSION['ann_univ']."'";
		//4407214239
		
		//echo '<script>alert("semestre_code")</script>';
		//echo '<script>alert($this->semestre_code)</script>';
		if(!empty($_POST["search"]["value"])){
		//	$sqlQuery .= ' (id LIKE "%'.$_POST["search"]["value"].'%" ';					
		//	$sqlQuery .= ' OR name LIKE "%'.$_POST["search"]["value"].'%" ';
		//	$sqlQuery .= ' OR status LIKE "%'.$_POST["search"]["value"].'%" ';					
		}
		if(!empty($_POST["order"])){
		//	$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY nord ASC ';
		}
		//if($_POST["length"] != -1){
		//	$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		//}	
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		//unset($notesData);
		$notesData = array();
		
		$nord = 0;
        $deposer_recours_cc1 = ($_SESSION['langue'] == 'AR')? "طعن أ.م":"Recours CC";
        $deposer_recours_cc2 = ($_SESSION['langue'] == 'AR')? "طعن أ.م":"Recours TP";
        
        if($_SESSION['DB'] == 'UFC_WEB'){
            $deposer_recours_cc1 = ($_SESSION['langue'] == 'AR')? "طعن ع.‘رضية":"Recours CC";
            $deposer_recours_cc2 = ($_SESSION['langue'] == 'AR')? "طعن ع,تجمعات":"Recours TP";
        }
        $deposer_recours_exa = ($_SESSION['langue'] == 'AR')? "طعن إمتحان":"Recours Examen";
        $deposer_recours_rat = ($_SESSION['langue'] == 'AR')?" طعن إستدراك":"Recours Rattrapage";
        $modifier_recours = ($_SESSION['langue'] == 'AR')? "تعديل":"Modifier";
        $supprimer_recours = ($_SESSION['langue'] == 'AR')? "حذف":"Supprimer";
                                    
                                    if($numRows > 0){
		while( $notes = mysqli_fetch_assoc($result) ) {
			$notesRows = array();
                                                      $uem_desl = ($_SESSION['langue'] == 'AR')? $notes['uem_desl_ar']:$notes['uem_desl'];
			$status = '';
			if($notes['decision'] == "1")	{
				$status = '<span class="label label-success">Enabled</span>';
			} else if($notes['decision'] == "0") {
				$status = '<span class="label label-danger">Disabled</span>';
			}	
			$nord += 1;
			$uem_moyenne = max($notes['uem_moyenne_ses1'], $notes['uem_moyenne_ses2']);
            
            if(strpos($uem_moyenne, "غائب") > 0){
                $uem_moyenne = "";
            }
			$notesRows[] = $notes['uem_type'];
			$notesRows[] = $nord; //$notes['nord']; //
			$notesRows[] = $uem_desl;
			$notesRows[] = $notes['uem_coef'];
			$notesRows[] = $notes['uem_credits_ref'];
			$notesRows[] = $notes['uem_acquis_ant'];
			$notesRows[] = ($hide_cc1 == '0')? $notes['uem_cc1']:  '';
			$notesRows[] = $notes['uem_cc2'];
			$notesRows[] = $notes['uem_examen'];
			$notesRows[] = $notes['uem_rattrapage'];
			$notesRows[] = ($_SESSION['hide_mg_uems'] == '0' && $hide_mg_uems_epas == 0)? $uem_moyenne: '';
			$notesRows[] = ($_SESSION['hide_mg_uems'] == '0' && $hide_mg_uems_epas == 0)? $notes['uem_credits']: '';
			$notesRows[] = $notes['decision_ar'];
                        $displayRecours = false;
                        $disabled = "";
                        
			//if ($uem_moyenne < 20 && $notes['uem_type'] == "MAT"){
			if ($notes['uem_type'] == "MAT"){				
                            $recours_cc1_Id = $this->getRecoursIdForMatiere($notes['uem_ckey'], 'CC1');
                            $recours_cc2_Id = $this->getRecoursIdForMatiere($notes['uem_ckey'], 'CC2');
                            $recours_exa_Id = $this->getRecoursIdForMatiere($notes['uem_ckey'], 'EXA');
                            $recours_rat_Id = $this->getRecoursIdForMatiere($notes['uem_ckey'], 'RAT');
                            
                            $recours_cc1_Created = $this->isRecoursMatiereCreated($notes['uem_ckey'], 'CC1');
                            $recours_cc2_Created = $this->isRecoursMatiereCreated($notes['uem_ckey'], 'CC2');
                            $recours_exa_Created = $this->isRecoursMatiereCreated($notes['uem_ckey'], 'EXA');
                            $recours_rat_Created = $this->isRecoursMatiereCreated($notes['uem_ckey'], 'RAT');
                            
                          //  $notesRows[] = '<button type="button" name="add" value="Intitulé matière" id="'.$notes["uem_ckey"].'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.'DR'.'</button>';
                          //  $notesRows[] = '<button type="button" name="update" id="'.$recoursId.'" value="'.$notes["uem_ckey"].'" class="btn btn-warning btn-xs update" '.$disableupdate.'>'.'MR'.'</button>';
                          //  $notesRows[] = '<button type="button" name="add" value="Intitulé matière" id="'.$notes["uem_ckey"].'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.'DR1'.'</button>';

                            if ($recours_cc1_Id + $recours_cc2_Id + $recours_exa_Id + $recours_rat_Id> 0){
                        //        $disabled = 'disabled';
                                $disableupdate = ""; //'disabled';
                               
                                  
                               
                                $disablereplied = ""; //désactiver la modification et/ou la suppression d'un recours ayant reçu une réponse
                                $recours_cc1_Replied = $this->isRecoursReplied($recours_cc1_Id);
                                $recours_cc2_Replied = $this->isRecoursReplied($recours_cc2_Id);
                                $recours_exa_Replied = $this->isRecoursReplied($recours_exa_Id);
                                $recours_rat_Replied = $this->isRecoursReplied($recours_rat_Id);
                                if ($recours_cc1_Replied){
                                    //$disablereplied = 'disabled';
                                }


                                    if($stop_recours_td_exa == '0' || $stop_recours_rat == '0'){
                                        if($stop_recours_td_exa == '0'){
                                            //$disabled = ($recours_cc1_Created == 1)? 'disabled': '';
                                            $notesRows[] = ($recours_cc1_Created == 1)? '': '<button type="button" name="update" rec_id='.$recours_cc1_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'CC1'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_cc1.'</button>';
                                            
                                            if($_SESSION['DB'] == 'ENST_NEW_WEB' || $_SESSION['DB'] == 'UFC_WEB'){
                                                //$disabled = ($recours_cc2_Created == 1)? 'disabled': '';
                                                $notesRows[] = ($recours_cc2_Created == 1)? '': '<button type="button" name="update" rec_id='.$recours_cc2_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'CC2'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_cc2.'</button>';
                                             }else{
                                                 $notesRows[] =  "";
                                             }
                                             //$disabled = ($recours_exa_Created == 1)? 'disabled': '';
                                            $notesRows[] = ($recours_exa_Created == 1)? '': '<button type="button" name="add" rec_id='.$recours_exa_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'EXA'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_exa.'</button>';
                                        } elseif($stop_recours_rat == '0'){
                                            //$disabled = ($recours_rat_Created == 1)? 'disabled': '';
                                            $notesRows[] = ($recours_rat_Created == 1)? '': '<button type="button" name="add" rec_id='.$recours_rat_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'RAT'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_rat.'</button>';
                                            $notesRows[] =  "";
                                            $notesRows[] =  "";
                                        }

//                                        $notesRows[] = '<button type="button" name="add" value="Intitulé matière" rec_id="'.$recours_ctc_Id.'" ctc_code="'.'CTC'.'" id="'.$notes["uem_ckey"].'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_ctc.'</button>';
//                                        $notesRows[] = '<button type="button" name="add" value="Intitulé matière" rec_id="'.$recours_exa_Id.'" ctc_code="'.'EXA'.'" id="'.$notes["uem_ckey"].'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_exa.'</button>';
                                        //$notesRows[] = '<button type="button" name="update" id="'.$recoursId.'" value="'.$notes["uem_ckey"].'" class="btn btn-warning btn-xs update" '.$disableupdate.'>'.$modifier_recours.'</button>';
                                    }else{
                                        $notesRows[] =  "";
                                        $notesRows[] = "";
                                       $notesRows[] =  "";
                                //        $notesRows[] =  "";
                                    }
		//$notesRows[] = '<button type="button" name="delete" id="'.$recours_ctc_Id.'" class="btn btn-danger btn-xs delete" '.$disablereplied.'>'.$supprimer_recours.'</button>';
                            } else{
                                    if($stop_recours_td_exa == '0' || $stop_recours_rat == '0'){
                                        if($stop_recours_td_exa == '0'){
                                            $notesRows[] = '<button type="button" name="update" rec_id='.$recours_cc1_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'CC1'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_cc1.'</button>';
                                            if($_SESSION['DB'] == 'ENST_NEW_WEB' || $_SESSION['DB'] == 'UFC_WEB'){
                                                $notesRows[] = '<button type="button" name="update" rec_id='.$recours_cc2_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'CC2'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_cc2.'</button>';
                                             }else{
                                                 $notesRows[] =  "";
                                             }
                                            $notesRows[] = '<button type="button" name="add" rec_id='.$recours_exa_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'EXA'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_exa.'</button>';
                                        } elseif($stop_recours_rat == '0'){
                                            $notesRows[] = '<button type="button" name="add" rec_id='.$recours_rat_Id.' uem_id="'.$notes["uem_ckey"].'" ctc_id="'.'RAT'.'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_rat.'</button>';
                                            $notesRows[] =  "";
                                            $notesRows[] =  "";
                                        }

//                                        $notesRows[] = '<button type="button" name="add" value="Intitulé matière" rec_id="'.$recours_ctc_Id.'" ctc_code="'.'CTC'.'" id="'.$notes["uem_ckey"].'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_ctc.'</button>';
//                                        $notesRows[] = '<button type="button" name="add" value="Intitulé matière" rec_id="'.$recours_exa_Id.'" ctc_code="'.'EXA'.'" id="'.$notes["uem_ckey"].'" class="btn btn-success btn-xs addRecours" '.$disabled.'>'.$deposer_recours_exa.'</button>';
                                        //$notesRows[] = '<button type="button" name="update" id="'.$recoursId.'" value="'.$notes["uem_ckey"].'" class="btn btn-warning btn-xs update" '.$disableupdate.'>'.$modifier_recours.'</button>';
                                }else{
                                        $notesRows[] = "";
                                        $notesRows[] = "";
                                       $notesRows[] = "";
//                                        $notesRows[] = "";
                                }
                            }
                        }else{
				$notesRows[] = "";
				$notesRows[] = "";
                                                                        $notesRows[] = "";
				//$notesRows[] = "";
				//$notesRows[] = "";
			}

			//$notesRows[] = '<button type="button" name="update" id="'.$notes["nord"].'" class="btn btn-warning btn-xs update">تعديل</button>';
			//$notesRows[] = '<button type="button" name="delete" id="'.$notes["nord"].'" class="btn btn-danger btn-xs delete">حذف</button>';
			$notesData[] = $notesRows;
		}
		//Résultats semestriels
                $sqlQuery = "SELECT * FROM rdn_etus ";
                $sqlQuery .= "WHERE matricule = '".$_SESSION['matricule']."'";
                $sqlQuery .= "AND ann_code = '".$ann_code."'";
                $sqlQuery .= " AND cycle='".$cycle."'";
                $sqlQuery .= "AND ann_univ = '".$_SESSION['ann_univ']."'";
                $result = mysqli_query($this->dbConnect, $sqlQuery);
		
		if( $mgsa = mysqli_fetch_assoc($result) ) {
                    $notesRows = array();
                    $nord += 1;
                    $mg_sem_code = ($sem_code % 2 ? '1' : '2');
                    $notesRows[] = "SEM";
                    $notesRows[] = $nord;
                    if($_SESSION['langue'] == 'AR'){
                       $notesRows[] = "معدل السداسي " . $mgsa['desl_s'.$mg_sem_code];
                    }else{
                        $notesRows[] = "MG semestre " . $mgsa['desl_s'.$mg_sem_code];
                    }
                    $notesRows[] = ""; //$notes['uem_coef'];
                    $notesRows[] = ""; //$notes['uem_credits_ref'];
                    $notesRows[] = ""; //$notes['uem_acquis_ant'];
                    $notesRows[] = ""; //$notes['uem_cc1'];
                    $notesRows[] = ""; //$notes['uem_cc2'];
                    $notesRows[] = ""; //$notes['uem_examen'];
                    $notesRows[] = ""; //$notes['uem_rattrapage'];
                    $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_sems_epas == '0')? $mgsa['mg_s'.$mg_sem_code]: '';
                    $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_sems_epas == '0')? $mgsa['cr_s'.$mg_sem_code]: ''; //$notes['uem_credits'];
                    if($_SESSION['langue'] == 'AR'){
                        $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_sems_epas == '0' && $hide_dec_sems_epas == '0')? $mgsa['dec_s'.$mg_sem_code.'_ar']: '';
                    }else{
                        $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_sems_epas == '0' && $hide_dec_sems_epas == '0')? $mgsa['dec_s'.$mg_sem_code]: '';
                    }
                    
                    $notesRows[] = ""; //'<button type="button" name="update" id="'.$notes["nord"].'" class="btn btn-warning btn-xs update">تعديل</button>';
                    $notesRows[] = ""; //'<button type="button" name="delete" id="'.$notes["nord"].'" class="btn btn-danger btn-xs delete">حذف</button>';
                    $notesRows[] = "";
                    $notesData[] = $notesRows;
                
                    //Résultats annuels
                    $notesRows = array();
                    $nord += 1;
					$notesRows[] = "ANN";
                    $notesRows[] = $nord;
                    if($_SESSION['langue'] == 'AR'){
                        $notesRows[] = " معدل السنة" . " ".$mgsa['ann_desl_ar'];
                    }else{
                        $notesRows[] = " MG annuelle" . " ".$mgsa['ann_desl'];
                    }
                    $notesRows[] = ""; //$notes['uem_coef'];
                    $notesRows[] = ""; //$notes['uem_credits_ref'];
                    $notesRows[] = ""; //$notes['uem_acquis_ant'];
                    $notesRows[] = ""; //$notes['uem_cc1'];
                    $notesRows[] = ""; //$notes['uem_cc2'];
                    $notesRows[] = ""; //$notes['uem_examen'];
                    $notesRows[] = ""; //$notes['uem_rattrapage'];
                    $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_anns_epas == '0')? $mgsa['mg_ann']: '';
                    $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_anns_epas == '0')? $mgsa['cr_ann']: ''; //$notes['uem_credits']
                    if($_SESSION['langue'] == 'AR'){
                        $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_anns_epas == '0' && $hide_dec_anns_epas == '0')? $mgsa['dec_ann_ar']: '';
                    }else{
                        $notesRows[] = ($_SESSION['hide_mg_sems'] == '0' && $hide_mg_anns_epas == '0' && $hide_dec_anns_epas == '0')? $mgsa['dec_ann']: '';
                    }
                    $notesRows[] = ""; //' Recours TD/EXA<button type="button" name="update" id="'.$notes["nord"].'" class="btn btn-warning btn-xs update">تعديل</button>';
                    $notesRows[] = ""; //'<button type="button" name="update" id="'.$notes["nord"].'" class="btn btn-warning btn-xs update">تعديل</button>';
                    $notesRows[] = ""; //'<button type="button" name="delete" id="'.$notes["nord"].'" class="btn btn-danger btn-xs delete">حذف</button>';
                    //$notesRows[] = "";
                    $notesData[] = $notesRows;
                }
         }
                
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows+1,
			"recordsFiltered" 	=> 	$numRows+1,
			"data"    			=> 	$notesData
		);
		echo json_encode($output);
	}	
	
		
	public function getNotesDetails(){		
		if($this->matricule) {
		//if (1==1){	

			$sqlQuery = "
				SELECT *
				FROM ".$this->notesTable." 
				WHERE id = '".$this->matricule."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);
			//echo json_last_error_msg(); // Print out the error if any
			//die(); // halt the script
		}		
	}

	public function getRecoursDetails(){
		if($_POST['rec_id']){
                                            $rec_id = $_POST['rec_id'];
			$_SESSION["uem_ckey"] = $_POST['uem_ckey'];	
			$sqlQuery = 'SELECT * FROM gpw_recs WHERE id = '.$rec_id;
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                                    //decode tags value
                                    //                $row['tags'] = json_decode($row['tags']);

                                                //encode and use
                                                //$record = json_encode($record);
                                                $myVar = json_encode($row);
			echo $myVar; //json_encode($row);
		}else{
                    
                                    $row = array();
                                                $myVar = json_encode($row);
			echo $myVar; //json_encode($row);
	
                }
	}
 
        	public function get_RecoursDetails($rec_id, $uem_ckey, $ctc_code){
                                   if(!isset($rec_id)){
                                       return;
                                   } 
                                   $sqlQuery = "SELECT * FROM  gpw_recs 	WHERE id = ".$rec_id;
                                   $result = mysqli_query($this->dbConnect, $sqlQuery);	
                                   $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                   if(!$row){
                                        $row['id'] = 0;
                                        $row['matricule'] = $_SESSION['matricule'];
                                        $row['ann_univ'] = $_SESSION['ann_univ'];
                                        $row['rec_type'] = '1';
                                        $row['rec_message'] = '';
                                        $row['eta_code'] = '000';
                                        $row['matricule_ens'] = '000';
                                        $row['uem_ckey'] = $uem_ckey;
                                        $row['ctc_code'] = $ctc_code;
                                        $row['last_reply'] = 0;
                                        $row['admin_read'] = 0;
                                        $row['ens_read'] = 0;
                                        $row['date_notify_ens'] = 0;
                                        $row['resolved'] = 0;
                                        //$row['datec'] = 0;
                                }
                                return $row;
	}

                public function getRedirectRecoursDetails(){
		if($_POST['recoursId']) {
			//$_SESSION["uem_ckey"] = $_POST['matiereId'];	
			$sqlQuery = "
				SELECT * FROM ".$this->recoursTable." 
				WHERE id = '".$_POST["recoursId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);
		}
	}
        
        
	public function getEtudiantInfos(){		
            if ($_SESSION['matricule']){
                $sqlQuery = "SELECT nom, prenom, nom_ar, prenom_ar, sexe, eta_desl, eta_desl_ar, par_desl, par_desl_ar, ann_desl, ann_desl_ar, sec_code, grp_code FROM rdn_etus ";
                $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
                $sqlQuery .= " ORDER BY ann_code DESC";
                
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if($etudiant = mysqli_fetch_assoc($result) ) {
                    
                    $cycle = ($_SESSION['cycle'] == '2' ? 'ماستر' : 'ليسانس');
                   if($_SESSION['langue'] == 'FR'){$cycle = ($_SESSION['cycle'] == '2' ? 'Master' : 'Licence');}
                    $info1 = "الطالب";
                    if ($etudiant['sexe'] == 2){
                        $info1 .= "ة";
                    }
                    
                    if($_SESSION['langue'] == 'AR'){
                        $info1 = ($etudiant['sexe'] == 2) ? "الطاالبة":"الطالب";
                        
                        $cycle = ($_SESSION['cycle'] == '2' ? 'ماستر' : 'ليسانس');

                        $info1 .= ": ".$etudiant['nom_ar']." ".$etudiant['prenom_ar']." - ";
                        $info1 .= " رقم التسجيل: ".$_SESSION['matricule'];
                    
                        $info2 = " السنة"." ".$etudiant['ann_desl_ar']." ".$cycle." ".$etudiant['par_desl_ar'];
                        $info2 .= " المجموعة"." ".$etudiant['sec_code']." "." الفوج "." ".$etudiant['grp_code'];
                        $info2 .= " - قسم  ".$etudiant['par_desl_ar'];
                    }else{
                        $info1 = ($etudiant['sexe'] == 2) ? "L'étudiante":"L'étudiant";
                        
                        $info1 .= ": ".$etudiant['nom']." ".$etudiant['prenom']." - ";
                        $info1 .= " Matricule: ".$_SESSION['matricule'];
                    
                        $info2 = $etudiant['ann_desl']." année"." ".$cycle." ".$etudiant['par_desl'];
                        $info2 .= " Section"." ".$etudiant['sec_code']." "." Groupe"." ".$etudiant['grp_code'];
                        $info2 .= " - Département".$etudiant['par_desl'];
                        
                    }
                    
                    echo '<font size="+1">'.$info1.'</font><br>';
                    /////////echo '<font size="+1">'.$info2.'</font>';
                    //echo '<B>'.$info1.'.</B><br>';
                    //echo '<B>'.$info2.'.</B>';
                }
            }
	}

    public function getListeInscriptionsEtudiant(){		
        if ($_SESSION['matricule']){
            $sqlQuery = "SELECT eta_code, eta_desl, eta_desl_ar, par_code, par_desl, par_desl_ar, ann_code, ann_desl, ann_desl_ar, sec_code, grp_code, cycle FROM rdn_etus ";
            $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
            $sqlQuery .= " ORDER BY ann_univ DESC, ann_code DESC";
            $li_row = 0;
            if($_SESSION['langue'] == 'AR'){
                echo '<option value="0">'.'--- اختر المسلك أو التخصص ---' . '</option>';
            }else{
                echo '<option value="0">'.'--- Sélectionnez un parcours/spécialité ---' . '</option>';
            }
            $_SESSION['par_code'] = "0";
            $_SESSION['ann_code'] = "0";
            $_SESSION['sem_code'] = "0";
            $_SESSION['cycle'] = "0";
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            while( $etudiant = mysqli_fetch_assoc($result) ) {
                $li_row++;
                if($li_row == 1){
                    $_SESSION['par_code'] = $etudiant['par_code'];
                    $_SESSION['ann_code'] = $etudiant['ann_code'];
                    $_SESSION['cycle'] = $etudiant['cycle'];
                }                 
                if($_SESSION['langue'] == 'AR'){                    
                    $cycle = ($etudiant['cycle'] == '2' ? 'ماستر' : 'ليسانس');
                    $info2 = " السنة"." ".$etudiant['ann_desl_ar']." ".$cycle." ".$etudiant['par_desl_ar'];
                    $info2 .= "  - المجموعة"." ".$etudiant['sec_code']." "." الفوج "." ".$etudiant['grp_code'];
                }else{                
                    $cycle = ($etudiant['cycle'] == '2' ? 'Master' : 'Licence');
                    if($_SESSION['DB'] == 'ENST_NEW_WEB'){
                        $cycle = ($etudiant['cycle'] == '2' ? 'Master CP' : 'Ingéniorat');
                    }    
                    $info2 = $cycle." - ".$etudiant['ann_desl']." année ".$etudiant['par_desl'];
                     
                    $info2 .= "  - Section ".$etudiant['sec_code']." "." Groupe"." ".$etudiant['grp_code'];

                }
                //$info2 .= "  - قسم  ".$etudiant['eta_desl_ar'];
                
                echo '<option value="' . $etudiant['par_code'].'/'.$etudiant['ann_code'] .'/'.$etudiant['cycle']. '">' . $info2  . '</option>';
                //echo '<B>'.$info1.'.</B><br>';
                //echo '<B>'.$info2.'.</B>';
            }
        }
}

    public function getSiteParams($obj_type, $par_code, $ann_code, $sem_code, $cycle){		
        if ($_SESSION['matricule']){
            
            $sqlQuery = "SELECT import_notes, hide_mg_uems, hide_mg_sems, hide_mg_anns, stop_recours, stop_saisie FROM gpw_site_pars ";
            $sqlQuery .= " WHERE par_code = '".$par_code."' AND ann_code = '".$ann_code."' AND sem_code = '".$sem_code."' AND cycle = '".$cycle."' AND ann_univ ='".$_SESSION['ann_univ']."'";
        
            $rc = "0";
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            if($row = mysqli_fetch_assoc($result) ) {
                if($obj_type == 'UEM'){ $rc = $row['hide_mg_uems'];}
                if($obj_type == 'SEM'){ $rc = $row['hide_mg_sems'];}
                if($obj_type == 'ANN'){ $rc = $row['hide_mg_anns'];}
            }                 
            return $rc;    
        }
    }
    
    public function get_SitePars($fld_name, $par_code, $ann_code, $sem_code, $cycle, $ses_code) {       
                $rc = '0';
                if($fld_name == 'stop_recours'){
                    $sqlQuery = "SELECT ".$fld_name." FROM gpw_site";
                    $result = mysqli_query($this->dbConnect, $sqlQuery);
                    $row = mysqli_fetch_assoc($result);
                    if ($row){
                        $rc = $row[$fld_name] ;
                        if($rc == '1'){
                            return $rc;
                        }
                    }
                }
                $sqlQuery = "SELECT ".$fld_name." FROM gpw_site_pars";
                $sqlQuery .= " WHERE par_code = '".$par_code."' AND ann_code = '".$ann_code."' AND sem_code = '".$sem_code."' AND cycle = '".$cycle."' AND ses_code = '".$ses_code."'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $row = mysqli_fetch_assoc($result);
                if ($row){
                    $rc = $row[$fld_name] ;
                }
                return $rc;
        }
        
	public function getPageTitle($ps_langue) {
                                    $title = "";
                                   if($_SESSION['langue'] == 'AR'){
                                   $title = "البوابة الرقمية ل";
                                   
                                   }
		$title .= $_SESSION['fac_desl_ar'];
		if($ps_langue == "AR") {  
            echo '<h3>'.$title.'</h3>';
        }     
    }

	public function getUniversityName($ps_langue) {
		if($ps_langue == "AR") {  
			echo '<style>';
			echo 'h3 {text-align: center;}';
			echo '</style>';
			
            echo '<h3>'.$_SESSION['uni_desl_ar'].'</h3>';
        }     
    }        

	public function getFacultyName($ps_langue) {
		if($ps_langue == "AR") {  
            echo '<h2>'.$_SESSION['fac_desl_ar'].'</h2>';
        }     
    }        

	public function getSemestres() {
    

			$sem_desl = ($_SESSION['langue'] == 'AR')? " السداسي ": "Semestre ";
			$sqlQuery = "SELECT * FROM rdn_etus ";
			$sqlQuery.= "WHERE matricule = '".$_SESSION['matricule']."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);
			if($semestre = mysqli_fetch_assoc($result) ) {  
				$_SESSION["ann_code"] = $semestre['ann_code'];
				$_SESSION["sem_code"] = $semestre['code_s1'];
			
				echo '<button type="button" name="add" id='.$semestre['code_s1'].' class="btn btn-success btn-xs getNotesSemestre">'.$sem_desl.$semestre['desl_s1'].'</button>';
				//echo '<div class="space"></div>';
				echo '<button type="button" name="add" id='.$semestre['code_s2'].' class="btn btn-success btn-xs getNotesSemestre">'.$sem_desl.$semestre['desl_s2'].'</button>';
			}     
	}        
	
	public function getTypesRecours($ctc_code) {      
        $type_rec1 = ($ctc_code == 'CTC')? 'علامة الأعمال الموجهة غائبة': '';
		$sqlQuery = "SELECT * FROM gpw_ldcs";
                $sqlQuery .= " WHERE ldc_type = 'REC_TYPE'";
                $sqlQuery .= " ORDER BY ldc_code ASC";
                
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		while($typerecours = mysqli_fetch_assoc($result) ) {       
            echo '<option value="' . $typerecours['id'] . '">' . $typerecours['ldc_desl_ar']  . '</option>';
        }
    }
            
	public function getDepartements() {       
		$sqlQuery = "SELECT DISTINCT gpw_etas.eta_code, eta_desl, eta_desl_ar FROM  gpw_ctc_nots";
                                    $sqlQuery .= " INNER JOIN gpw_etas ON gpw_etas.eta_code = gpw_ctc_nots.eta_code";
                                    $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
                                    $numRows = mysqli_num_rows($result);
                                    if($numRows == 0){
                                        $sqlQuery = "SELECT eta_code, eta_desl, eta_desl_ar FROM  gpw_etas";
                                        if($_SESSION['DB'] == 'UFC_WEB'){
                                            $sqlQuery .= " WHERE Locate(eta_code, '".$_SESSION['matricule']."')>0";  
                                        }
                                        $sqlQuery .= " ORDER BY eta_code";
                                        $result = mysqli_query($this->dbConnect, $sqlQuery);
                                    }
                                   /* 
		if ($ps_all == 'ALL'){
			echo '<option value="0" selected>' . 'جميع الأقسام'  . '</option>';			
		}*/
                                    $dep_desl = ($_SESSION['langue'] == 'AR')? "......     اختر القسم    ...... ":"Sélectionnez un département";
                                    echo '<option value="0"  selected>' . $dep_desl  . '</option>';			
		while($departement = mysqli_fetch_assoc($result) ) {       
                                            $dep_desl = ($_SESSION['langue'] == 'AR')? $departement['eta_desl_ar']:$departement['eta_desl'];
                     echo '<option value=' . $departement['eta_code'] . '>' . $dep_desl  . '</option>';
        }
    }

	public function getEnseignants($mat_code) {    
                                    //$mat_code  = $_SESSION['mat_code'];
                                    $ens_count = 0;
                                    $matricule_ens = "";
                                    echo '<option value="000">' . '...  غير موجود في القائمة   .....'. '</option>';
                                    
                                    $sqlQuery = "SELECT matricule_ens FROM gpw_ctc_nots";
                                    $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
                                    $sqlQuery .= " AND mat_code = '".$mat_code."'";
                                    $sqlQuery .= " LIMIT 1";
                                    
                                    $result = mysqli_query($this->dbConnect, $sqlQuery);
                                    $numRows = mysqli_num_rows($result);
                                    if($numRows > 0){
                                        $row = mysqli_fetch_assoc($result);
                                        $matricule_ens = $row['matricule_ens'];
                                        $matricule_ens = ($matricule_ens == null)? '': $matricule_ens; 
                                    }
                                    if($matricule_ens != ''){
                                        $sqlQuery = "SELECT matricule, concat(nom_ar, ' ', prenom_ar) as nom FROM ".$this->usersTable;
                                        $sqlQuery .= " WHERE matricule = '".$matricule_ens."'";
                                        //$sqlQuery .= " WHERE user_type = '3'";
                                        $sqlQuery .= " ORDER BY nom_ar, prenom_ar";
		
                                        $result = mysqli_query($this->dbConnect, $sqlQuery);
                                        $numRows = mysqli_num_rows($result);
                                        if($numRows > 0){
                                            $enseignant = mysqli_fetch_assoc($result);       
                                                $ens_count++;
                                                $nom = $enseignant['nom'];
                                                //echo '<option value="' . $matricule_ens . '">'.$nom.'</option>';
                                                echo '<option value="000">' . '...  Nouveau.....'.$ens_count. '</option>';
                                                echo '<option value="' . $matricule_ens.'">' . $nom  . '</option>';
                                                //echo '<option value="' . $enseignant['matricule'] . '"selected>' . $enseignant['nom']  . '</option>';
                                            
                                        }
                                   }
        if($ens_count >= 0){
          echo '<option value="000">' . '...  Nouveau.....'. '</option>';
        }
    }
    
	public function getMatieres() {       
		$sqlQuery = "SELECT uem_ckey, uem_desl, uem_desl_ar FROM rdn_notes";
                $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                $sqlQuery .= " AND uem_type = '"."MAT"."'";
                $sqlQuery .= " AND sem_code = '".$_SESSION['sem_code']."'";
		$sqlQuery .= " AND (uem_ckey = '".$_SESSION['uem_ckey']."')";
		//$sqlQuery .= " OR uem_ckey = '".$_POST['matiereId']."')";
		
                //$sqlQuery .= " AND greatest(uem_moyenne_ses1, uem_moyenne_ses2) < 10";
                //$sqlQuery .= " AND uem_ckey not in (SELECT uem_ckey FROM gpw_recs WHERE user_id = ".$_SESSION['userid'].")";
                
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		while($uems = mysqli_fetch_assoc($result) ) {       
                  echo '<input type="text" id="matiere" name="'.$uems['uem_desl_ar'].'" value="'.$uems['uem_desl_ar'].'" readonly>';
                  //echo '<option value="' . $uems['uem_ckey'] . '">' . $uems['uem_desl_ar']  . '</option>';
        }
    }

            
    public function createRecours() {      
        if(!empty($_POST['matiereId']) && $_SESSION['OK'] == 'OK') {
        $_SESSION['OK'] = '';
        $recoursId = $_POST['recoursId'];
        $matiere = $_POST['matiereId'];
        $ctc_code = $_POST['ctcId'];
        $rec_type = $_POST['type_recours'];
        $departement = $_POST['departement'];
        if($departement == '0'){
           $departement = $_SESSION['eta_code'];
        }

        $enseignant = $_POST['enseignant'];
         $message = strip_tags($_POST['message']);              
        $ann_univ = $_SESSION['ann_univ'];

        if($recoursId > 0){
            $stmt = $this->dbConnect->prepare("UPDATE gpw_recs SET rec_type = ?, rec_message = ?, eta_code = ?, matricule_ens = ?, datem=now()  WHERE id = ".$recoursId);
            $stmt->bind_param("ssss", $rec_type, $message, $departement, $enseignant); ///, $recoursId);
            $stmt->execute();
            $stmt->close();
            return;
        }
        $sqlQuery = "SELECT id FROM gpw_recs";
        $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
        $sqlQuery .= " AND uem_ckey = '".$matiere."'";
        $sqlQuery .= " AND ctc_code = '".$ctc_code."'";
                
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
         if($numRows == 0 && $matiere <> '' && $ctc_code <> '' && $_SESSION['matricule'] <> '' && $_SESSION['ann_univ'] <> ''){
                  
		//if(!empty($_POST['message']) && !empty($_POST['matiereId'])) {                			
		//$uem_ckey = "123456";
                $date = new DateTime();
		//$date = $date->getTimestamp();
		//$uniqid = uniqid();   
                $rec_type = $_POST['type_recours'];
                if ($rec_type == null){$rec_type = 0;}
		$matiere = $_POST['matiereId'];
		$departement = $_POST['departement'];
		$enseignant = $_POST['enseignant'];
                //$enseignant  = "";
                if ($enseignant == null){$enseignant = "";}
                
		$status = "0"; //$_POST['status'];
                if($departement == '0'){ $departement = $_SESSION['eta_code'];
        }

        $message = strip_tags($_POST['message']);              
$ann_univ = $_SESSION['ann_univ'];

                //$test = " VALUES('".$_SESSION["userid"]."', '".$message."', '".$departement."', '".$enseignant."', '".$matiere."', 0, 0, 0, 0)";
                
		$queryInsert = "INSERT INTO ".$this->recoursTable." (matricule, ann_univ, rec_type, rec_message, eta_code, matricule_ens, uem_ckey, ctc_code, last_reply, ens_read, admin_read, resolved, datec)";
                //$queryInsert .= " VALUES('".$_SESSION["matricule"]."'.","'.$_SESSION['ann_univ']."",".$rec_type.",'".$message."', '".$departement."', '".$enseignant."', '".$matiere."', 0, 0, 0, 0, now())";
                $queryInsert .= " VALUES('".$_SESSION["matricule"]."','$ann_univ','$rec_type','$message','$departement', '$enseignant','$matiere', '$ctc_code', 0, 0, 0, 0, now())";
                
                //$queryInsert .= " VALUES(1, 0, 6 , 0, 0, 0, 0, 0, 0)";
                //console.log($qryInsert);
                 //$queryInsert .= " VALUES('".$_SESSION["userid"]."', '".$message."', '".$_POST['department']."', '".$_POST['enseignant']."', '".$uem_ckey."', 0, 0, 0, '".$_POST['status']."', '".$date."')";
                mysqli_query($this->dbConnect, $queryInsert); // == false){
                //echo 'error.. The error is '. mysqli_error($this->dbConnect);
                }
            //}
            //    }
		        
		//echo 'success ' . $uniqid;
		} else {
                    echo '<div class="alert error">Veuillez compléter les champs manquants.</div>';
		}
	}	

	public function getRecoursIdForMatiere($puem_ckey, $pctc_code) {
		$recoursId = 0;
                                    
		$sqlQuery = "SELECT id FROM gpw_recs";
                        $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
                        $sqlQuery .= " AND uem_ckey = '".$puem_ckey."'";
                        $sqlQuery .= " AND ctc_code = '".$pctc_code."'";
                        
		$result = mysqli_query($this->dbConnect, $sqlQuery);
                                    $numRows = mysqli_num_rows($result);
                                    if($numRows > 0){
                                        $row = mysqli_fetch_assoc($result);

                                        if ($row){
			$recoursId = $row["id"];
                                        }
                                    }
                                return $recoursId;
                }

	public function isRecoursMatiereCreated($puem_ckey, $pctc_code) {       
		$sqlQuery = "SELECT id FROM gpw_recs";
                $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND uem_ckey = '".$puem_ckey."'";
                $sqlQuery .= " AND ctc_code = '".$pctc_code."'";
                
	$result = mysqli_query($this->dbConnect, $sqlQuery);
                $numRows = mysqli_num_rows($result);
                if ($numRows > 0){
                    return true;
                } else {
                    return false;
                }
    }

	public function isRecoursReplied($recoursId) {       
		$sqlQuery = "SELECT id FROM gpw_recs_reps";
                $sqlQuery .= " WHERE rec_id = ".$recoursId;
                
		$result = mysqli_query($this->dbConnect, $sqlQuery);
                $numRows = mysqli_num_rows($result);
                if ($numRows > 0){
                    return true;
                } else {
                    return false;
                }
    }
    
	public function updateRecours($recoursId) {
            $rec_type = $_POST["type_recours"];
            if ($rec_type == null){$rec_type = 0;}
            
            $eta_code = $_POST["departement"];
            if ($eta_code == null){$eta_code = "";}

            $ens_id = $_POST["enseignant"];
            if ($ens_id == null){$ens_id = 0;}
            
            //$test = "SET uem_ckey = '".$_POST["matiereId"]."', eta_code = '".$eta_code."', matricule_ens = '".$ens_id."', rec_message = '".$_POST["message"]."', resolved = '".$_POST["status"]."', recoursId='".$recoursId."'";
            if($_POST['recoursId'] && $_POST["matiereId"]) {	
		$updateQuery = "UPDATE ".$this->recoursTable."
		SET rec_type = ".$rec_type.", eta_code = ".$eta_code.", matricule_ens = ".$ens_id.", rec_message = '".$_POST["message"]."', resolved = '".$_POST["status"]."'
                WHERE id ='".$_POST["recoursId"]."'";
		$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}	

        	public function updateRedirectRecours($recoursId) {
            //$rec_type = $_POST["type_recours"];
            //if ($rec_type == null){$rec_type = 0;}
            
            $eta_code = $_POST["departement"];
            if ($eta_code == null){$eta_code = "";}

            $ens_id = $_POST["enseignant"];
            if ($ens_id == null){$ens_id = 0;}
            
            //$test = "SET uem_ckey = '".$_POST["matiereId"]."', eta_code = '".$eta_code."', matricule_ens = '".$ens_id."', rec_message = '".$_POST["message"]."', resolved = '".$_POST["status"]."', recoursId='".$recoursId."'";
            
            if($_POST['recoursid'] ) {	
		$updateQuery = "UPDATE ".$this->recoursTable."
		SET eta_code = '".$eta_code."', matricule_ens = '".$ens_id."', resolved = '".$_POST["status"]."'
                WHERE id =".$_POST["recoursid"];
		$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}	

        
	public function deleteRecours() {
		if($_POST['recoursId']) {	
                    $deleteQuery = "DELETE FROM ".$this->recoursTable." 
                    WHERE id ='".$_POST["recoursId"]."'";
                    $isdeleted = mysqli_query($this->dbConnect, $deleteQuery);		
		}	
	}		

     public function get_EtudiantSemestres(){
                $par_code = $_SESSION['par_code'];
                $ann_code = $_SESSION['ann_code'];
                $cycle = $_SESSION['cycle'];
                $_SESSION['sem_code'] = "0";
                
                //$sem_desl = " السداسي ";
                 $sem_desl = ($_SESSION['langue'] == 'AR') ? 'السداسي ' : 'Semestre ';
                 
                
	$sqlQuery = "SELECT * FROM rdn_etus ";
	$sqlQuery.= " WHERE matricule = '".$_SESSION['matricule']."'";
                  $sqlQuery .= " AND  par_code = '".$par_code."'";
                  $sqlQuery .= " AND  ann_code = '".$ann_code."'";
                  $sqlQuery .= " AND  cycle = '".$cycle."'";
                  $sqlQuery .= " AND  ann_univ = '".$_SESSION['ann_univ']."'";
                   
	$result = mysqli_query($this->dbConnect, $sqlQuery);
         
                  $semestres_arr = array();
                    
	if($semestre = mysqli_fetch_assoc($result) ) {
                        $_SESSION['sem_code'] = $semestre['code_s1'];
                        //$semestres_arr = array();
                        $semestres_arr[] = array("sem_code" => $semestre['code_s1'], "sem_desl" => $sem_desl.$semestre['desl_s1']);
                        $semestres_arr[] = array("sem_code" => $semestre['code_s2'], "sem_desl" => $sem_desl.$semestre['desl_s2']);
                }                             
                    echo json_encode($semestres_arr);
                 
                /*
                        //$_SESSION["ann_code"] = $semestre['ann_code'];
                        $_SESSION["sem_code"] = $semestre['code_s1'];
			
				echo '<button type="button" name="add" id='.$semestre['code_s1'].' class="btn btn-success btn-xs getNotesSemestre">'.$sem_desl.$semestre['desl_s1'].'</button>';
				//echo '<div class="space"></div>';
				echo '<button type="button" name="add" id='.$semestre['code_s2'].' class="btn btn-success btn-xs getNotesSemestre">'.$sem_desl.$semestre['desl_s2'].'</button>';
			}     
	}        
         
         $ann_code = $_SESSION['ann_code'];
         
                  if($ann_code=='1'){$s1 = 1; $s2 = 2;}
	if($ann_code=='2'){$s1 = 3; $s2 = 4;}
	if($ann_code=='3'){$s1 = 5; $s2 = 6;}
                  $sem_desl1 = "السداسي" . " " . $s1;   
                  $sem_desl2 = "السداسي" . " " . $s2;   
                  $sem_desl1 = ($_SESSION['langue'] == 'AR') ? 'السداسي'.' '.$s1 : 'Semestre '.$s1;
                  $sem_desl2 = ($_SESSION['langue'] == 'AR') ? 'السداسي'.' '.$s2 : 'Semestre '.$s2;
                
                              
                $_SESSION['sem_code'] = $s1;
                $semestres_arr = array();
                $semestres_arr[] = array("sem_code" => $s1, "sem_desl" => $sem_desl1);
                $semestres_arr[] = array("sem_code" => $s2, "sem_desl" => $sem_desl2);
                
              
               
                // encoding array to json format
                echo json_encode($semestres_arr); */
        }
         
     public function get_EnseignantRecours(){    
                $eta_code  = $_POST['eta_code'];
                $uem_ckey  = $_POST['uem_ckey'];
                
                $ctc_code = $_SESSION['ctc_code'];
                if($ctc_code == 'CTC'){$ctc_code = 'CC1';}
                
                $sqlQuery = "SELECT DISTINCT matricule_ens, gpw_users.nom, gpw_users.prenom, gpw_users.nom_ar, gpw_users.prenom_ar  FROM gpw_ctc_nots ";
                $sqlQuery .= " INNER JOIN gpw_users ON gpw_ctc_nots.matricule_ens  = gpw_users.matricule";
                $sqlQuery .= " WHERE gpw_ctc_nots.matricule = '".$_SESSION['matricule']."'";
                $sqlQuery .= "  AND gpw_ctc_nots.eta_code = '".$eta_code."'";
                $sqlQuery .= "  AND gpw_ctc_nots.mat_code = '".$uem_ckey."'";
                $sqlQuery .= "  AND gpw_ctc_nots.ctc_code = '".$ctc_code."'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                //$sqlQuery .= "  LIMIT 1";
                //echo $sqlQuery;
                        
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $numRows = mysqli_num_rows($result);
                if($numRows == 0){
                    $sqlQuery = "SELECT DISTINCT matricule_ens, gpw_users.nom, gpw_users.prenom, gpw_users.nom_ar, gpw_users.prenom_ar  FROM gpw_ens_mats ";
                    $sqlQuery .= " INNER JOIN gpw_users ON gpw_ens_mats.matricule_ens  = gpw_users.matricule";
                    //$sqlQuery .= " WHERE gpw_ctc_nots.matricule = '".$_SESSION['matricule']."'";
                    $sqlQuery .= "  AND  gpw_ens_mats.eta_code = '".$_SESSION['eta_code']."'";
                    $sqlQuery .= "  AND  gpw_ens_mats.mat_code = '".$_SESSION['mat_code']."'";
                    $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                    $result = mysqli_query($this->dbConnect, $sqlQuery);
                }
                
                $row_id = 0;
                $cycle = "";
                $enseignant_arr = array();
                while( $row = mysqli_fetch_array($result) ){
                    $row_id++;
                    if ($row_id == 1){$_SESSION['matricule_ens'] = $row['matricule_ens'];}
                   // $mat_code = $row['mat_code'];
                    if($_SESSION['langue'] == 'AR'){
                        
                    }    
                    $nom_prenom = ($_SESSION['langue'] == 'AR') ? $row['nom_ar'].' '.$row['prenom_ar'] : $row['nom'].' '.$row['prenom'];
                    $enseignant_arr[] = array("matricule_ens" => $row['matricule_ens'], "nom_prenom" => $nom_prenom);
                 }
               
                // encoding array to json format
                echo json_encode($enseignant_arr);
        }

        public function get_ListeChoix($par_code, $ann_code, $chx_code) {      
                $sqlQuery = "SELECT * FROM gpw_ori_chxs";
                $sqlQuery .= " WHERE par_code = '$par_code'";
                $sqlQuery .= " AND ann_code = '$ann_code'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                $sqlQuery .= " ORDER BY nord ASC";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while($choix = mysqli_fetch_assoc($result) ) {       
                    $selected = ($choix['chx_code'] == $chx_code)? ' selected': '';
                    echo '<option value="' . $choix['chx_code'] . '"'.$selected.'>' . $choix['chx_desl_ar']  . '</option>';
                    //echo '<option value="' . $choix['chx_code'] . '"'.$selected.'>' . $choix['chx_code'] . '-' . $choix['chx_desl_ar']  . '</option>';
        }
    }

        public function get_EtudiantChoix($matricule, $par_code, $ann_code, $chx_num) {      
                $chx_code = "";
                $sqlQuery = "SELECT * FROM gpw_ori_etu_chxs";
                $sqlQuery .= " WHERE matricule = '$matricule'";
                $sqlQuery .= " AND par_code = '$par_code'";
                $sqlQuery .= " AND ann_code = '$ann_code'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                $sqlQuery .= " AND chx_num = ".$chx_num;
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $numRows = mysqli_num_rows($result);
                if($numRows > 0){
                    $choix = mysqli_fetch_assoc($result);
                    $chx_code = $choix['chx_code'];
                }
                return $chx_code;
                    
    }
    
    public function saveEtudiantChoix($matricule, $par_code, $ann_code, $chx_num) {      
        if(!empty($matricule)) {
            $chx_code = ($chx_num == 1)? $_POST['fdv_choix1']: $_POST['fdv_choix2'];
            switch($chx_num) {
                 case 1:  {$chx_code = $_POST['fdv_choix1']; break;}
                case 2:  {$chx_code = $_POST['fdv_choix2']; break;}
                case 3:  {$chx_code = $_POST['fdv_choix3']; break;}
                case 4:  {$chx_code = $_POST['fdv_choix4']; break;}
                default: {$chx_code = $_POST['fdv_choix1']; break;}
            }
            
            $ann_univ = $_SESSION['ann_univ'];
            $sqlQuery = "SELECT * FROM gpw_ori_etu_chxs";
            $sqlQuery .= " WHERE matricule = '$matricule' AND par_code = '$par_code' AND ann_code = '$ann_code' AND ann_univ = '$ann_univ' AND chx_num = $chx_num";
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            $numRows = mysqli_num_rows($result);
            
            if($numRows == 0){
                $stmt = $this->dbConnect->prepare("INSERT INTO  gpw_ori_etu_chxs(matricule, par_code, ann_code, ann_univ, chx_num, chx_code, username, datec) VALUES(?,?,?,?,?,?,?,now())");
                $stmt->bind_param("ssssiss", $matricule, $par_code, $ann_code, $ann_univ, $chx_num, $chx_code,$_SESSION['username']); ///, $recoursId);
            }else{
                $stmt = $this->dbConnect->prepare("UPDATE gpw_ori_etu_chxs SET chx_code = ?, username = ?, datem=now()  WHERE matricule=? AND par_code=? AND ann_code=? AND chx_num=?");
                $stmt->bind_param("sssssi", $chx_code, $_SESSION['username'], $matricule, $par_code, $ann_code,$chx_num);
           }     
            $stmt->execute();
            $stmt->close();
            return;
        }
	}	

 	
}