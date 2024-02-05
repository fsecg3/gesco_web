<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>Gesco Portal Web (2023)</title>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/general.js"></script>
<script src="js/notes.js"></script>
<!-- <link rel="stylesheet" href="css/style.css" /> -->
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>	

<div class="container">
    <!--	
    <div class="row home-sections">
    <?php $notes->getUniversityName('AR'); ?>
      <h4>   
	<?php $notes->getPageTitle('AR', 'النتائج'); ?>
    </h4>
    </div>
    --> 
    <div class="">   		
        
        
        <div class="panel-heading">
            <?php $notes->getEtudiantInfos();?> 
            
            <div class="row">
                <h4>
                <?php if($_SESSION['langue'] == 'AR'){ echo "<label>المسلك:</label>";}else{echo "<label>Parcours:</label>";} ?>
                <select name="parcours" id="parcours" class="parcours"   style="width: 70%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
<!--                <select name="parcours" id="parcours" class="parcours" onchange="loadSemestres()"  style="width: 70%; padding: 0.375rem 0.75rem;    border-color: #ced4da;"> -->
                    <?php $notes->getListeInscriptionsEtudiant();?>
                </select>
                &nbsp;&nbsp;
                <?php if($_SESSION['langue'] == 'AR'){ echo "<label>السداسي:</label>";}else{echo "<label>Semestre:</label>";} ?>
                <select name="semestre" id="semestre" class="semestre" style="width: 12%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
                    </select>
            </h4>
                <div class="col-md-2" align="right">
                    <input type="hidden" name="sem_code" id="sem_code" />
<!--                    <?php $notes->getSemestres(); ?> -->

                </div>
            </div>
        </div>
			
        <table id="listNotes" class="table table-bordered table-striped">
            <thead>
                <tr>
                     <?php if($_SESSION['langue'] == 'AR'){ 
                        echo '<th data-visible="false">Column</th>';
                        echo '<th class="text-center">الرقم</th>';
                        echo '<th class="text-center">الوحدة / المادة</th>';
                        echo '<th class="text-center">المعامل</th>';
                        echo '<th class="text-center">الرصيد</th>';
                        echo '<th class="text-center">مكسب سابق</th>';
                        if($_SESSION['DB'] != 'UFC_WEB'){
                            echo '<th class="text-center">أعمال موجهة</th>';
                            echo '<th class="text-center">أعمال تطبيقية</th>';
                        }else{
                            echo '<th class="text-center">الأرضية</th>';
                            echo '<th class="text-center">التجمعات</th>';
                        }

                        echo '<th class="text-center">الإمتحان</th>';
                        echo '<th class="text-center">الإستدراك</th>';
                        echo '<th class="text-center">المعدل</th>';
                        echo '<th class="text-center">الرصيد المحصل عليه</th>';
                        echo '<th class="text-center">ملاحظة</th>';
                        echo '<th class="text-center">..</th>';
                        echo '<th class="text-center">..</th>';
                        echo '<th class="text-center">..</th>';

                         }else{
   echo '<th data-visible="false">Column</th>';
                        echo '<th class="text-center">N°</th>';
                        echo '<th style="width:130px">Unité d'.'enseignement / Matière style</th>';
                        echo '<th class="text-center">Coeff.</th>';
                        echo '<th class="text-center">Crédits</th>';
                        echo '<th class="text-center">Acquis Ant.</th>';
                        echo '<th class="text-center">CC</th>';
                        echo '<th class="text-center">TP</th>';
                        echo '<th class="text-center">Examen</th>';
                        echo '<th class="text-center">Rattrapage</th>';
                        echo '<th class="text-center">MG</th>';
                        echo '<th class="text-center">Crédits. obt.</th>';
                        echo '<th class="text-center">Observation</th>';
                        echo '<th class="text-center">..</th>';
                        echo '<th class="text-center">..</th>';
                        echo '<th class="text-center">..</th>';
                             
                     } ?>
                </tr>
            </thead>
        </table>
    </div>
    <?php include('add_recours.php');?>	
</div>	
<?php include('inc/footer.php');?>

<?php


