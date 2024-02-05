<?php

class Stats extends Database {  
    private $recoursTable = 'gpw_ctc_nots';
        private $recoursRepliesTable = 'gpw_recs_reps';
	private $departementsTable = 'gpw_deps';
        static $mat_code = "";
        static $ctc_code = "";
        static $sec_code = "0";
        static $grp_code = "0";
        public $selected_mat_code = "";
        private $seuil_vert = 80;
        private $seuil_orange = 40;
        private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
        public function stats_SaisieNotes(){
                $mat_code = $_SESSION["mat_code"];
                $ctc_code = $_SESSION["ctc_code"];
                $sec_code = $_SESSION["sec_code"];
                $grp_code = $_SESSION["grp_code"];

                $sqlQuery = "SELECT DISTINCT gpw_ctc_nots.eta_code, gpw_etas.eta_desl, gpw_etas.eta_desl_ar, gpw_ctc_nots.par_code, gpw_pars.par_desl, gpw_pars.par_desl_ar, gpw_ctc_nots.ann_code, gpw_ctc_nots.sem_code, gpw_ctc_nots.mat_code, gpw_ctc_nots.ctc_code, gpw_ctc_nots.matricule_ens, gpw_users.nom, gpw_users.prenom, gpw_users.nom_ar, gpw_users.prenom_ar, mat_desl, mat_desl_ar  FROM gpw_ctc_nots";
                ////////$sqlQuery .= " sum(if(selected='1',1,0)) AS total_selected, sum(if(imported='1' and modified>='0',1,0)) AS total_imported, sum(if(modified='1' and imported='0',1,0)) AS total_modified FROM gpw_ctc_nots";
                $sqlQuery .= " INNER JOIN gpw_etas ON gpw_etas.eta_code=gpw_ctc_nots.eta_code";
                $sqlQuery .= " INNER JOIN gpw_pars ON gpw_pars.par_code=gpw_ctc_nots.par_code";
                $sqlQuery .= " INNER JOIN gpw_ens_mats ON gpw_ens_mats.mat_code=gpw_ctc_nots.mat_code";
                $sqlQuery .= " INNER JOIN gpw_users ON gpw_ctc_nots.matricule_ens=gpw_users.username";
                $sqlQuery .= " WHERE gpw_ctc_nots.ann_univ = '".$_SESSION['ann_univ']."'";
                
                if(isset($_SESSION['eta_code']) && $_SESSION['eta_code'] != '0' && $_SESSION['eta_code'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ctc_nots.cycle = '".$_SESSION['cycle']."'";
                }
                if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == '2' && $_SESSION['user_type'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.eta_code = '".$_SESSION['user_eta_code']."'";
                }
                if(isset($_SESSION['par_code']) && $_SESSION['par_code'] != '0' && $_SESSION['par_code'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.par_code = '".$_SESSION['par_code']."'";
                }
                if(isset($_SESSION['ann_code']) && $_SESSION['ann_code'] != '0' && $_SESSION['ann_code'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.ann_code = '".$_SESSION['ann_code']."'";
                }
                if(isset($_SESSION['sem_code']) && $_SESSION['sem_code'] != '0' && $_SESSION['sem_code'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.sem_code = '".$_SESSION['sem_code']."'";
                }
                if(isset($_SESSION['mat_code']) && $_SESSION['mat_code'] != '0' && $_SESSION['mat_code'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.mat_code = '".$_SESSION['mat_code']."'";
                }
                if(isset($_SESSION['ctc_code']) && $_SESSION['ctc_code'] != '0' && $_SESSION['ctc_code'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.ctc_code = '".$_SESSION['ctc_code']."'";
                }                
                if(isset($_SESSION['matricule_ens']) && $_SESSION['matricule_ens'] != '0' && $_SESSION['matricule_ens'] != ''){
                    $sqlQuery .= " AND gpw_ctc_nots.matricule_ens = '".$_SESSION['matricule_ens']."'";
                }
                
                $sqlQuery .= " AND gpw_ctc_nots.selected = '"."1"."'";
                
                $sqlQuery .= " GROUP BY gpw_ctc_nots.eta_code,gpw_ctc_nots.par_code, gpw_ctc_nots.ann_code,gpw_ctc_nots.sem_code,  gpw_ctc_nots.mat_code, gpw_ctc_nots.ctc_code, gpw_ctc_nots.matricule_ens";
                
                                
                $li_row = 0;
                $cycle = '1';	
                $status = '1';
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $numRows = mysqli_num_rows($result);
	            $stats_SaisieNotesData = array();
                $output = array();	
                while( $recours = mysqli_fetch_assoc($result) ) {
                    $li_row = $li_row +1;
                        $eta_desl = ($_SESSION['langue'] == 'AR') ? $recours['eta_desl_ar'] : $recours['eta_desl'];
                        $eta_desl = $recours['eta_code'].' - '.$eta_desl;
                        $par_desl = ($_SESSION['langue'] == 'AR') ? $recours['par_desl_ar'] : $recours['par_desl'];
                        $par_desl = $recours['par_code'].'-'.$par_desl;
                        $mat_desl = ($_SESSION['langue'] == 'AR') ? $recours['mat_desl_ar'] : $recours['mat_desl'];
                        $nom_prenom = ($_SESSION['langue'] == 'AR') ? $recours['nom_ar'].' '.$recours['prenom_ar'] : $recours['nom'].' '.$recours['prenom'];
                        
                        $ctc_desl = "";
                        if($_SESSION['langue'] == 'AR'){
                            if ($recours['ctc_code'] == 'CC1'){$ctc_desl = "أعمال موجهة";}
                            if ($recours['ctc_code'] == 'EXA'){$ctc_desl = "إمتحان";}
                            if ($recours['ctc_code'] == 'RAT'){$ctc_desl = "إستدراك";}
                        }else{
                            if ($recours['ctc_code'] == 'CC1'){$ctc_desl = "CC";}
                            if ($recours['ctc_code'] == 'EXA'){$ctc_desl = "Examen";}
                            if ($recours['ctc_code'] == 'RAT'){$ctc_desl = "Rattrapage";}

                        }
                        //$stats($a,$b,($total_etudiants, $total_imported, $total_modified, $total_selected) = GetStatsEnseignant($recours);
                        $stats = $this->GetStatsEnseignant($recours);
                        $total_selected = $stats[0];
                        $total_imported = $stats[1];
                        $total_modified = $stats[2];
                        
                        
                        //if($recours['total_modified'] > 0){$total_modified = $recours['total_modified'];}
                        $total_saisie = $total_imported + $total_modified;
                        $taux_saisie = 0;
                        if($total_selected > 0){
                            $taux_saisie = round($total_saisie/$total_selected, 2)*100;
                        }
                       if($total_imported == 0){
                            $total_imported = "";
                        }
                        
                        if($total_modified == 0){
                            $total_modified = "";
                        }
                        
                        $recoursRows = array();
                        $output[] = array(
                            "pos"=>$li_row,
                            "etablissement"=>$eta_desl, 
                            "parcours"=>$par_desl, 
                            "annee"=>$recours['ann_code'], 
                            "semestre"=>$recours['sem_code'], 
                            "matiere"=>$mat_desl, 
                            "examen"=>$ctc_desl, 
                            "enseignant"=>$nom_prenom, 
                            "nbre_etudiants"=>$total_selected, 
                            "notes_importees"=>$total_imported,
                            "notes_non_importees"=>$total_modified,
                            "taux_saisie"=>'%'.$taux_saisie,
                            "taux_progression"=>$taux_saisie
                        );
                        /*
                        $disabled = ""; //disabled";
                        if($taux_saisie > $this->seuil_vert){
                            $recoursRows[] = '<button type="button" name="taux" id="'.'1'.'" class="btn btn-success btn-xs" '.$disabled.'>'.'%'.$taux_saisie.'</button>';
                        }else if($taux_saisie > $this->seuil_orange){
                                $recoursRows[] = '<button type="button" name="taux" id="'.'2'.'" class="btn btn-warning btn-xs"  '.$disabled.'>'.'%'.$taux_saisie.'</button>';
                       }else{
                                $recoursRows[] = '<button type="button" name="taux" id="'.'3'.'" class="btn btn-danger btn-xs"  '.$disabled.'>'.'%'.$taux_saisie.'</button>';
                        }    

                        $stats_SaisieNotesData[] = $recoursRows;
                        */
		}
/*		$output = array(
			//"draw"				=>	intval($_POST["draw"]),
			//"recordsTotal"  	=>  $numRows,
			//"recordsFiltered" 	=>  $numRows,
			"data"    			=> 	$stats_SaisieNotesData
		);
            $output = array();
            //$output[] = array("matricule_ens" => $matricule_ens, "nom_prenom" => $nom_prenom);
            
            $output[] = array("pos"=>"1","etablissement"=>"Billy Bob", "parcours"=>"12", "annee"=>"male", "semestre"=>"1", "matiere"=>"red", "examen"=>"", "enseignant"=>"1", "nbre_etudiants"=>"200", "notes_importees"=>"20", "notes_non_importees"=>"15", "taux_saisies"=>"15");
            *
            [pos=>"1",etablissement=>"Billy Bob", "parcours"=>"12", "annee"=>"male", "semestre"=>"1", "matiere"=>"red", "examen"=>"", "enseignant"=>"1", "nbre_etudiants"=>"200", "notes_importees"=>"20", "notes_non_importees"=>"15", "taux_saisies"=>"15"]
            //[id=>2, name=>"Mary May", progress=>"1", gender=>"female", height=>2, col=>"blue", dob=>"14/05/1982", driver=>true],
            //[id=>3, name=>"Christine Lobowski", progress=>"42", height=>0, col=>"green", dob=>"22/05/1982", driver=>"true"],
            //[id=>4, name=>"Brendon Philips", progress=>"125", gender=>"male", height=>1, col=>"orange", dob=>"01/08/1980"],
            //[id=>5, name=>"Margret Marmajuke", progress=>"16", gender=>"female", height=>5, col=>"yellow", dob=>"31/01/1999"],
        ];*/
		echo json_encode($output);
        //echo "[".json_encode($output,JSON_UNESCAPED_UNICODE)."]";
	}	
        
    public function GetStatsEnseignant($row){
        $sqlQuery = "SELECT sum(if(selected='1',1,0)) AS total_selected, sum(if(imported='1' and modified>='0',1,0)) AS total_imported, sum(if(modified='1' and imported='0',1,0)) AS total_modified FROM gpw_ctc_nots";
        $sqlQuery .= " WHERE gpw_ctc_nots.ann_univ = '".$_SESSION['ann_univ']."'";
        
        $sqlQuery .= " AND gpw_ctc_nots.eta_code = '".$row['eta_code']."'";
        $sqlQuery .= " AND gpw_ctc_nots.cycle = '".$_SESSION['cycle']."'";
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == '2' && $_SESSION['user_type'] != ''){
            $sqlQuery .= " AND gpw_ctc_nots.eta_code = '".$_SESSION['user_eta_code']."'";
        }
        $sqlQuery .= " AND gpw_ctc_nots.par_code = '".$row['par_code']."'";
        $sqlQuery .= " AND gpw_ctc_nots.ann_code = '".$row['ann_code']."'";
        $sqlQuery .= " AND gpw_ctc_nots.sem_code = '".$row['sem_code']."'";
        $sqlQuery .= " AND gpw_ctc_nots.mat_code = '".$row['mat_code']."'";
        $sqlQuery .= " AND gpw_ctc_nots.ctc_code = '".$row['ctc_code']."'";
        $sqlQuery .= " AND gpw_ctc_nots.matricule_ens = '".$row['matricule_ens']."'";
        
        $sqlQuery .= " AND gpw_ctc_nots.selected = '"."1"."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_assoc($result);
        if ($row){
            $r1 = $row['total_selected'];
            $r2 = $row['total_imported'];
            $r3 = $row['total_modified'];
             return array($r1, $r2, $r3);
        }else{	
                  return array(0,0,0);
        }        
        //return array($a,$$b,$c);
                
                
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
			$queryInsert = "INSERT INTO ".$this->recoursTable." (matricule, rec_title, rec_message, eta_code, ens_id, datec, last_reply, etu_read, ens_read, admin_read, resolved) 
			VALUES('".$_SESSION["matricule"]."', '".$_POST['red_title']."', '".$message."', '".$_POST['eta_code']."', 0, '".$date."', '".$_SESSION["userid"]."', 0, 0, 0'".$_POST['status']."')";			
			mysqli_query($this->dbConnect, $queryInsert);			
			//echo 'success ' . $uniqid;
		} else {
			echo '<div class="alert error">Please fill in all fields.</div>';
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
	
        public function getEnseignantMatieres() {       
                $this->mat_code = "";
                $row_id = 0;
		$sqlQuery = "SELECT cycle, ann_code, sem_code, mat_code, mat_desl, mat_desl_ar FROM gpw_ens_mats";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['username']."'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                $sqlQuery .= " ORDER BY mat_code ASC";
                
	$result = mysqli_query($this->dbConnect, $sqlQuery);
                 while($matieres = mysqli_fetch_assoc($result) ) {       
                    $row_id++;
                    if ($row_id == 1){$_SESSION['mat_code'] = $_matieres['mat_code'];}
                    $t=$matieres['ann_code'];
                    $cycle = ($matieres['cycle'] == '2') ? 'م' : 'ل';
                    $cycle .= $matieres['ann_code'];
                    $cycle .= 'س'.$matieres['sem_code']."- ";
                    
                    echo '<option value="' . $matieres['mat_code'] . '">' .$cycle.$matieres['mat_desl_ar']  . '</option>';
                }
                //$this->getEnseignantMatiereCtcs();
        } 
                
                
        public function getEnseignantMatiereCtcs(){    
               //if($_SESSION['ctc_code'] != 'EXA'){
                   $_SESSION['ctc_code'] = 'CC1';
                //}   
                echo '<option value="'.'CC1'.'">'.'أعمال موجهة'.'</option>';
                echo '<option value="'.'EXA'.'">'.'إمتحان'.'</option>';
                
                /*
		$sqlQuery = "SELECT DISTINCT ctc_code FROM gpw_ctc_nots";
                //$sqlQuery = "LEFT JOIN scl_ctcs ON gpw_ens_mats.ctc_code=scl_ctcs.ctc_code"
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['username']."'";
                $sqlQuery .= " AND mat_code = '". $_SESSION['mat_code']."'";
                $sqlQuery .= " ORDER BY ctc_code ASC";
	$result = mysqli_query($this->dbConnect, $sqlQuery);
                $row_id = 0;
                while($ctcs = mysqli_fetch_assoc($result) ) { 
                    $row_id++;
                    if ($row_id == 1){$_SESSION['ctc_code'] = $ctcs['ctc_code'];}
                    if ($ctcs['ctc_code'] == 'CC1'){$ctc_desl = "أعمال موجهة";}
                    if ($ctcs['ctc_code'] == 'EXA'){$ctc_desl = "إمتحان";}
                    if ($ctcs['ctc_code'] == 'RAT'){$ctc_desl = "إستدراك";}
                 echo '<option value="' . $ctcs['ctc_code'] . '">' . $ctc_desl  . '</option>';
                }*/
                //$this->getEnseignantMatiereSections();
        }
        
        public function getEnseignantMatiereSections() {
                $_SESSION['sec_code'] = '0';
                echo '<option value="'.'0'.'">'.'...'.'</option>';
                echo '<option value="'.'1'.'">'.'المجموعة 1'.'</option>';
                echo '<option value="'.'2'.'">'.'المجموعة 2'.'</option>';
                if($_SESSION['ctc_code'] != 'EXA'){
                    $_SESSION['ctc_code'] = 'CC1';
                }                   
                /*
                $sqlQuery = "SELECT DISTINCT sec_code FROM gpw_ctc_nots";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['username']."'";
                $sqlQuery .= " AND mat_code = '". $_SESSION['mat_code']."'";
                $sqlQuery .= " AND ctc_code = '". $_SESSION['ctc_code']."'";
                $sqlQuery .= " AND selected = '"."1"."'";
                $sqlQuery .= " ORDER BY sec_code ASC";
                
                echo '<option value="0"' . '>' . "...". '</option>';
                $row_id = 0;
	$result = mysqli_query($this->dbConnect, $sqlQuery);
	while($sections = mysqli_fetch_assoc($result) ) {       
                    $row_id++;
                    if ($row_id == 1){$_SESSION['sec_code'] = $sections['sec_code'];}
                   echo '<option value="' . $sections['sec_code'] . '">' . "المجموعة ".$sections['sec_code']  . '</option>';
                }*/
                //$this->getEnseignantMatiereGroupes();
        }
        
        public function getEnseignantMatiereGroupes() {
                
                $sqlQuery = "SELECT DISTINCT grp_code FROM gpw_ctc_nots";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['username']."'";
                $sqlQuery .= " AND mat_code = '". $_SESSION['mat_code']."'";
                $sqlQuery .= " AND ctc_code = '". $_SESSION['ctc_code']."'";
                //if (($_SESSION['sec_code'] != '0') && ($_SESSION['sec_code'] != '')){
                //    $sqlQuery .= " AND sec_code = '". $_SESSION['sec_code']."'";
                //}
                $sqlQuery .= " AND selected = '"."1"."'";
                
                $sqlQuery .= " ORDER BY grp_code ASC";
                echo '<option value="0"' . '>' . "...". '</option>';
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while($groupes = mysqli_fetch_assoc($result) ) {       
                 echo '<option value="' . $groupes['grp_code'] . '">' . "الفوج ".$groupes['grp_code']  . '</option>';
                }
        }
        
        public function Firsttime() {
           // $_SESSION['ctc_code'] = 'CC1';
            //getEnseignantMatiereSections();
        }
        
        public function getActions() {
                 $action1 = "موافق";
                 $action2 = " Excel";
                 echo '<button type="button" name="add" id='.'$FilterStats'.' class="btn btn-success btn-sm FilterStats">'.$action1.'</button>';
                 echo '&nbsp;&nbsp;&nbsp';
                 echo '<button type="button" name="add" id='.'ExportStatsExcel'.' class="btn btn-info btn-sm ExportStatsExcel">'.$action2.'</button>';
        }
        
       
        public function ExportNotesExcel() {
           /* (&$str){ 
            $str = preg_replace("/\t/", "\\t", $str); 
            $str = preg_replace("/\r?\n/", "\\n", $str); 
            if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
            }
            * */
            
 
            // Excel file name for download 
            $fileName = "members-data_" . date('Y-m-d') . ".xls"; 
 
            // Column names 
            $fields = array('ID', 'NOM', 'PRENOM', 'DATE NAIS.', 'STATUS'); 
 
            // Display column names as first row 
            $excelData = implode("\t", array_values($fields)) . "\n"; 
 
            // Fetch records from database 
            //$query = $dbConnect->query("SELECT * FROM gpw_users limit 10"); 
            $query = mysqli_query($this->dbConnect, "SELECT * FROM gpw_users limit 2");
	
            if($query->num_rows > 0){ 
                // Output each row of the data 
                while($row = $query->fetch_assoc()){ 
                $status = ($row['status'] == 1)?'Active':'Inactive'; 
                $lineData = array($row['id'], $row['nom'], $row['prenom'], $row['sexe'], $row['lieu_nais'], $row['datec'], $status); 
                //$lineData = array($row['id'], $row['first_name'], $row['last_name'], $row['email'], $row['gender'], $row['country'], $row['created'], $status); 
                //array_walk($lineData, 'filterData'); 
                $excelData .= implode("\t", array_values($lineData)) . "\n"; 
            } 
            }else{ 
                $excelData .= 'No records found...'. "\n"; 
            } 
 
            // Headers for download 
            header("Content-Type: application/vnd.ms-excel"); 
            //header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="aaa.xls"'); 
            header("Pragma: ");  
            header("Cache-Control: ");  
            $_REQUEST[$excelData]; 
            // Render excel data 
            echo $excelData;
            exit;
        }
        
        public function SaveNote($id, $note, $absent, $exclu, $deleted, $obs, $update_fields) {
                
              /*
        }
                $new_note = 'NULL';
                if ($note != ""){
                    $new_note = $note;
                }
                 
		$updateFields = '';
                $update_fields .= "note=".$new_note.", absent='".$absent."', exclu='".$exclu."', deleted='".$deleted."', obs='".$obs."'";
                
		if(isset($_SESSION["admin"])) {
			$updateFields = "admin_read = '1'";
		} else {
			$updateFields = "ens_read = '1'";
		}
                */
		$updateQuery = "UPDATE gpw_ctc_nots";
                
                if ($note == ""){
                    $updateQuery .= " SET note = NULL".",modified='"."1'".", datem=now()";
                }
                else{
                   $updateQuery .= " SET ".$update_fields.", modified='"."1'".", datem=now()";
                }
		$updateQuery .=	" WHERE id = ".$id;
                    
		mysqli_query($this->dbConnect, $updateQuery);
	}
        

        public function get_Etablissements() {       
                $row_id = 0;
                $sqlQuery = "SELECT DISTINCT gpw_ens_mats.cycle, gpw_ens_mats.eta_code, eta_desl, eta_desl_ar FROM gpw_ens_mats";
                $sqlQuery .= " INNER JOIN gpw_etas ON gpw_etas.eta_code = gpw_ens_mats.eta_code"; // AND gpw_etas.cycle = gpw_ens_mats.cycle"; 
                //$sqlQuery .= " WHERE matricule_ens = '".$_SESSION['username']."'";
                $sqlQuery .= " WHERE gpw_ens_mats.ann_univ = '".$_SESSION['ann_univ']."'";
                if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == '2' && $_SESSION['user_type'] != ''){
                    $sqlQuery .= " AND gpw_ens_mats.eta_code = '".$_SESSION['user_eta_code']."'";
                }
                $sqlQuery .= " ORDER BY gpw_ens_mats.eta_code ASC";
          
                
                $eta_code = "0";
                $eta_desl = ($_SESSION['langue'] == 'AR') ?  '...': '...';

                echo '<option value="' . $eta_code. '">'.$eta_desl .'</option>';
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while($etablissement = mysqli_fetch_assoc($result) ) {       
                    $row_id++;
                    if ($row_id == 1){$_SESSION['eta_code'] = $parcours['eta_code'];}
                    $eta_desl = ($_SESSION['langue'] == 'AR') ? $etablissement['eta_desl_ar'] : $etablissement['eta_desl'];
                    echo '<option value="' . $etablissement['eta_code'] . '">'.$eta_desl.'</option>';
                }
                //$this->getEnseignantMatiereCtcs();
        }
        public function get_Cycles() {       
                $row_id = 0;
                $sqlQuery = "SELECT DISTINCT gpw_ens_mats.cycle FROM gpw_ens_mats";
                $sqlQuery .= " WHERE gpw_ens_mats.ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND eta_code = '".$_SESSION['eta_code']."'";
                }               
                $sqlQuery .= " ORDER BY gpw_ens_mats.cycle ASC";
          
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $row_id = 0;
                $cycles_arr = array();
                while( $row = mysqli_fetch_array($result) ){
                    $row_id++;
                    if ($row_id == 1){$_SESSION['cycle'] = $row['cycle'];}
                    if($_SESSION['langue'] == 'AR'){
                        $cycle_desl = ($row['cycle'] == '2') ? 'ماستر' : 'ليسانس';
                    }else {
                        $cycle_desl = ($row['cycle'] == '2') ? 'Master' : 'Licence';
                    }
                    $cycles_arr[] = array("cycle" => $row['cycle'], "cycle_desl" => $cycle_desl);
                 }
               
                echo json_encode($cycles_arr);
        }
        
        
        public function get_Parcours() {       
                $row_id = 0;
                $sqlQuery = "SELECT DISTINCT gpw_ens_mats.par_code, par_desl, par_desl_ar FROM gpw_ens_mats";
                $sqlQuery .= " INNER JOIN gpw_pars ON gpw_pars.par_code = gpw_ens_mats.par_code AND gpw_pars.cycle = gpw_ens_mats.cycle AND gpw_pars.ann_univ = gpw_ens_mats.ann_univ"; 
                $sqlQuery .= " AND gpw_ens_mats.ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.cycle = '".$_SESSION['cycle']."'";
                }
                $sqlQuery .= " ORDER BY gpw_ens_mats.par_code ASC";
                
                $parcours_arr = array();
                $par_code = "0";
                $par_desl = ($_SESSION['langue'] == 'AR') ? '...' : '...';
                $parcours_arr[] = array("par_code" => $par_code, "par_desl" => $par_desl);
                $parcours_list = "";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $found = strpos($parcours_list, $row['par_code']);
                    if($found === false){
                        $row_id++;
                        if ($row_id == 1){$_SESSION['par_code'] = $row['par_code'];}
                        $par_desl = ($_SESSION['langue'] == 'AR') ? $row['par_desl_ar'] : $row['par_desl'];
                        $par_desl = $row['par_code'].'-'.$par_desl;   
                        $parcours_arr[] = array("par_code" => $row['par_code'], "par_desl" => $par_desl);
                    }
                 }
               
                echo json_encode($parcours_arr);
        }
   
         
        //Années
        public function get_Annees(){    
                $sqlQuery = "SELECT DISTINCT ann_code FROM gpw_ens_mats ";
                $sqlQuery .= " WHERE ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.cycle = '".$_SESSION['cycle']."'";
                }
                if($_SESSION['par_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.par_code = '".$_SESSION['par_code']."'";
                }
                
                $sqlQuery .= " ORDER BY gpw_ens_mats.ann_code ASC";                
                
                $annees_arr = array();
                $ann_code = "0";
                $ann_desl = '...';
                $annees_arr[] = array("ann_code" => $ann_code, "ann_desl" => $ann_desl);
                $_SESSION['ann_code'] = "0";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $ann_desl = ($_SESSION['langue'] == 'AR') ? $row['ann_code'] : $row['ann_code'];
                    $annees_arr[] = array("ann_code" => $row['ann_code'], "ann_desl" => $ann_desl);
                 }
               
                echo json_encode($annees_arr);        
                
        }
        
        //Semestres
        public function get_Semestres(){    
                $sqlQuery = "SELECT DISTINCT sem_code FROM gpw_ens_mats ";
                $sqlQuery .= " WHERE ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.cycle = '".$_SESSION['cycle']."'";
                }
                if($_SESSION['par_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.par_code = '".$_SESSION['par_code']."'";
                }
                if($_SESSION['ann_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.ann_code = '".$_SESSION['ann_code']."'";
                }
                $sqlQuery .= " ORDER BY gpw_ens_mats.sem_code ASC";                
                
                $semestres_arr = array();
                $sem_code = "0";
                $sem_desl = '...';
                $semestres_arr[] = array("sem_code" => $sem_code, "sem_desl" => $sem_desl);
                $_SESSION['sem_code'] = "0";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $sem_desl = ($_SESSION['langue'] == 'AR') ? $row['sem_code'] : $row['sem_code'];
                    $semestres_arr[] = array("sem_code" => $row['sem_code'], "sem_desl" => $sem_desl);
                 }
               
                echo json_encode($semestres_arr);        
                
        }
        
        //Matières
        public function get_Matieres(){    
                $sqlQuery = "SELECT DISTINCT ann_code, sem_code, cycle, mat_code, mat_desl, mat_desl_ar FROM gpw_ens_mats ";
                //$sqlQuery .= "INNER JOIN gpw_mats ON gpw_mats.mat_code=gpw_ens_mats.mat_code AND gpw_mats.cycle=gpw_ens_mats.cycle AND gpw_mats.ann_univ=gpw_ens_mats.ann_univ";
                $sqlQuery .= " WHERE gpw_ens_mats.ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.cycle = '".$_SESSION['cycle']."'";
                }
                if($_SESSION['par_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.par_code = '".$_SESSION['par_code']."'";
                }
                if($_SESSION['ann_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.ann_code = '".$_SESSION['ann_code']."'";
                }
                if($_SESSION['sem_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_mats.sem_code = '".$_SESSION['sem_code']."'";
                }
                $sqlQuery .= " ORDER BY gpw_ens_mats.mat_code ASC";                
                
                $matieres_arr = array();
                $mat_code = "0";
                $mat_desl = '...';
                $matieres_arr[] = array("mat_code" => $mat_code, "mat_desl" => $mat_desl);
                $_SESSION['mat_code'] = "0";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    if($_SESSION['langue'] == 'AR'){
                        $cycle = ($row['cycle'] == '2') ? 'م' : 'ل';
                        $cycle .= $row['ann_code'];
                        $cycle .= 'س'.$row['sem_code']."- ";
                        $mat_desl = $cycle.$row['mat_desl_ar'];
                    }else{
                        $cycle = ($row['cycle'] == '2') ? 'M' : 'L';
                        $cycle .= $row['ann_code'];
                        $cycle .= 'S'.$row['sem_code']."- ";
                        $mat_desl = $cycle.$row['mat_desl'];
                    }

                    //$mat_desl = ($_SESSION['langue'] == 'AR') ? $row['mat_desl_ar'] : $row['mat_desl'];
                    $matieres_arr[] = array("mat_code" => $row['mat_code'], "mat_desl" => $mat_desl);
                 }
               
                echo json_encode($matieres_arr);        
                
        }
        
        //Examens
        public function get_Examens(){    
                $sqlQuery = "SELECT DISTINCT ctc_code FROM gpw_ens_sec_grps ";
                //$sqlQuery .= "INNER JOIN gpw_mats ON gpw_mats.mat_code=gpw_ens_sec_grps.mat_code AND gpw_mats.cycle=gpw_ens_sec_grps.cycle AND gpw_mats.ann_univ=gpw_ens_sec_grps.ann_univ";
                $sqlQuery .= " WHERE gpw_ens_sec_grps.ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.cycle = '".$_SESSION['cycle']."'";
                }
                if($_SESSION['par_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.par_code = '".$_SESSION['par_code']."'";
                }
                if($_SESSION['ann_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.ann_code = '".$_SESSION['ann_code']."'";
                }
                if($_SESSION['sem_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.sem_code = '".$_SESSION['sem_code']."'";
                }
                if($_SESSION['mat_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.mat_code = '".$_SESSION['mat_code']."'";
                }
                $sqlQuery .= " ORDER BY gpw_ens_sec_grps.ctc_code ASC";                
                
                $examens_arr = array();
                $ctc_code = "0";
                $ctc_desl = '...';
                $examens_arr[] = array("ctc_code" => $ctc_code, "ctc_desl" => $ctc_desl);
                $_SESSION['ctc_code'] = "0";
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $ctc_code = $row['ctc_code'];
                    if ($ctc_code == 'CC1'){$ctc_desl = ($_SESSION['langue'] == 'AR') ? 'أعمال موجهة' : 'TD';}
                    if ($ctc_code == 'EXA'){$ctc_desl = ($_SESSION['langue'] == 'AR') ? 'إمتحان' : 'Examen';}
                    if ($ctc_code == 'RAT'){$ctc_desl = ($_SESSION['langue'] == 'AR') ? 'إستدراك ': 'Rattrapage';}
                    $examens_arr[] = array("ctc_code" => $ctc_code, "ctc_desl" => $ctc_desl);

                  }
               
                echo json_encode($examens_arr);        
        }
        
        //Enseignants
        public function get_Enseignants(){    
                $sqlQuery = "SELECT DISTINCT matricule_ens, nom, prenom, nom_ar, prenom_ar FROM gpw_ens_sec_grps ";
                $sqlQuery .= "INNER JOIN gpw_users ON gpw_ens_sec_grps.matricule_ens=gpw_users.username";
                $sqlQuery .= " WHERE gpw_ens_sec_grps.ann_univ = '".$_SESSION['ann_univ']."'";
                if($_SESSION['eta_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.eta_code = '".$_SESSION['eta_code']."'";
                }
                if($_SESSION['cycle'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.cycle = '".$_SESSION['cycle']."'";
                }
                if($_SESSION['par_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.par_code = '".$_SESSION['par_code']."'";
                }
                if($_SESSION['ann_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.ann_code = '".$_SESSION['ann_code']."'";
                }
                if($_SESSION['sem_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.sem_code = '".$_SESSION['sem_code']."'";
                }
                if($_SESSION['mat_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.mat_code = '".$_SESSION['mat_code']."'";
                }
                if($_SESSION['ctc_code'] != '0'){
                    $sqlQuery .= " AND gpw_ens_sec_grps.ctc_code = '".$_SESSION['ctc_code']."'";
                }
                if($_SESSION['langue'] == 'AR'){
                    $sqlQuery .= " ORDER BY nom_ar ASC, prenom_ar ASC";
                }else{
                    $sqlQuery .= " ORDER BY nom ASC, prenom ASC";
                }
                
                $enseignants_arr = array();
                $matricule_ens = "0";
                $nom_prenom = '...';
                $enseignants_arr[] = array("matricule_ens" => $matricule_ens, "nom_prenom" => $nom_prenom);
                $_SESSION['matricule_ens'] = "0";
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $nom_prenom = ($_SESSION['langue'] == 'AR') ? $row['nom_ar']." ".$row['prenom_ar'] : $row['nom']." ".$row['prenom'];
                    $enseignants_arr[] = array("matricule_ens" => $row['matricule_ens'], "nom_prenom" => $nom_prenom);
                  }
               
                echo json_encode($enseignants_arr);        
        }
        
        
  }