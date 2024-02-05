<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>Gesco Portail Web 2023</title>
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="dist/js/jquery_wrapper.min.js"></script>
<link href="dist/css/tabulator.min.css" rel="stylesheet">
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="dist/js/jquery_wrapper.min.js"></script>
<link href="dist/css/tabulator.min.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>
-->

<!--
<link href="dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="dist/js/jquery_wrapper.js"></script>
--
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.tabledit.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/general.js"></script>
<script src="js/saisie_notes.js"></script>
-->
<link rel="stylesheet" href="css/style.css" />


<?php include('inc/container.php');?>
<?php include('menus.php'); ?>

<div class="container">
	<!--	
	<div class="row home-sections">
	
	<?php $notes->getPageTitle('AR', 'رصد العلامات'); ?>	
	
		<p>Saisie des notes contrôles continus et examens.</p>	
	-->
        <div class="input-field">
            
            <?php if($_SESSION['langue'] == 'AR'){ echo "<label>المسلك:</label>";}else{echo "<label>Parcours:</label>";} ?>
             <select name="parcours" id="parcours" class="parcours" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
                <?php $saisie_notes->get_EnseignantParcours(); ?>
            </select>            
             &nbsp;&nbsp;&nbsp;   
             <label></label><?php if($_SESSION['langue'] == 'AR'){ echo "<label>المـادة:</label>";}else{echo "<label>Matière:</label>";} ?>
            <select name="matiere" id="matiere" class="matiere" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>
             &nbsp;&nbsp;&nbsp;
            <?php if($_SESSION['langue'] == 'AR'){ echo "<label>الإمتحان:</label>";}else{echo "<label>Examen:</label>";} ?>
            <select name="examen" id="examen" class="examen" style="width: 10%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"> </select>
             &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;

             <?php if($_SESSION['langue'] == 'AR'){ echo "<label>المجموعة:</label>";}else{echo "<label>Section:</label>";} ?>
             <select name="section" id="section" class="section" style="width: 2%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>             
             &nbsp;&nbsp;&nbsp;

            <?php if($_SESSION['langue'] == 'AR'){ echo "<label>الفوج:</label>";}else{echo "<label>Groupe:</label>";} ?>
            <select name="groupe" id="groupe" class="groupe" style="width: 2%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>             
            
            <input type="hidden" name="sem_code" id="sem_code" />
            <?php $saisie_notes->getActions(); ?>
        </div>

	<div class="">   		
      <div id="SaisieNotesTable"></div>
      <script src="js/saisie_notes.js"></script>
	</div>
         
        
</div>
<?php include('inc/footer.php');?>
</html>

<script>
    
    function getEnseignantMatiereCtcs(val) {
      var action = "getEnseignantMatiereCtcs";
      $.ajax({
        type: "POST",
        url: "saisie_notes_action.php",
        //data:'mat_code='+val,
        data:{mat_code:val, action:action},
        success: function(data){
          $("#ctc_code").html(data);
        }
      });
      
      $('.sec_code').selectpicker('render');
      $('.sec_code').selectpicker('refresh');
;
    }
    
    function getEnseignantMatiereSections(val) {
      var action = "getEnseignantMatiereSections";
      $.ajax({
        type: "POST",
        url: "saisie_notes_action.php",
        data:{ctc_code:val, action:action},
        success: function(data){
          $("#sec_code").html(data);
        }
      });
    }
     
    function getEnseignantMatiereGroupes(val) {
      var action = "getEnseignantMatiereGroupes";
      $.ajax({
        type: "POST",
        url: "saisie_notes_action.php",
        data:{ctc_code:val, action:action},
        success: function(data){
          $("#grp_code").html(data);
        }
      });
    }
    
  </script> 
