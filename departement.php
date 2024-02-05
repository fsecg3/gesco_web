<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>Gesco Web Portal (2023)</title>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/general.js"></script>
<script src="js/departement.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>		

<div class="container">	
	<!--
	<div class="row home-sections">
    	<?php $notes->getUniversityName('AR'); ?>
		<?php $notes->getPageTitle('AR'); ?>
	</div> 
	-->
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-10">
				<h3 class="panel-title"></h3>
			</div>
			<div class="col-md-2" align="right">
				<button type="button" name="add" id="addDepartement" class="btn btn-success btn-xs">إظافة قسم جديد</button>
			</div>
		</div>
	</div>
			
	<table id="listDepartement" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>الرقم</th>
				<th>القسم</th>
				<th>Département</th>
				<th>الحالة</th>
				<th></th>
				<th></th>									
			</tr>
		</thead>
	</table>
	
	<div id="departementModal" class="modal fade">
		<div class="modal-dialog">
			<form method="post" id="departementForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> إظافة قسم جديد</h4>
					</div>
					<div class="modal-body">
						<div class="form-group"
							<label for="dep_desl_ar" class="control-label">القسم</label>
							<input type="text" class="form-control" id="dep_desl_ar" name="dep_desl_ar" placeholder="القسم" required>
						</div>
						<div class="form-group"
							<label for="dep_desl" dir=ltr class="control-label">Département</label>
							<input type="text" dir=ltr class="form-control" id="dep_desl" name="dep_desl" placeholder="Département" required>
						</div>
						<div class="form-group">
							<label for="status" class="control-label">الحالة</label>				
							<select id="status" name="status" class="form-control">
							<option value="1">تفعيل</option>				
							<option value="0">إبطال</option>	
							</select>						
						</div>						
						
					</div>
					<div class="modal-footer">
						<input type="hidden" name="departementId" id="departementId" />
						<input type="hidden" name="action" id="action" value="" />
						<input type="submit" name="save" id="save" class="btn btn-info" value="حفظ" />
						<button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>	
<?php include('inc/footer.php');?>