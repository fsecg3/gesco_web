<?php

class Type_recours extends Database {  
    
	private $typeRecoursTable = 'gpw_ldcs';
	
	private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	public function listTypeRecours(){
			 			 
		$sqlQuery = "SELECT id, ldc_code, ldc_type, ldc_desl, ldc_desl_ar, datec
			FROM ".$this->typeRecoursTable;
			
	        $sqlQuery .= ' ORDER BY id ASC ';
		
		if($_POST["length"] != -1){
		//	$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$typerecoursData = array();	
		while( $typerecours = mysqli_fetch_assoc($result) ) {
			$typerecoursRows = array();			
			$typerecoursRows[] = $typerecours['id'];
			$typerecoursRows[] = $typerecours['ldc_desl_ar'];
			$typerecoursRows[] = $typerecours['ldc_desl'];
			//$typerecoursRows[] = $status;
				
			$typerecoursRows[] = '<button type="button" name="update" id="'.$typerecours["id"].'" class="btn btn-warning btn-xs update">تعديل</button>';
			$typerecoursRows[] = '<button type="button" name="delete" id="'.$typerecours["id"].'" class="btn btn-danger btn-xs delete">حذف</button>';
			$typerecoursData[] = $typerecoursRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$typerecoursData
		);
		echo json_encode($output);
	}	
	
		
	public function getTypeRecoursDetails($pTypeRecoursId){		
		//if($_POST["typerecoursId"]) {
		if (isset($pTypeRecoursId)){	

			$sqlQuery = "
				SELECT id, ldc_code, ldc_type, ldc_desl, ldc_desl_ar
				FROM ".$this->typerecoursTable." 
				WHERE id = '".$pTypeRecoursId."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);
			//echo json_last_error_msg(); // Print out the error if any
			//die(); // halt the script
		}		
	}
	
	public function insert() {      
		if($this->ldc_desl || $this->ldc_desl_ar) {		              
			$this->ldc_desl = strip_tags($this->ldc_desl);
			$this->ldc_desl_ar = strip_tags($this->ldc_desl_ar);			              
			$queryInsert = "INSERT INTO ".$this->typeRecoursTable." (ldc_code, ldc_type, ldc_desl, ldc_desl_ar, datec) 
			VALUES('".$this->ldc_code."', "."'REC_TYPE','".$this->ldc_desl."', '".$this->ldc_desl_ar."', Now())";
			mysqli_query($this->dbConnect, $queryInsert);			
			echo "inserted";
		}
	}

	public function update() {      
		if($this->typerecoursId && ($this->ldc_desl || $this->ldc_desl_ar)) {		              
			$this->ldc_desl = strip_tags($this->ldc_desl);
			$this->ldc_desl_ar = strip_tags($this->ldc_desl_ar);
			$queryUpdate = "
				UPDATE ".$this->typeRecoursTable." 
				SET ldc_code = '".$this->ldc_code."', ldc_desl = '".$this->ldc_desl."', ldc_desl_ar = '".$this->ldc_desl_ar."', datem = Now()'
				WHERE id = '".$this->typerecoursId."'";				
			mysqli_query($this->dbConnect, $queryUpdate);			
		}
	}	
	
	public function delete() {      
		if($_POST['typerecoursId']) {		          
			$queryDelete = "
				DELETE FROM ".$this->typeRecoursTable." 
				WHERE id = '".$_POST['typerecoursId']."'";				
			mysqli_query($this->dbConnect, $queryDelete);			
		}
	}
	
}