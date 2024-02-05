<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>Gesco Portail Web 2023</title>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/general.js"></script>
<script src="js/recours.js"></script>
<script src="js/notes.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>
<div class="container">
	<!--	
	<div class="row home-sections">
	
<!--	<?php $notes->getPageTitle('AR', 'الطعون'); ?> 
	</div> 
	
		<p>Consultez vos notes & résultats, adressez vos recours directement à l'administration.</p>	
	-->
	<div class=""> 
                        <?php if($_SESSION['user_type'] != '4'):?>
                        <?php $_SESSION['cycle_recours'] = '1'; $_SESSION['type_recours'] = '1';?>
                        <?php if($_SESSION['langue'] == 'AR'){ echo "<label>الطور:</label>";}else{echo "<label>Cycle:</label>";} ?>
                        <select name="cycle_recours" id="cycle_recours" class="cycle_recours" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
                            <option value="0">كل الأطوار</option>
                            <option value='1' selected>ليسانس</option>
                            <option value='2'>ماستر</option>
                            
                        </select>            
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label></label><?php if($_SESSION['langue'] == 'AR'){ echo "<label>نوع الطعن:</label>";}else{echo "<label>Type Recours:</label>";} ?>
                        <select name="type_recours" id="type_recours" class="type_recours" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">
                            <?php $recours->get_TypesRecours(); ?>
                        </select>
                       <?php endif;?>
		<table id="listRecours" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text-center">رقم الطعن</th>
					<th class="text-center">صاحب الطعن</th>
					<th class="text-center">الطور / التخصص / التسجيل</th>
					<th class="text-center">سبب الطعن</th>
					<th class="text-center">معلومات إضافية عن الطعن</th>
					<th class="text-center">المادة</th>
					<th class="text-center">القسم المعني بالطعن</th>					
					<th class="text-center">الأستاذ المعني بالطعن</th>	
					<th class="text-center">تاريخ تقديم الطعن</th>
					<th class="text-center">...</th>
					<th class="text-center">...</th>
                                        <th></th>
                                        <th></th>
				</tr>
			</thead>
		</table>
	</div>
	<?php include('add_recours_modal.php'); ?>
        	<?php include('redirect_recours.php'); ?>

</div>	
<?php include('inc/footer.php');?>