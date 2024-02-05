<?php
class Users extends Database { 
	private $userTable = 'gpw_users';
	private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    }	
	public function isLoggedIn () {
		if(isset($_SESSION["userid"])) {
			return true; 			
		} else {
			return false;
		}
	}
	public function login(){		
		$errorMessage = '';
                                    $ok = false;
                if(!empty($_POST["login"]) && $_POST["username"]!=''&& $_POST["password"]!='') {	
                                $username = htmlspecialchars($_POST['username']);
                                //$input = urlencode($_POST['username']);
                                $password = htmlspecialchars($_POST['password']);
                                $sqlQuery = "SELECT id, username, matricule, concat(nom_ar, ' ', prenom_ar) as name, sexe, email, password, user_type, eta_code, cycle, status, datec FROM ".$this->userTable." 
                                    WHERE username='".$username."' AND password=MD5('".$password."') AND status = 1";
		//	WHERE username='".$username."' AND (password='".md5($password)."' OR md_pwd='".md5($password)."')  AND status = 1";
                                    
                                mysqli_set_charset($this->dbConnect, "utf8");
                                //mysqli_set_charset("utf8");
                                //echo "Current character set is: " . $mysqli -> character_set_name();
                                
                                //mysqli_query($this->dbConnect, 'SET NAMES utf8');
                                $resultSet = mysqli_query($this->dbConnect, $sqlQuery);
                        
                                $isValidLogin = mysqli_num_rows($resultSet);	
                                if($isValidLogin){
                                      $ok = True;
                                }else{
                                    $sqlQuery = "SELECT id, username, matricule, concat(nom_ar, ' ', prenom_ar) as name, sexe, email, password, user_type, eta_code, cycle, status, datec FROM ".$this->userTable." 
                                    WHERE username='".$username."' AND md_pwd=MD5('".$password."') AND status = 1";
                                    
                                    mysqli_set_charset($this->dbConnect, "utf8");
                                    $resultSet = mysqli_query($this->dbConnect, $sqlQuery);
                        
                                    $isValidLogin = mysqli_num_rows($resultSet);	
                                    if($isValidLogin){
                                        $ok = True;
                                    }   
                                }
                                
                                if($ok){
                                    $userDetails = mysqli_fetch_assoc($resultSet);
                                    //$_SESSION['langue'] = 'FR';
                                    //<script>
                                    //   let langue = 'FR';
                                    //<script>
                                    
                                    $_SESSION["userid"] = $userDetails['id'];
                                     $_SESSION["matricule"] = $userDetails['matricule'];
                                    $_SESSION["username"] = $userDetails['username'];
                                    $_SESSION["nom_prenom"] = $userDetails['name'];
                                    $_SESSION["user_type"] = $userDetails['user_type'];
                                    $_SESSION["user_eta_code"] = $userDetails['eta_code'];
                                    $_SESSION["eta_code"] = '';
                                    //$_SESSION["dep_id"] = $userDetails['dep_id'];
                                    $_SESSION["eta_code"] = $userDetails['eta_code'];
                                    $_SESSION["cycle"] = $userDetails['cycle'];
                                    $_SESSION["ann_univ"] = "2023/2024";
                                    $_SESSION["par_code"] = '*';
                                    $_SESSION["mat_code"] = '*';
                                    $_SESSION["ctc_code"] = '*';
                                    $_SESSION["sec_code"] = '0';
                                    $_SESSION["grp_code"] = '0';                                
                                    $_SESSION["sexe"] = $userDetails['sexe'];
                                    $_SESSION["ann_code"] = "0";
		$_SESSION["sem_code"] = "1";
		$_SESSION["uem_ckey"] = "";
                                    $_SESSION["matricule_ens"] = "";
                                    $_SESSION['type_recours'] = "1";
                                    $_SESSION['cycle_recours'] = "1";
                                    
                                    $_SESSION['stop_saisie'] = "0";
                                    $_SESSION['import_moyennes'] = "0";
                                    $_SESSION['stop_recours'] = "0";
                                    $_SESSION['hide_mg_uems'] = "0";
                                    $_SESSION['hide_mg_sems'] = "0";
				$_SESSION["uni_desl_ar"] = "جامعة الجزائر 3";
				$_SESSION["fac_desl_ar"] = "كلية العلوم الإقتصادية و العلوم التجارية و علوم التسيير";

/*				$_SESSION["uni_desl_ar"] = "جامعة الجيلالي بونعامة - خميس مليانة";
				$_SESSION["fac_desl_ar"] = "كلية العلوم الإجتماعية والإنسانية";

                                                                        $_SESSION["uni_desl_ar"] = "Ecole Nationale Supérieure de Technologie";
				$_SESSION["fac_desl_ar"] = "Direction des Etudes de Graduation et des Diplômes";
*/                                                                      $this->saveLoginSession();

				if($userDetails['user_type'] == '1') {
					$_SESSION["admin"] = 1;
					header("location: recours.php");
				}else if($userDetails['user_type'] == '2') {
					header("location: recours.php");
				}else if($userDetails['user_type'] == '3') {
					header("location: saisie_notes.php");
				}else if($userDetails['user_type'] == '4') {
                    $_SESSION["matricule"] = $userDetails['username'];
					header("location: notes.php");
				}
				
			} else {		
				$errorMessage = "Utilisateur et/ou Mot de passe erronés";
			}
		} else if(!empty($_POST["login"])){
			$errorMessage = "Entrez le nom del l'utilisateur et le mot de passe";
		}
		return $errorMessage; 		
	}
	public function getUserInfo() {
		//echo '<script>alert("getUserInfo")</script>';

		if(!empty($_SESSION["userid"])) {
			$sqlQuery = "SELECT id, username, nom, prenom, nom_ar, prenom_ar, email, user_type, eta_code, cycle, status, datec FROM ".$this->userTable." 
				WHERE id ='".$_SESSION["userid"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);		
			$userDetails = mysqli_fetch_assoc($result);
			
			///////echo '<script>alert("getUserInfo-OK")</script>';
			return $userDetails;
		}		
	}
	public function getColoumn($id, $column) {     
        $sqlQuery = "SELECT id, concat(nom_ar, ' ', prenom_ar) as name, email, user_type, eta_code, cycle, status, datec FROM ".$this->userTable." 
			WHERE id ='".$id."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);		
		$userDetails = mysqli_fetch_assoc($result);
		return $userDetails[$column];       
    }
	
	
	public function listUser(){
		//echo '<script>alert("listUser in Users.php")</script>';
		$sqlQuery = "SELECT id, username, nom, prenom, nom_ar, prenom_ar , sexe, email, datec, user_type, eta_code, cycle, status 
			FROM ".$this->userTable;
			
		if(!empty($_POST["search"]["value"])){
			//$sqlQuery .= ' (id LIKE "%'.$_POST["search"]["value"].'%" ';					
			//$sqlQuery .= ' OR name LIKE "%'.$_POST["search"]["value"].'%" ';
			//$sqlQuery .= ' OR status LIKE "%'.$_POST["search"]["value"].'%" ';					
		}
		if(!empty($_POST["order"])){
			//$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
		//	$sqlQuery .= ' ORDER BY id DESC ';
		}
		if($_POST["length"] != -1){
			//$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$userData = array();	
		while( $user = mysqli_fetch_assoc($result) ) {		
			$userRows = array();			
			$status = '';
			if($user['status'] == 1)	{
				$status = '<span class="label label-success">مستعمل</span>';
			} else if($user['status'] == 0) {
				$status = '<span class="label label-danger">غير مستعمل</span>';
			}	
			
			$userRole = '';
			if($user['user_type'] == '1')	{
				$userRole = '<span class="label label-danger">أدمن الكلية</span>';
			} else if($user['user_type'] == '2') {
				$userRole = '<span class="label label-warning">أدمن القسم</span>';
			} else if($user['user_type'] == '3') {
				$userRole = '<span class="label label-warning">أستاذ</span>';
			} else if($user['user_type'] == '4') {
				$userRole = '<span class="label label-warning">طالب</span>';
			}	

			$userRows[] = $user['id'];
			//$userRows[] = $user['nom'];
			//$userRows[] = $user['prenom'];
			$userRows[] = $user['nom_ar'] . " " . $user['prenom_ar'];
			$userRows[] = $user['username'];
			$userRows[] = $user['email'];
			//$userRows[] = $userRole; //$user['user_type'];
			$userRows[] = $user['datec'];
			$userRows[] = $userRole;			
			$userRows[] = $status;
				
			$userRows[] = '<button type="button" name="update" id="'.$user["id"].'" class="btn btn-warning btn-xs update">تعديل</button>';
			$userRows[] = '<button type="button" name="delete" id="'.$user["id"].'" class="btn btn-danger btn-xs delete">حذف</button>';
			$userData[] = $userRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$userData
		);
		echo json_encode($output);
	}	
	
	
	public function getUserDetails(){		
		//echo '<script>alert("GetUserDetails")</script>';
		
		if($this->id) {		
			$sqlQuery = "
				SELECT id, username, nom, prenom, nom_ar, prenom_ar, sexe, email, password, user_type, eta_code, cycle, status, datec 
				FROM ".$this->userTable." 
				WHERE id = '".$this->id."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);
			//echo '<script>alert("Lecture User")</script>';
		}		
	}
        
	
	public function insert() {      
		if($this->username && $this->password) {		              
			$this->username = strip_tags($this->username);			
			$this->password = md5($this->password);			
			$queryInsert = "
				INSERT INTO ".$this->userTable."(username, nom, prenom, nom_ar, prenom_ar, sexe, email, user_type, eta_code, cycle, status, password, datec) VALUES(
					'".$this->username."', '".$this->nom."', '".$this->prenom."', '".$this->nom_ar."', '".$this->prenom_ar."', '".$this->sexe."', '".$this->email."', '".$this->user_type."', '".$this->eta_code."',  '".$this->departement."', '".$this->cycle."', '".$this->status."', '".$this->password."',Now())";
			mysqli_query($this->dbConnect, $queryInsert);			
		}
	}	
	
                public function getEtudiantInscription(){
                         $etudiant_ins_arr = array("matricule" => $_SESSION['matricule'], "par_code" => "*", "ann_code"=>"*");
                        
                        if ($_SESSION['matricule']){
                            $sqlQuery = "SELECT eta_code,  par_code, ann_code, cycle FROM rdn_etus ";
                            $sqlQuery .= " WHERE matricule = '".$_SESSION['matricule']."'";
                            $sqlQuery .= " ORDER BY ann_univ DESC, ann_code DESC";
                            
                            $result = mysqli_query($this->dbConnect, $sqlQuery);
                            $numRows = mysqli_num_rows($result);
                            if($numRows > 0){
                                $etudiant = mysqli_fetch_assoc($result);
                                $etudiant_ins_arr['par_code'] = $etudiant['par_code'];
                                $etudiant_ins_arr['ann_code'] = $etudiant['ann_code'];
                         }
                    }
                    return $etudiant_ins_arr;
              }
	public function update() { 
		//alert("update function");     
		//echo '<script>alert("Update")</script>';
		if($this->updateUserId && $this->username) {
		//if (1 == 1){	
			$this->username = strip_tags($this->username);

			$changePassword = '';
			if($this->password) {
				$this->password = md5($this->password);
				$changePassword = ", password = '".$this->password."'";
			}
			//SET username = '".$this->username."',nom = '".$this->nom."', prenom_ar = '".$this->prenom_ar."', nom_ar = '".$this->nom_ar."', prenom_ar = '".$this->prenom_ar."', sexe = '".$this->sexe."', email = '".$this->email."', user_type = '".$this->user_type."', status = '".$this->status."' $changePassword
			$queryUpdate = "
				UPDATE ".$this->userTable." 
				SET username = '".$this->username."',nom = '".$this->nom."', prenom_ar = '".$this->prenom_ar."', nom_ar = '".$this->nom_ar."', prenom_ar = '".$this->prenom_ar."', sexe = '".$this->sexe."', email = '".$this->email."', eta_code= '".$this->departement."', cycle = '".$this->cycle."', user_type = '".$this->user_type."', status = '".$this->status."', datem = now() $changePassword
				WHERE id = '".$this->updateUserId."'";				
			mysqli_query($this->dbConnect, $queryUpdate);
			//echo mysql_errno($dbConnect) . ": " . mysql_error($dbConnect) . "\n";
			//$err = mysql_error($dbConnect);
			//alert($err);		
			//if (!mysqli_query($this->dbConnect, $queryUpdate)) {
			//	printf("Errormessage: %s\n", mysqli_error($this->dbConnect));
			//}				
		}
	}	
	
	public function delete() {      
		if($this->deleteUserId) {		          
			$queryUpdate = "
				DELETE FROM ".$this->userTable." 
				WHERE id = '".$this->deleteUserId."'";				
			mysqli_query($this->dbConnect, $queryUpdate);			
		}
	}
                
                public function saveLoginSession() {      
		if(isset($_SESSION["username"])) {	
                                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                                        $webpage = $_SERVER['SCRIPT_NAME'];
                                        $timestamp = date('d/m/Y h:i:s');
                                        $browser = ''; //$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR']: $_SERVER['REMOTE_ADDR'];
                                        //$_SERVER['HTTP_USER_AGENT'];
                                        //$this->username = strip_tags($this->username);			
                                        //$this->password = md5($this->password);			
                                        $queryInsert = "INSERT INTO gpw_jrn_sess(user_name, user_ip, user_browser, user_login)";
                                        $queryInsert .= " VALUES('".$_SESSION['username']."','".$ipaddress."','".$browser."', now())";
                                                 
                                        mysqli_query($this->dbConnect, $queryInsert);
                                        $last_id = mysqli_insert_id($this->dbConnect);
                                        $_SESSION['jrn_id'] = $last_id;
		}
	}
                 public function saveLogoutSession() {      
		if(isset($_SESSION["username"])) {	
                                        $queryInsert = "UPDATE gpw_jrn_sess SET user_logout = now()";
                                        $queryInsert .= " WHERE id = ".$_SESSION['jrn_id'];
                                        mysqli_query($this->dbConnect, $queryInsert);
		}
	}	

}