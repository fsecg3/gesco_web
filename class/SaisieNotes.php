<?php
//require('fpdf.php');

require('reps/tcpdf.php');
//$pdf = new PDF;
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

class SaisieNotes extends Database {  
    private $recoursTable = 'gpw_ctc_nots';
        private $recoursRepliesTable = 'gpw_recs_reps';
	private $departementsTable = 'gpw_etas';
        static $mat_code = "";
        static $ctc_code = "";
        static $sec_code = "0";
        static $grp_code = "0";
        public $selected_mat_code = "";
       
        private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	public function listSaisieNotes(){
                $mat_code = $_SESSION["mat_code"];
                $ctc_code = $_SESSION["ctc_code"];
                $sec_code = $_SESSION["sec_code"];
                $grp_code = $_SESSION["grp_code"];

                //echo '<script>alert("GPW")</script>';
                $sqlWhere = "WHERE matricule_ens = '".$_SESSION["matricule"]."'";
                $sqlWhere .= " AND mat_code = '".$mat_code."'";
                $sqlWhere .= " AND ctc_code = '".$ctc_code."'";
                //$sqlWhere .= " OR ctc_code = '"."***"."')";
                if (isset($sec_code)  && $sec_code > '0'){
                   $sqlWhere .= " AND sec_code = ".$sec_code.""; 
                }   
                if (isset($grp_code) && $grp_code > '0'){
                   $sqlWhere .= " AND grp_code = ".$grp_code.""; 
                }
                
                $sqlWhere .= " AND selected = '"."1"."'";
                $sqlWhere .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                
		//$time = new time;  			 
                
		//$sqlQuery = "SELECT e.matricule, e.nom, e.prenom, e.nom_ar, e.prenom_ar, e.date_nais, e.ins_type, e.sec_code, e.grp_code ";
		//$sqlQuery .= " FROM rdn_etus e 
                //    LEFT JOIN gpw_ens_sec_grps g ON e.eta_code=g.eta_code AND e.par_code=g.par_code AND e.ann_code=g.ann_code AND e.sec_code=g.sec_code AND e.grp_code=g.grp_code
                //     $sqlWhere";
               
        //            LEFT JOIN gpw_users r ON g.matricule = r.matricule $sqlWhere ";
		$sqlQuery = "SELECT * FROM gpw_ctc_nots ".$sqlWhere." ORDER BY nom, prenom";
		
                
		$li_row = 0;
		$cycle = '1';	
                $status = '1';
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$output = array();
		while( $recours = mysqli_fetch_assoc($result) ) {
                                            $mat_acquise = false;
                                            if($ctc_code == '_RAT'){
                                                $mat_acquise = $this->IsMatiereAcquise($recours['matricule'], $mat_code, $ctc_code, $_SESSION['ann_univ'], '2');
                                            }
                                            if(!$mat_acquise){
                                                $li_row = $li_row +1;
                                                $etat = '';
                                                if($recours['etat'] == '0'){$etat = ' ';}
                                                if($recours['etat'] == '1'){$etat = "غياب مبرر";}
                                                if($recours['etat'] == '2'){$etat ="غياب غير مبرر";}
                                                if($recours['etat'] == '3'){$etat = "إقصاء";}

                                                $output[] = array(
                                                "pos"=>$li_row,
                                                "matricule"=>$recours['matricule'], 
                                                "nom"=>$recours['nom'], 
                                                "prenom"=>$recours['prenom'], 
                                                "date_nais"=>$recours['date_nais'], 
                                                "situation"=>$recours['ins_desl'], 
                                                "section"=>$recours['sec_code'], 
                                                 "groupe"=>$recours['grp_code'], 
                                                "note"=>$recours['note'], 
                                                "etat"=>$etat,
                                                "obs"=>$recours['obs'],
                                                "id"=>$recours['id']
                                                );
                                            }
                }
/*		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=>  $numRows,
			"data"    			=> 	$saisienotesData
		);*/
		echo json_encode($output);
	}	
        
