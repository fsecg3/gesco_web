<?php

class Site extends Database {  
    
	private $siteTable = 'gpw_site';
	
	private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	public function listSite(){
			 			 
		$sqlQuery = "SELECT id, dep_desl_ar, dep_desl, status
			FROM ".$this->departementsTable;
			
		if(!empty($_POST["search"]["value"])){
		//	$sqlQuery .= ' (id LIKE "%'.$_POST["search"]["value"].'%" ';					
		//	$sqlQuery .= ' OR name LIKE "%'.$_POST["search"]["value"].'%" ';
		//	$sqlQuery .= ' OR status LIKE "%'.$_POST["search"]["value"].'%" ';					
		}
		if(!empty($_POST["order"])){
		//	$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY id DESC ';
		}
		if($_POST["length"] != -1){
		//	$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$departementData = array();	
		while( $departement = mysqli_fetch_assoc($result) ) {
			$departementRows = array();			
			$status = '';
			if($departement['status'] == "1")	{
				$status = '<span class="label label-success">مستعمل</span>';
			} else if($departement['status'] == "0") {
				$status = '<span class="label label-danger">غير مستعمل</span>';
			}	
			
			$departementRows[] = $departement['id'];
			$departementRows[] = $departement['dep_desl_ar'];
			$departementRows[] = $departement['dep_desl'];
			$departementRows[] = $status;
				
			$departementRows[] = '<button type="button" name="update" id="'.$departement["id"].'" class="btn btn-warning btn-xs update">تعديل</button>';
			$departementRows[] = '<button type="button" name="delete" id="'.$departement["id"].'" class="btn btn-danger btn-xs delete">حذف</button>';
			$departementData[] = $departementRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$departementData
		);
		echo json_encode($output);
	}	
	
		
        public function getSiteEtablissement(){		
	$sqlQuery = "SELECT * FROM gpw_site";
				
	$result = mysqli_query($this->dbConnect, $sqlQuery);
                if(!$result){
	die(mysqli_error($this->dbConnect));
                }
                  $numRows = mysqli_num_rows($result);
                  
                  if($numRows > 0){
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if($row){
                            $_SESSION['langue'] = $row['site_langue'];
                            $_SESSION['import_notes'] = $row['import_notes'];
                            if($_SESSION['langue'] == 'AR'){
                                echo $row['site_eta_ar'];
                            }else{
                                echo $row['site_eta'];
                            }
                            $_SESSION['stop_saisie'] = $row['stop_saisie'];
                            $_SESSION['import_moyennes'] = $row['import_notes'];
                            $_SESSION['stop_recours'] = $row['stop_recours'];
                            $_SESSION['hide_mg_uems'] = $row['hide_mg_uems'];
                            $_SESSION['hide_mg_sems'] = $row['hide_mg_sems'];
			//echo json_last_error_msg(); // Print out the error if any
			//die(); // halt the script
                        }		
	}
        }
    public function getSiteLoginMessage(){		
                $sqlQuery = "SELECT * FROM gpw_site";
				
                $result = mysqli_query($this->dbConnect, $sqlQuery);	
                  if(!$result){
	die(mysqli_error($this->dbConnect));
                }

                $numRows = mysqli_num_rows($result);
                if($numRows > 0){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    if($row){
                        //$_SESSION['langue'] = $row['site_langue'];
                        //$_SESSION['import_notes'] = $row['import_notes'];
                        if($_SESSION['langue'] == 'AR'){
                                echo $row['site_login_msg_ar'];
                        }else{
                                echo $row['site_login_msg'];
                        }
			//echo json_last_error_msg(); // Print out the error if any
			//die(); // halt the script
	}		
                }
    }
    public function getSitePageMessage(){		
                $sqlQuery = "SELECT * FROM gpw_site";
				
                $result = mysqli_query($this->dbConnect, $sqlQuery);	
                $numRows = mysqli_num_rows($result);
                if($numRows > 0){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    if($row){
                    //$_SESSION['langue'] = $row['site_langue'];
                    //$_SESSION['import_notes'] = $row['import_notes'];
                    if($_SESSION['langue'] == 'AR'){
                            echo $row['site_page_msg_ar'];
                    }else{
                            echo $row['site_page_msg'];
                    }
			//echo json_last_error_msg(); // Print out the error if any
			//die(); // halt the script
                }		
            }
    }
	public function insert() {      
		if($this->dep_desl || $this->dep_desl_ar) {		              
			$this->dep_desl = strip_tags($this->dep_desl);
			$this->dep_desl_ar = strip_tags($this->dep_desl_ar);			              
			$queryInsert = "INSERT INTO ".$this->departementsTable." (dep_desl, dep_desl_ar, status) 
			VALUES('".$this->dep_desl."', '".$this->dep_desl_ar."', '".$this->status."')";			
			mysqli_query($this->dbConnect, $queryInsert);			
			echo "inserted";
		}
	}

	public function update() {      
		if($this->departementId && ($this->dep_desl || $this->dep_desl_ar)) {		              
			$this->dep_desl = strip_tags($this->dep_desl);
			$this->dep_desl_ar = strip_tags($this->dep_desl_ar);
			$queryUpdate = "
				UPDATE ".$this->departementsTable." 
				SET dep_desl = '".$this->dep_desl."', dep_desl_ar = '".$this->dep_desl_ar."', status = '".$this->status."' 
				WHERE id = '".$this->departementId."'";				
			mysqli_query($this->dbConnect, $queryUpdate);			
		}
	}	
	
	public function delete() {      
		if($this->departementId) {		          
			$queryUpdate = "
				DELETE FROM ".$this->departementsTable." 
				WHERE id = '".$this->departementId."'";				
			mysqli_query($this->dbConnect, $queryUpdate);			
		}
	}
	
}