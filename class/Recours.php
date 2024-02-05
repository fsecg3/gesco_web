<?php

class Recours extends Database {  
    private $recoursTable = 'gpw_recs';
	private $recoursRepliesTable = 'gpw_recs_reps';
	private $departementsTable = 'gpw_etas';
	private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	public function showRecours(){
                   ini_set('max_execution_time', 0);
                    //header('Content-Type: text/html; charset=utf-8');
		//echo '<script>alert("GPW")</script>';
		$sqlWhere = '';	
		if(isset($_SESSION["user_type"])) {
                    if ($_SESSION['user_type'] == '4'){
			$sqlWhere .= " WHERE r.matricule = '".$_SESSION["matricule"]."' ";
                    }else if ($_SESSION['user_type'] == '3'){
			$sqlWhere .= " WHERE r.matricule_ens = '".$_SESSION["matricule"]."' ";
                    }else if ($_SESSION['user_type'] == '2'){ // && $_SESSION['DB'] != 'UFC_WEB'){
			$sqlWhere .= " WHERE r.eta_code = '".$_SESSION["eta_code"]."' ";
                        //////////////////////////////$sqlWhere .= " AND u.cycle = '".$_SESSION["cycle"]."' ";
                    }
                }
                
                 $pos = strpos($sqlWhere, 'WHERE');
                 if ($pos === false) {                    
                     $sqlWhere .= " WHERE r.ann_univ = '".$_SESSION["ann_univ"]."' ";
                 }else{
                     $sqlWhere .= " AND r.ann_univ = '".$_SESSION["ann_univ"]."' ";
                 }
                $sqlWhere .= " AND r.id > 0 ";
                if($_SESSION['user_type'] != '4'){
                    if ($_SESSION['type_recours'] == '1'){
                         $sqlWhere .= " AND r.id NOT IN (SELECT rec_id FROM gpw_recs_reps) AND r.resolved <> '1'";
                    }else if ($_SESSION['type_recours'] == '2'){
                        $sqlWhere .= " AND (r.id IN (SELECT rec_id FROM gpw_recs_reps) OR r.resolved = '1')";
                    }else if ($_SESSION['type_recours'] == '3'){
                        $sqlWhere .= " AND r.resolved = '1'";
                    }               
                    
                    if ($_SESSION['cycle_recours'] != '0'){
                         $sqlWhere .= " AND u.cycle = '".$_SESSION['cycle_recours']."'";
                    }
                }
                if($_SESSION['user_type'] != '4' && ($_SESSION['DB'] == 'FSIC_WEB' || $_SESSION['DB'] == 'FSPRI_WEB')){
                      $sqlWhere .= " AND locate(left(right(r.uem_ckey,3),1), '".'135'."')=0";
                }

                if($_SESSION['user_type'] == '1' && $_SESSION['DB'] == 'UFC_WEB'){
                      $sqlWhere .= " AND r.ctc_code='"."CC1'";
                }
                if($_SESSION['user_type'] == '2' && $_SESSION['DB'] == 'UFC_WEB'){
                      $sqlWhere .= " AND r.ctc_code<>'"."CC1'";
                }
                
		//$time = new time;  			 
		$sqlQuery = "SELECT r.id, r.matricule, r.uem_ckey, r.ctc_code, r.eta_code, r.matricule_ens, concat(e.nom, ' ', e.prenom) as nom_prenom_ens, concat(e.nom_ar, ' ', e.prenom_ar) as nom_prenom_ens_ar, u.cycle, r.rec_type, l.ldc_desl, l.ldc_desl_ar, r.rec_message, r.last_reply, r.admin_read, r.ens_read, r.resolved, r.datec, concat(u.nom, ' ', u.prenom) as nom_prenom, concat(u.nom_ar, ' ', u.prenom_ar) as nom_prenom_ar, d.eta_desl, d.eta_desl_ar, '".$_SESSION['user_type']. "' as session_user_type";
		$sqlQuery .= " FROM gpw_recs r 
			LEFT JOIN gpw_users u ON r.matricule = u.matricule 
			LEFT JOIN gpw_ldcs l ON r.rec_type = l.id 
			LEFT JOIN gpw_users e ON r.matricule_ens = e.matricule 
                LEFT JOIN gpw_etas d ON r.eta_code = d.eta_code $sqlWhere ";
		
                if(!empty($_POST["search"]["value"]) && $_SESSION['user_type'] != '4'){
                    $search = $_POST["search"]["value"];
                    $encoded_search = $search.'%';; ///html_entity_decode($search, ENT_COMPAT, 'UTF-8').'%';
                    $pos = strpos($sqlQuery, 'WHERE');
                    if ($pos === false) {                    
                            $sqlQuery .= " WHERE e.nom_ar LIKE '".$_POST["search"]["value"]."%'"." ";
                    }else {
                        //$sqlQuery .=   " AND left(e.nom_ar,2) = '$encoded_search'";  //" AND e.nom_ar LIKE '".$_POST["search"]["value"]."%'"." ";
                        $sqlQuery .=   " AND u.nom_ar LIKE '".$_POST["search"]["value"]."%'"." ";
                    }    
		//	$sqlQuery .= ' AND (dep_id LIKE "%'.$_POST["search"]["value"].'%") ';					
		//	$sqlQuery .= ' OR title LIKE "%'.$_POST["search"]["value"].'%" ';
		//	$sqlQuery .= ' OR resolved LIKE "%'.$_POST["search"]["value"].'%" ';
		//	$sqlQuery .= ' OR last_reply LIKE "%'.$_POST["search"]["value"].'%") ';			
		}
	$sqlQuery .= " ORDER BY r.id ASC";
        /*
                                    mysqli_set_charset($this->dbConnect, 'utf8');  
                                    mysqli_query($this->dbConnect, "SET NAMES 'utf8';");
                                    mysqli_query($this->dbConnect, "SET CHARACTER SET 'utf8';");
                                    mysqli_query($this->dbConnect, "SET COLLATION_CONNECTION = 'utf8_general_ci';");
         
         */
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$recoursData = array();	
                                    if($numRows > 0){
		while( $recours = mysqli_fetch_assoc($result) ) {
			
			$recoursRows = array();
                        
                        if ($recours['cycle'] == '2'){
                            $cycle = 'ماستر';
                        }else {
                            $cycle = 'ليسانس';
                        }
                        if ($_SESSION['langue'] == 'FR'){
                            if($recours['cycle'] == '2'){
                                $cycle = 'Master CP';
                            }else {
                                $cycle = 'Ingéniorat';
                            }
                        }
                        $status = '';
			//$rec_title = $recours['rec_title'];
			$disbaled = '';
                        $recoursRepliesCreated = $this->isRecoursRepliesCreated($recours['id']);
                        
                        $recoursRows[] = $recours['id'];
                        $nom_prenom = ($_SESSION['langue'] == 'AR')? $recours['nom_prenom_ar']: $recours['nom_prenom'];
                        $recoursRows[] = $nom_prenom;
                        $par_desl = "";
                        $ann_code = "";
                        $sem_code = "";
						$grp_code = "";
                        $uem_desl = "";
                        list($par_desl, $ann_code, $sem_code, $grp_code, $uem_desl) = $this->getIntituleInscriptionMatiere($recours['matricule'], $recours['uem_ckey'], $_SESSION['langue']);
						$cycle .= ' '.$par_desl;
                         if($_SESSION['langue'] == 'AR'){                           
                        $cycle .= ' / سنة '.$ann_code.'،';
                        $cycle .= ' سداسي '.$sem_code."، "."فوج ".$grp_code."";
                        }else{
                        $cycle .= 'Année:'.$ann_code.'،';
                        $cycle .= 'Semestre '.$sem_code."، "."Groupe ".$grp_code."";
                            
                        }
                        //$cycle .= ' '.$par_desl;
                        //$cycle .= ' - '.' فوج'.$grp_code;

                        $recoursRows[] = $cycle;
                         if($_SESSION['langue'] == 'AR'){                           
                        
                            switch($recours['ctc_code']) {
                                case 'CC1':  {$ctc_desl = 'أعمال موجهة'; break;}
                                case 'CTC':  {$ctc_desl = 'مال موجهة'; break;}
                                case 'CC2':  {$ctc_desl = 'أعمال تطبيقية'; break;}
                                case 'CC3':  {$ctc_desl = 'إعمال موجهة'; break;}
                                case 'EXA':  {$ctc_desl = 'إمتحان'; break;}
                                case 'RAT':  {$ctc_desl = 'إستدراك'; break;}
                                
                                default: {$ctc_desl='مال موجهة'; break;}
                                
                                }
                                
                                if($_SESSION['DB'] == 'UFC_WEB'){
                                    if($recours['ctc_code'] == 'CC1'){$ctc_desl = 'الأرضية';}
                                    if($recours['ctc_code'] == 'CC2'){$ctc_desl = 'التجمعات';}
                                    if($recours['ctc_code'] == 'CC3'){$ctc_desl = 'الإمتحان';}
                                }
                                
                                $recoursRows[] = $recours['ldc_desl_ar'].' ('.$ctc_desl.')';
                         }else{
                            switch($recours['ctc_code']) {
                                case 'CC1':  {$ctc_desl = 'CC'; break;}
                                case 'CTC':  {$ctc_desl = 'CC'; break;}
                                case 'CC2':  {$ctc_desl = 'TP'; break;}
                                case 'CC3':  {$ctc_desl = 'TD'; break;}
                                case 'EXA':  {$ctc_desl = 'Examen'; break;}
                                case 'RAT':  {$ctc_desl = 'Rattrapage'; break;}
                                
                                default: {$ctc_desl='مال موجهة'; break;}
                                }
                                $recoursRows[] = $recours['ldc_desl'].' ('.$ctc_desl.')';
                              
                          }
			$recoursRows[] = $recours['rec_message'];

			$recoursRows[] = $uem_desl; //$this->getIntituleMatiere($recours['uem_ckey'], 'AR');
                                                      $eta_desl = ($_SESSION['langue'] == 'AR')? $recours['eta_desl_ar']: $recours['eta_desl'];
			$recoursRows[] = $eta_desl; 			
			$recoursRows[] = $recours['nom_prenom_ens']; //$time->ago($recours['datec']);
                        $recoursRows[] = $recours['datec']; //$time->ago($recours['datec']);
                        
			$recoursRows[] = $status;
                        if ($_SESSION['user_type'] == '4'){  
                            if ($recoursRepliesCreated === true){
                                if($_SESSION['langue'] == 'AR'){     
                                $recoursRows[] = '<a href="view_recours.php?id='.$recours["id"].'" class="btn btn-success btn-xs update">قراءة الرد</a>';
                                }else{
                                $recoursRows[] = '<a href="view_recours.php?id='.$recours["id"].'" class="btn btn-success btn-xs update">Lire Réponse</a>';
                                }
                            }
                            else {
                                ///////////////$recoursRows[] = "";
                                if($_SESSION['langue'] == 'AR'){     
                                    $recoursRows[] = '<button type="button" name="delete_recours" id="'.$recours["id"].'" class="btn btn-warning btn-xs delete_recours" '.$disbaled.'>حذف الطعن</button>';
                                }else{
                                    $recoursRows[] = '<button type="button" name="delete_recours" id="'.$recours["id"].'" class="btn btn-warning btn-xs delete_recours" '.$disbaled.'>Supprimer Recours</button>';
                                }
                                ///////provisoirement $recoursRows[] = '<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-warning btn-xs redirect_recours" '.$disbaled.'>تحويل الطعن</button>';
                            }    
                            //$recoursRows[] = '<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-warning btn-xs update" '.$disbaled.'>قراءة الرد</button>';
                            $recoursRows[] = ""; //'<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-warning btn-xs update" '.$disbaled.'>Edit</button>';
                            $recoursRows[] = ""; //'<button type="button" name="delete" id="'.$recours["id"].'" class="btn btn-danger btn-xs delete"  '.$disbaled.'>Close</button>';
                        }else if ($_SESSION['user_type'] == '3'){    
                            $recoursRows[] = '<a href="view_recours.php?id='.$recours["id"].'" class="btn btn-success btn-xs update">الرد على الطعن</a>';	
                            //$recoursRows[] = '<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-success btn-xs update" '.$disbaled.'>الرد على الطعن</button>';
                            $recoursRows[] = ""; //'<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-warning btn-xs update" '.$disbaled.'>Edit</button>';
                            if ($recours['resolved'] == '1'){
                                $recoursRows[] = "";
                            }else {
                                $recoursRows[] = '<button type="button" name="delete" id="'.$recours["id"].'" class="btn btn-danger btn-xs closeRecours"  '.$disbaled.'>إنهاء الطعن</button>';
                            }    
                        }else if ($_SESSION['user_type'] <= '2'){    
                            $recoursRows[] = '<a href="view_recours.php?id='.$recours["id"].'" class="btn btn-success btn-xs update">الرد على الطعن</a>';
                            //$recoursRows[] = '<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-success btn-xs update" '.$disbaled.'>الرد على الطعن</button>';
                            $recoursRows[] = '<button type="button" name="update" id="'.$recours["id"].'" class="btn btn-warning btn-xs redirect_recours" '.$disbaled.'>تحويل الطعن</button>';
                            /*if ($recoursRepliesCreated){
                                $recoursRows[] = '<a href="view_recours.php?id='.$recours["id"].'" class="btn btn-success btn-xs read_recours">قراءة الرد</a>';
                            }else {
                                $recoursRows[] = "";
                            }    */
                            if ($recours['resolved'] == '1'){
                                $recoursRows[] = "";
                            }else {
                                $recoursRows[] = '<button type="button" name="delete" id="'.$recours["id"].'" class="btn btn-danger btn-xs closeRecours"  '.$disbaled.'>إنهاء الطعن</button>';
                            }    
                        }
                        
                        
                        $recoursData[] = $recoursRows;
		} // while...
                          } //numRows
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=>  $numRows,
			"data"    			=> 	$recoursData
		);
		echo json_encode($output);
	}	
	public function getRepliedTitle($title) {
		$title = $title.'<span class="answered">الرد موجود</span>';
		return $title; 		
	}
	public function createRecours() {      
		//if(!empty($_POST['rec_title']) && !empty($_POST['rec_message'])) {
		if (1==1){	
			$date = new DateTime();
			$date = $date->getTimestamp();
			//$uniqid = uniqid();                
			$message = strip_tags($_POST['rec_message']);
                                                      $eta_code = $_POST['eta_code'];
                                                      if($eta_code == '0'){
                                                         $eta_code = $_SESSION['eta_code'];
                                                      }
			$queryInsert = "INSERT INTO ".$this->recoursTable." (matricule, rec_title, rec_message, eta_code, matricule_ens, datec, last_reply, etu_read, ens_read, admin_read, resolved) 
			VALUES('".$_SESSION["matricule"]."', '".$_POST['red_title']."', '".$message."', '".$_POST['eta_code']."', '', '".$date."', '".$_SESSION["userid"]."', 0, 0, 0'".$_POST['status']."')";			
			mysqli_query($this->dbConnect, $queryInsert);			
			//echo 'success ' . $uniqid;
		} else {
			echo '<div class="alert error">Please fill in all fields.</div>';
		}
	}	
	public function getRecoursDetails(){
		echo '<script>alert("listRecours")</script>';
		echo '<script>alert("getUserInfo")</script>';
		if($_POST['recoursId']) {	
			$sqlQuery = "
				SELECT gpw_recs.*, unix_timestamp(datec) as date_recours FROM ".$this->recoursTable." 
				WHERE id = '".$_POST["recoursId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);
		}
	}
	public function updateRecours() {
		if($_POST['recoursId']) {	
			$updateQuery = "UPDATE ".$this->recoursTable." 
			SET title = '".$_POST["subject"]."', department = '".$_POST["department"]."', init_msg = '".$_POST["message"]."', resolved = '".$_POST["status"]."'
			WHERE id ='".$_POST["recoursId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}		
	public function closeRecours(){
		if($_POST["recoursId"]) {
			$sqlDelete = "UPDATE ".$this->recoursTable." 
				SET resolved = '1'
				WHERE id = '".$_POST["recoursId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}	
	public function getDepartments() {       
		echo '<script>alert("Liste départements")</script>';
		$sqlQuery = "SELECT * FROM ".$this->departmentsTable;
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(empty($result)){
	    	echo("email was not found");
		}
		else{
			echo "dépt found";
		}
		echo "<option value='"."aaa"."'>"."bbb"."</option>";
		
		while($department = mysqli_fetch_assoc($result) ) {     
			echo "<option value='".$department['id']."'>".$department['desl_ar']."</option>";
			//echo '<option value="' . $department['id'] . '">' . $department['desl_ar']  . '</option>';           
        }
    }	    
    public function recoursInfo($id) {  
		//echo "recours was not found";
                                    if($_SESSION['langue'] == 'AR'){
                                        $sqlQuery = "SELECT t.id, t.matricule, t.rec_type, l.ldc_desl_ar as title, t.rec_message as message, t.datec, t.last_reply, t.resolved, concat(u.nom_ar, ' ', u.prenom_ar) as creater, d.dep_desl_ar as department , unix_timestamp(t.datec) as date_recours, '".$_SESSION['user_type']. "' as session_user_type";
                                    }else{
                                        $sqlQuery = "SELECT t.id, t.matricule, t.rec_type, l.ldc_desl as title, t.rec_message as message, t.datec, t.last_reply, t.resolved, concat(u.nom, ' ', u.prenom) as creater, d.dep_desl as department , unix_timestamp(t.datec) as date_recours, '".$_SESSION['user_type']. "' as session_user_type";
                                    }
		$sqlQuery .= "	FROM ".$this->recoursTable." t 
			LEFT JOIN gpw_users u ON t.matricule = u.matricule 
			LEFT JOIN gpw_etas d ON t.eta_code = d.eta_code
                        LEFT JOIN gpw_ldcs l ON t.rec_type = l.id
			WHERE t.id = '".$id."'";	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
        $recourss = mysqli_fetch_assoc($result);
        return $recourss;        
    }    
    
public function saveRecoursReplies () {
                                    $message = "";
                                    if(isset($_POST['message'])){
                                        $message =  $_POST['message'];
                                    }
                                    
                                    $rep_note = "";
                                    if(isset($_POST['new_note'])){
                                        $rep_note =  $_POST['new_note'];
                                        if ($rep_note < 0 || $rep_note > 20){
                                               $rep_note = '';
                                        }
                                    }
                                    
                                    if($message != '' || $rep_note != ''){ //$_POST['message'] || $_POST['new_note']) {
			$date = new DateTime();
			$date = $date->getTimestamp();
                                                      $rep_type = '0';
                                                      //$message =  $_POST['message'];
                                                      $recoursId = $_POST['recoursId'];
                                    if($rep_note == ''){$rep_note = null;}
                                                      //if(isset($_POST['new_note'])){
                                                      //    $rep_note = $_POST['new_note'];
                                                       //   if ($rep_note < 0 || $rep_note > 20){
                                                       //       $rep_note = '';
                                                        //  }
                                                      
                                                      
                                                    $stmt = $this->dbConnect->prepare("INSERT INTO gpw_recs_reps(matricule, ann_univ, rep_type, rep_message, rep_note, rec_id, datec) VALUES(?,?,?,?,?,?,now())");
                                                    $stmt->bind_param("sssssi", $_SESSION["matricule"], $_SESSION['ann_univ'], $rep_type,$message,$rep_note,$recoursId); ///, $recoursId);
                                                    $stmt->execute();
                                                    $stmt->close();
/*
			$queryInsert = "INSERT INTO ".$this->recoursRepliesTable." (matricule, ann_univ, rep_type, rep_message, rep_note, rec_id, datec) 
				VALUES('".$_SESSION["matricule"]."', '".$_SESSION['ann_univ']."', 0,'".$_POST['message']."', '".$_POST['recoursId']."', Now())";
			mysqli_query($this->dbConnect, $queryInsert);
                        */
			$updateRecours = "UPDATE gpw_recs SET last_reply = '".$_SESSION["userid"]."', ens_read = '0', admin_read = '0'  WHERE id = ".$recoursId;
			mysqli_query($this->dbConnect, $updateRecours);
		} 
	}	
	public function getRecoursReplies($id) {  		
		$sqlQuery = "SELECT r.id, r.rep_message as message, rep_note, unix_timestamp(r.datec) as date_reply, concat(u.nom_ar, ' ', u.prenom_ar) as creater, 'd.eta_desl_ar' as departement, u.user_type, '".$_SESSION['user_type']. "' as session_user_type";  
		$sqlQuery .= "	FROM ".$this->recoursRepliesTable." r
			LEFT JOIN ".$this->recoursTable." t ON r.rec_id = t.id
			LEFT JOIN gpw_users u ON r.matricule = u.matricule 
			WHERE r.rec_id = '".$id."'";	
       			//LEFT JOIN gpw_etas d ON t.eta_code = d.eta_code

		$result = mysqli_query($this->dbConnect, $sqlQuery);
       	$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
        return $data;
    }
	public function updateRecoursReadStatus($recoursId) {
		$updateField = '';
		if(isset($_SESSION["admin"])) {
			$updateField = "admin_read = '1'";
		} else {
			$updateField = "ens_read = '1'";
		}
		$updateRecours = "UPDATE ".$this->recoursTable." 
			SET $updateField
			WHERE id = '".$recoursId."'";				
		mysqli_query($this->dbConnect, $updateRecours);
	}
        
        public function getIntituleMatiere($puem_ckey, $langue) {       
		$sqlQuery = "SELECT uem_desl, uem_desl_ar FROM rdn_notes";
                $sqlQuery .= " WHERE uem_ckey = '".$puem_ckey."'";
                $sqlQuery .= " AND uem_type = 'MAT'";
                $sqlQuery .= " LIMIT 1";
                
		$result = mysqli_query($this->dbConnect, $sqlQuery);
                $row = mysqli_fetch_assoc($result);
                if ($row){
                   if ($langue == 'AR'){ 
			return $row["uem_desl_ar"];
                    }else{	
                        return $row["uem_desl"];
                    }
                }    
    }
    
        public function getIntituleInscriptionMatiere($matricule, $puem_ckey, $langue) {     
                $par_code = "";
                $par_desl = "";
                $ann_code = "";
                $sem_code = "";
				$grp_code = "";
                $uem_desl = "";
                
                $sqlQuery = "SELECT rdn_notes.par_code, par_desl, par_desl_ar, rdn_notes.ann_code, rdn_notes.sem_code, grp_code, uem_desl, uem_desl_ar FROM rdn_notes";
                //$sqlQuery .= " INNER JOIN gpw_pars ON rdn_notes.par_code  = gpw_pars.par_code AND rdn_notes.ann_univ = gpw_pars.ann_univ";
                $sqlQuery .= " INNER JOIN rdn_etus ON rdn_notes.matricule = rdn_etus.matricule AND rdn_notes.par_code  = rdn_etus.par_code AND rdn_notes.ann_code = rdn_etus.ann_code AND rdn_notes.ann_univ = rdn_etus.ann_univ";
                $sqlQuery .= " WHERE rdn_notes.matricule = '".$matricule."'";
                $sqlQuery .= " AND uem_ckey = '".$puem_ckey."'";
                $sqlQuery .= " AND uem_type = 'MAT'";
                $sqlQuery .= " AND rdn_notes.ann_univ = '".$_SESSION['ann_univ']."'";
                $sqlQuery .= " LIMIT 1";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $row = mysqli_fetch_assoc($result);
                if ($row){
                    $par_code = $row['par_code'];
                    $ann_code = $row['ann_code'];
                    $sem_code = $row['sem_code'];
					$grp_code = $row['grp_code'];
                    $par_desl = ($_SESSION['langue'] == 'AR')?  $row["par_desl_ar"]:$row["par_desl"];
                    $uem_desl = ($_SESSION['langue'] == 'AR')?  $row["uem_desl_ar"]:$row["uem_desl"];
                }
                return array($par_desl, $ann_code, $sem_code, $grp_code, $uem_desl); 
    }
    
    public function isRecoursRepliesCreated($rec_id) {       
		$sqlQuery = "SELECT id FROM gpw_recs_reps";
                //$sqlQuery .= " WHERE matricule = ".$_SESSION['matricule'];
                $sqlQuery .= " WHERE rec_id = ".$rec_id."";
                
		$result = mysqli_query($this->dbConnect, $sqlQuery);
                $numRows = mysqli_num_rows($result);
                if ($numRows > 0){
                    return true;
                } else {
                    return false;
                }
    }
     
    public function get_TypesRecours() {       
                echo "<option value='0'>جميع الطعون</option>";
                echo "<option value='1' selected>طعون في انتظار المعالجة </option>";
                echo "<option value='2'>طعون معالجة</option>";
                echo "<option value='3'>طعون منتهية</option>";
                
    }	    
   
}