                public function IsMatiereAcquise($matricule, $mat_code, $ctc_code, $ann_univ, $ses_code){
                        $mat_acquise = false;
                        $sqlQuery = "SELECT note FROM rdn_fsic_master.scl_mat_nots";
                        $sqlQuery .= " WHERE matricule = '".$matricule."'";
                        $sqlQuery .= " AND mat_code = '".$mat_code."'";
                        $sqlQuery .= " AND ann_univ = '".$ann_univ."'";
                        $sqlQuery .= " AND ses_code < '".$ses_code."'";
                        $sqlQuery .= " AND note >= 10";
                
                        $result = mysqli_query($this->dbConnect, $sqlQuery);
                        $row = mysqli_fetch_assoc($result);
                    if ($row){
                        $mat_acquise = true;
                    } 
                    return $mat_acuise;
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
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
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
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
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
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
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
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
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
                  if($_SESSION['langue'] == 'AR'){
                        $action1 = "موافق";
                        $action2 = "طباعة";
                        $action3 = "رصد العلامات في ملف Excel";
                        $action4 = "تحميل العلامات من ملف Excel";
                  }else{
                        $action1 = "Valider";
                        $action2 = "Imprimer";
                        $action3 = "Exporter vers Excel";
                        $action4 = "Importer depuis Excel";
                      
                  }
                        echo '<button type="button" name="add" id='.'$GetNotesSaisie'.' class="btn btn-success btn-sm GetNotesSaisie">'.$action1.'</button>';
                        //echo '&nbsp'; //&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '<button type="button" name="add" id='.'PrintEnseignantPVM'.' class="btn btn-info btn-sm PrintEnseignantPVM">'.$action2.'</button>';
                        //echo '&nbsp;&nbsp;&nbsp;';
                        //echo '<button type="button" name="add" id='.'ExportNotesExcel'.' class="btn btn-info btn-sm ExportNotesExcel">'.$action3.'</button>';
                        //echo '&nbsp;&nbsp;&nbsp;';
                        //echo '<button type="button" name="add" id='.'$ImportNotesExcel'.' class="btn btn-danger btn-sm ImportNotesExcel">'.$action4.'</button>';
			     
	}        
	
