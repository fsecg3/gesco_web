<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>Gesco Portail Web 2022</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>

<link href="dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="dist/js/jquery_wrapper.js"></script>
<!--
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.tabledit.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/general.js"></script>
<script src="js/stats.js"></script>
-->
<link rel="stylesheet" href="css/style.css" />


<?php include('inc/container.php');?>
<?php include('menus.php'); ?>

<div class="container">
	<!--	
	<div class="row home-sections">
	
	<?php $notes->getPageTitle('AR', 'إحصائيات'); ?>	
	
		<p>Saisie des notes contrôles continus et examens.</p>	
	-->
        <div class="input-field">
            <label>المؤسسة:</label> <select name="etablissement" id="etablissement" class="etablissement" style="width: 30%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
             <?php $stats->get_Etablissements(); ?>
            </select>            
            &nbsp;&nbsp;
            <label>الـطـــور:</label> <select name="cycle" id="cycle" class="cycle" style="width: 1%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>
            &nbsp;&nbsp;&nbsp;&nbsp;
             <label>المسلك:</label> <select name="parcours" id="parcours" class="parcours" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
             <?php $saisie_notes->get_EnseignantParcours(); ?>
            </select>            
             &nbsp;&nbsp;&nbsp;&nbsp;   
            <label>السنة:</label> <select name="annee" id="annee" class="annee" style="width: 1%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>
             &nbsp;&nbsp;&nbsp;
            <label>السداسي:</label> <select name="semestre" id="semestre" class="semestre" style="width: 1%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"> </select>
             <br>
             
             
            <label>المــــــادة:</label> <select name="matiere" id="matiere" class="matiere" style="width: 20%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>
             &nbsp;&nbsp;
            <label>الإمتحان:</label> <select name="examen" id="examen" class="examen" style="width: 1%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"> </select>
             &nbsp;&nbsp;&nbsp; &nbsp;

             <label>الأستاذ:</label> <select name="enseignant" id="enseignant" class="enseignant" style="width: 2%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"></select>             
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 

            <input type="hidden" name="sem_code" id="sem_code" />
            <?php $stats->getActions(); ?>
<!--
            <div class="panel-heading">
            <div class="row">
                <div class="col-md-2" align="left">
                    <input type="hidden" name="sem_code" id="sem_code" />
                    <?php $stats->getActions(); ?>
                </div>
            </div>

            <div class="col-md-2" align="right">
                
            </div>
-->	            
        </div>

	<div class="">   		
    <div id="Stats_SaisieNotesTable"></div>
    <script src="js/stats.js"></script>
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