        public function ExportNotesExcel_v0() {
            $sql_data="select * from gpw_users limit 100";
            //$result_data=$this->dbConnect->query($sql_data);
            $result_data = mysqli_query($this->dbConnect, $sql_data);
		
            $results=array();
            $filename = "Webinfopen.xls"; // File Name
            // Download file
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");

            $flag = false;
            while ($row = mysqli_fetch_assoc($result_data)) {
            if (!$flag) {
                // display field/column names as first row
                echo implode("\t", array_keys($row)) . "\r\n";
                $flag = true;
                }
                echo implode("\t", array_values($row)) . "\r\n";
            }
            header("Pragma: ");  
            header("Cache-Control: ");  
            $_REQUEST['datatodisplay']; 
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
        
        public function SaveNote($id, $fieldname, $oldValue, $newValue) {
                
                $updateQuery = "UPDATE gpw_ctc_nots";
                if(isset($fieldname) ){
                    if ($fieldname == 'note'){
                        if($newValue == ""){
                            $updateQuery .= " SET note = NULL".", modified='"."1'".", datem=now()";
                        }else{
                            $updateQuery .= " SET note = ".$newValue.", modified='"."1'".", datem=now()";
                        }
                    } else{
                        $updateQuery .= " SET ".$fieldname." = '".$newValue."', modified='"."1'".", datem=now()";
                    }
                
                    $updateQuery .=	" WHERE id = ".$id;
                    
		mysqli_query($this->dbConnect, $updateQuery);
	}
        }
    
        
        public function get_SitePars($fld_name, $par_code, $ann_code, $sem_code, $cycle, $ses_code) {       
                $response = '0';
                $sqlQuery = "SELECT ".$fld_name." FROM gpw_site_pars";
                $sqlQuery .= " WHERE par_code = '".$par_code."' AND ann_code = '".$ann_code."' AND sem_code = '".$sem_code."' AND cycle = '".$cycle."' AND ses_code = '".$ses_code."'";
                $sqlQuery .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $row = mysqli_fetch_assoc($result);
                if ($row){
                    $response = $row[$fld_name] ;
                }
                return $response;
        }
        
        public function get_EnseignantEtablissement() {       
                $etablissement = "";
                $sqlQuery = "SELECT  DISTINCT gpw_ens_mats.eta_code, eta_desl, eta_desl_ar, par_code, ann_code, sem_code FROM gpw_ens_mats";
                $sqlQuery .= " INNER JOIN gpw_etas ON gpw_etas.eta_code = gpw_ens_mats.eta_code"; 
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND gpw_ens_mats.mat_code = '".$_SESSION['mat_code']."'";
                $sqlQuery .= " AND gpw_ens_mats.ann_univ = '".$_SESSION['ann_univ']."'";
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                $row = mysqli_fetch_assoc($result);
                if ($row){
                    $hide_saisie_ses1 = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], '1');
                    $hide_saisie_ses2 = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], '2');
                    if($hide_saisie_ses1 == '0' || $hide_saisie_ses2 == '0'){
                        $etablissement = ($_SESSION['langue'] == 'AR') ? $row['eta_desl_ar'] : $row['eta_desl'];
                    }
                }
                return $etablissement;
        }
        
        public function get_EnseignantParcours() {       
                $row_id = 0;
                $parcours_list = "0";
                
                $sqlQuery = "SELECT DISTINCT gpw_ens_mats.cycle, gpw_ens_mats.par_code, par_desl, par_desl_ar,gpw_ens_mats.ann_code, gpw_ens_mats.sem_code  FROM gpw_ens_mats";
                $sqlQuery .= " INNER JOIN gpw_pars ON gpw_pars.par_code = gpw_ens_mats.par_code AND gpw_pars.cycle = gpw_ens_mats.cycle AND gpw_pars.ann_univ = gpw_ens_mats.ann_univ"; 
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND gpw_ens_mats.ann_univ = '".$_SESSION['ann_univ']."'";
                $sqlQuery .= " ORDER BY gpw_ens_mats.cycle ASC, gpw_ens_mats.par_code ASC";
                
                $par_code = "0";
                $par_desl = ($_SESSION['langue'] == 'AR') ? "اختر المسلك أو التخصص" : "Sélection parours/spécialité";
                echo '<option value="' . $par_code. '">'.$par_desl .'</option>';
                
	$result = mysqli_query($this->dbConnect, $sqlQuery);
                 while($row = mysqli_fetch_assoc($result) ) {  
                    $found = strpos($parcours_list, $row['par_code']); //.$row['ann_code'].$row['sem_code']);
                    if($found === false){
                        //$parcours_list .= '<'.$row['par_code'].'>';
                        //if($_SESSION['DB'] == 'ENST_MASTER_CP'){
                                $row['cycle'] = '2';
                        //}
                        $hide_saisie_ses1 = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], $row['cycle'], '1');
                        $hide_saisie_ses2 = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], $row['cycle'], '2');
                        if($hide_saisie_ses1 == '0' || $hide_saisie_ses2 == '0'){
                            $parcours_list .= '<'.$row['par_code'].'>';
                            $row_id++;
                            if ($row_id == 1){$_SESSION['par_code'] = $row['par_code'];}
                            $par_desl = ($_SESSION['langue'] == 'AR') ? $row['par_desl_ar'] : $row['par_desl']; 
                            echo '<option value="' . $row['par_code'] . '">'.$row['par_code'].'-'.$par_desl .'</option>';
                        }
                    }
                }
                //$this->getEnseignantMatiereCtcs();
        }
        
        //Matières
        public function get_EnseignantMatieres(){    
                $sqlQuery = "SELECT cycle, par_code, ann_code, sem_code, mat_code, mat_desl, mat_desl_ar FROM gpw_ens_mats ";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND par_code = '".$_SESSION['par_code']."'";
                $sqlQuery .= " ORDER BY mat_code ASC";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);

                $row_id = 0;
                $cycle = "";
                $matieres_arr = array();
                while( $row = mysqli_fetch_array($result) ){
                    $hide_saisie_ses1 = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], $row['cycle'], '1');
                    $hide_saisie_ses2 = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], $row['cycle'], '2');
                    if($hide_saisie_ses1 == '0' || $hide_saisie_ses2 == '0'){
                        $row_id++;
                        if ($row_id == 1){$_SESSION['mat_code'] = $row['mat_code'];}
                        $mat_code = $row['mat_code'];
                        if($_SESSION['langue'] == 'AR'){
                            $cycle = ($row['cycle'] == '2') ? 'م' : 'ل';
                            $cycle .= $row['ann_code'];
                            $cycle .= 'س'.$row['sem_code']."- ";
                        }    
                        $mat_desl = ($_SESSION['langue'] == 'AR') ? $cycle.$row['mat_desl_ar'] : $cycle.$row['mat_desl'];
                        $matieres_arr[] = array("mat_code" => $mat_code, "mat_desl" => $mat_desl);
                    }
                }
                // encoding array to json format
                echo json_encode($matieres_arr);
        }
        //Contrôles continus
        public function get_EnseignantMatiereCtcs($mat_code){    
                $sqlQuery = "SELECT DISTINCT par_code, ann_code, sem_code, ctc_code, ctc_code as ctc_desl, cycle, ses_code FROM gpw_ctc_nots ";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND mat_code = '".$_SESSION['mat_code']."'";
                $sqlQuery .= " ORDER BY ctc_code ASC";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);

                $row_id = 0;
                $examens_arr = array();
                while( $row = mysqli_fetch_array($result) ){
                    $ses_code = ($row['ctc_code'] == 'RAT')? '2':'1';
                    $hide_saisie = $this->get_SitePars('hide_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], $row['cycle'], $ses_code);
                    if($hide_saisie == '0'){
                        $row_id++;
                        if ($row_id == 1){$_SESSION['ctc_code'] = $row['ctc_code'];}
                        $ctc_code = $row['ctc_code'];
                        //if($_SESSION['langue'] == 'AR'){
                        if ($_SESSION['langue'] == 'AR' &&$ctc_code == 'CC1'){$ctc_desl = "أعمال موجهة";}
                        if ($_SESSION['langue'] == 'AR' &&$ctc_code == 'CC2'){$ctc_desl = "أعمال تطبيقية";}
                        if ($_SESSION['langue'] == 'AR' && $ctc_code == 'EXA'){$ctc_desl = "إمتحان";}
                        if ($_SESSION['langue'] == 'AR' && $ctc_code == 'RAT'){$ctc_desl = "إستدراك";}
                    
                        if ($_SESSION['langue'] == 'FR' && $ctc_code == 'CC1'){$ctc_desl = "Note CC";}
                        if ($_SESSION['langue'] == 'FR' && $ctc_code == 'CC2'){$ctc_desl = "Note TP";}
                        
                        if ($_SESSION['langue'] == 'FR' && $ctc_code == 'EXA'){$ctc_desl = "Examen";}
                        if ($_SESSION['langue'] == 'FR' && $ctc_code == 'RAT'){$ctc_desl = "Rattrapage";}
                        
                        $examens_arr[] = array("ctc_code" => $ctc_code, "ctc_desl" => $ctc_desl);
                    }
                 }
               
                // encoding array to json format
                 if($row_id == 0){$examens_arr[] = array("ctc_code" => "---", "ctc_desl" => "---");}
                 
                echo json_encode($examens_arr);
        }

        //Sections
        public function get_EnseignantMatiereCtcSections(){    
                $sqlQuery = "SELECT DISTINCT sec_code FROM gpw_ens_sec_grps ";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND mat_code = '".$_SESSION['mat_code']."'";
                $sqlQuery .= " AND ctc_code = '".$_SESSION['ctc_code']."'";
                $sqlQuery .= " ORDER BY sec_code ASC";
                
                $sections_arr = array();
                $sec_code = "0";
                $sec_desl = ($_SESSION['langue'] == 'AR') ? "كل المجموعات" : "Toutes";
                 $sections_arr[] = array("sec_code" =>$sec_code, "sec_desl" =>$sec_desl);
                 $_SESSION['sec_code'] = $sec_code;
                
                 $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $sec_code = $row['sec_code'];
                    $sec_desl = ($_SESSION['langue'] == 'AR') ? "المجموعة "." ".$sec_code : "Section ".$sec_code;
                    $sections_arr[] = array("sec_code" => $sec_code, "sec_desl" => $sec_desl);
                 }
               
                // encoding array to json format
                echo json_encode($sections_arr);
        }        
        
        //Groupes
        public function get_EnseignantMatiereCtcGroupes(){    
                $sqlQuery = "SELECT DISTINCT grp_code FROM gpw_ens_sec_grps ";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND mat_code = '".$_SESSION['mat_code']."'";
                $sqlQuery .= " AND ctc_code = '".$_SESSION['ctc_code']."'";
                if(isset($_SESSION['sec_code']) && $_SESSION['sec_code'] > '0') {
                    $sqlQuery .= " AND sec_code = ".$_SESSION['sec_code'];
                }
                $sqlQuery .= " ORDER BY grp_code ASC";
                
                $groupes_arr = array();
                $grp_code = "0";
                $grp_desl = ($_SESSION['langue'] == 'AR') ? "كل الأفواج" : "Tous";
                 $groupes_arr[] = array("grp_code" =>$grp_code, "grp_desl" =>$grp_desl);
                 $_SESSION['grp_code'] = $grp_code;
                
                 $result = mysqli_query($this->dbConnect, $sqlQuery);
                while( $row = mysqli_fetch_array($result) ){
                    $grp_code = $row['grp_code'];
                    $grp_desl = ($_SESSION['langue'] == 'AR') ? "الفوج "." ".$grp_code : "Groupe ".$grp_code;
                    $groupes_arr[] = array("grp_code" => $grp_code, "grp_desl" => $grp_desl);
                 }
               
                // encoding array to json format
                echo json_encode($groupes_arr);
     }  
          
   
       public function get_StopSaisie(){    
                $stop_saisie = '1';
                $sqlQuery = "SELECT par_code, ann_code, sem_code, ctc_code, cycle FROM gpw_ctc_nots";
                $sqlQuery .= " WHERE matricule_ens = '".$_SESSION['matricule']."'";
                $sqlQuery .= " AND mat_code = '".$_SESSION['mat_code']."'";
                $sqlQuery .= " AND ctc_code = '".$_SESSION['ctc_code']."'";
                //$sqlQuery .= " LIMIT 1";
                
                $result = mysqli_query($this->dbConnect, $sqlQuery);
                if( $row = mysqli_fetch_array($result) ){
                    $ses_code = ($row['ctc_code'] == 'RAT')? '2':'1';
                    $stop_saisie = $this->get_SitePars('stop_saisie', $row['par_code'], $row['ann_code'], $row['sem_code'], $row['cycle'], $ses_code);
                 }
               
                echo $stop_saisie;
                
        }        
    
        
 public function PrintEnseignantPVM_v0($parcours, $matiere, $examen, $section, $groupe){
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Hello World !');
ob_end_clean();
ob_start();

    $mpdf->Output('e:\xampp\htdocs\gesco_web\pvm.pdf', \Mpdf\Output\Destination::FILE);
    //$pdf->Output();
    //$pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'pvm.pdf','D'); // 'I'); 
    //$pdf->OutputHttpDownload('download.pdf');
    
 }
    public function PrintEnseignantPVM($parcours, $matiere, $examen, $section, $groupe){
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        ////$pdf = new TCPDF("P", "mm", "A4", true, "UTF-8", false);
        //$pdf = new MYPDF("P", "mm", "A5", true, "UTF-8", false);

    //Before Write
    if ($_SESSION['langue'] == 'AR'){
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        $pdf->setLanguageArray($lg);
        $pdf->setRTL(true);        
    }
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false);
    
                $mat_code = $_SESSION["mat_code"];
                $ctc_code = $_SESSION["ctc_code"];
                $sec_code = $_SESSION["sec_code"];
                $grp_code = $_SESSION["grp_code"];

                $sqlWhere = "WHERE matricule_ens = '".$_SESSION["matricule"]."'";
                $sqlWhere .= " AND mat_code = '".$mat_code."'";
                $sqlWhere .= " AND ctc_code = '".$ctc_code."'";
                //$sqlWhere .= " OR ctc_code = '"."***"."')";
                if (isset($sec_code)  && $sec_code > '0'){
                   $sqlWhere .= " AND sec_code = ".$sec_code.""; 
                }   
                if (isset($grp_code) && $grp_code > '0'){
                   $sqlWhere .= " AND grp_code = ".$grp_code.""; 
                }
                
                $sqlWhere .= " AND selected = '"."1"."'";
                $sqlWhere .= " AND ann_univ = '".$_SESSION['ann_univ']."'";
        
                $sqlQuery = "SELECT * from gpw_ctc_nots ";
                $sqlQuery .= $sqlWhere;
                $sqlQuery .= " ORDER BY nom, prenom";
        $result = mysqli_query($this->dbConnect, $sqlQuery);

  /*      $PDF_HEADER_LOGO =""; // "logo.png";//any image file. check correct path.
        $PDF_HEADER_LOGO_WIDTH = "20";
        $PDF_HEADER_TITLE = "This is my Title";
        $PDF_HEADER_STRING = "Tel 1234567896 Fax 987654321\n"
.       "E abc@gmail.com\n"
.       "www.abc.com";
        $pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);
   
   */
        
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);        
         $pdf->SetPrintHeader(false);
        //$pdf->SetHeaderMargin(10);
  
        $pdf->SetMargins(10, 5, 10);
    
        //$pdf->AddPage();    
        //$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        /*
        $pdf->SetFont('FreeSerif', 'B', 11);    
        $pdf->SetFillColor(255, 255, 255);
    
        $pdf->Cell(10, $row_height, 'رقم', 1, 0, 'C', 1);
        $pdf->Cell(28, $row_height, 'رقم التسجيل', 1, 0, 'C', 1);
        $pdf->Cell(40, $row_height, 'اللقب', 1, 0, 'C', 1);
        $pdf->Cell(40, $row_height, 'الإسم', 1, 0, 'C', 1);
        $pdf->Cell(15, $row_height, 'الوضعية', 1, 0, 'C', 1);
        $pdf->Cell(15, $row_height, 'العلامة', 1, 1, 'C', 1);
    */
        $etablissement = '$this->get_EnseignantEtablissement()';
        $ann_univ = '2023/2022';
        $annee = mb_substr($matiere, 1, 1);
        $semestre = mb_substr($matiere, 3, 1);
        $matiere = mb_substr($matiere, 6, mb_strlen($matiere)-6);
        
        $i = 0;
        $max = 30;
        $row_height = 7;
        $backup_group = "";
        $li_row = 0;
        $li_line = 0;

        while($row = mysqli_fetch_array($result))
        {
            $li_row++;
            $li_line++;
            if ($li_line > 30){$li_line = 1;}
            
            if($li_line == 1){
                if($li_row >1){
                    //$pdf->SetY(-25);
                        $pdf->Cell(50, 12, 'إمضاء الأستاذ..............................................', 0, 1, 'R', false); 
                        $page = $pdf->getAliasNumPage();
                        $footer = $page .' '. 'الصفحة  ';
                        $pdf->SetY(-15);
                        $pdf->Cell(190, 15,$footer, 0, 1, 'C', false); 
                        }
                
                $pdf->AddPage();
                 $pdf->SetFont('aealarabiya', 16);
                 $pdf->Cell(190, 7, $_SESSION['uni_desl_ar'], 0, 1, 'C', false);
                 $pdf->SetFont('aealarabiya', 12);
                 $pdf->Cell(70, 6, $_SESSION['fac_desl_ar'], 0, 1, 'R', false);
                 $pdf->Cell(70, 6, $etablissement, 0, 1, 'R', false);
                //$pdf->Cell(190, 8, 'جامعة الجيلالي بونعامة - خميس مليانة', 0, 1, 'C', false);
                //$pdf->Cell(50, 10, 'كلية العلوم الإجتماعية والإنسانية', 0, 1, 'R', false);
                $pdf->SetFont('FreeSerif', 'B', 18);
                $pdf->Cell(190, 10, 'محضر النقاط'.' - '.$examen, 0, 1, 'C', false);

                $pdf->SetFont('FreeSerif', 'B', 11);    
                $pdf->SetFillColor(255, 255, 255);
                
                $pdf->Cell(18, $row_height-2, 'المسلك:', 0, 0, 'R', 1);
                $pdf->Cell(95, $row_height-2, $parcours, 0, 0, 'R', 1);
                $pdf->Cell(25, $row_height-2, 'السنة الجامعية:', 0, 0, 'L', 1);
                $pdf->Cell(74, $row_height-2, $ann_univ, 0, 1, 'R', 1);
                
                $pdf->Cell(18, $row_height-2, 'السنة:', 0, 0, 'R', 1);
                $pdf->Cell(95, $row_height-2, $annee, 0, 0, 'R', 1);
                $pdf->Cell(25, $row_height-2, 'الدورة:', 0, 0, 'L', 1);
                $pdf->Cell(74, $row_height-2, 'العادية', 0, 1, 'R', 1);
             
                $pdf->Cell(18, $row_height-2, 'المجموعة:', 0, 0, 'R', 1);
                $pdf->Cell(95, $row_height-2, $section, 0, 0, 'R', 1);
                $pdf->Cell(25, $row_height-2, 'المادة:', 0, 0, 'L', 1);
                $pdf->Cell(74, $row_height-2, $matiere, 0, 1, 'R', 1);
                
                $pdf->Cell(18, $row_height-2, 'الفوج:', 0, 0, 'R', 1);
                $pdf->Cell(95, $row_height-2, $groupe, 0, 0, 'R', 1);
                $pdf->Cell(25, $row_height-2, 'الأستاذ:', 0, 0, 'L', 1);
                $pdf->Cell(74, $row_height-2, $_SESSION['nom_prenom'], 0, 1, 'R', 1);
                
                $pdf->Cell(74, $row_height-2, '', 0, 1, 'R', 1); //ligne vide
                
                $pdf->SetFont('FreeSerif', 11);
                $pdf->MultiCell(10, $row_height, 'رقم', 1, 0, 'C', 0);
                $pdf->Cell(32, $row_height, 'رقم التسجيل', 1, 0, 'C', 0);
                $pdf->Cell(40, $row_height, 'اللقب', 1, 0, 'C', 0);
                $pdf->Cell(40, $row_height, 'الإسم', 1, 0, 'C', 0);
                $pdf->Cell(15, $row_height, 'الوضعية', 1, 0, 'C', 0);
                $pdf->Cell(15, $row_height, 'العلامة', 1, 0, 'C', );
                $pdf->Cell(38, $row_height, 'العلامة', 1, 1, 'C', 1);
                
            }
            $matricule = $row['matricule'];
            $nom = $row['nom'];
            $prenom = $row['prenom'];
            $ins_desl = $row['ins_desl'];
            $note = $row['note'];

            $pdf->Cell(10, $row_height, $li_row, 1, 0, 'C', 1);
            $pdf->Cell(32, $row_height, $matricule, 1, 0, 'C', 1);
            $pdf->Cell(40, $row_height, $nom, 1, 0, 'R', 1);
            $pdf->Cell(40, $row_height, $prenom, 1, 0, 'R', 1);
            $pdf->Cell(15, $row_height, $ins_desl, 1, 0, 'C', 1);
            $pdf->Cell(15, $row_height, $note, 1, 0, 'C', 1);
            $pdf->Cell(38, $row_height, '', 1, 1, 'C', 1);
            //$backup_group = $group;
            $i++;
        }
        if($li_row >1){
                        $pdf->Cell(50, 12, 'إمضاء الأستاذ..............................................', 0, 1, 'R', false); 
                        $page = $pdf->getAliasNumPage();
                        $footer = $page .' '. 'الصفحة  ';
                        $pdf->SetY(-15);
                        $pdf->Cell(190, 15,$footer, 0, 1, 'C', false); 
         }
        //mysql_close($con);
    
        //ob_end_clean();
    
        //$pdf->Output('mypdf.pdf', 'I');

ob_end_clean();
ob_start();

//$pdf->Output('pvm.pdf', 'D');
//$pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'pvm.pdf','D'); // 'I'); 
$pdf->Output('e:\xampp\htdocs\gesco_web\pvm.pdf', 'F');
//return true;
  }
  }