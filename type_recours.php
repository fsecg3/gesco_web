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
<script src="js/Type_recours.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>		

<div class="container">	
	<!--
	<div class="row home-sections">
	</div> 
	-->

	<div class="panel-heading">
		<div class="row">
			<div class="col-md-10">
				<h3 class="panel-title"></h3>
			</div>
			<div class="col-md-2" align="right">
				<button type="button" name="add" id="addTypeRecours" class="btn btn-success btn-xs">إظافة نوع طعن جديد</button>
			</div>
		</div>
	</div>
			
	<table id="listTypeRecours" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>الرقم</th>
				<th>الرمز</th>
				<th>إسم الطعن</th>
                                <th>Intitulé recours</th>
				<th>aa</th>
				<th>bb</th>	
                                <th>Intitulé recours</th>
			</tr>
		</thead>
	</table>
	
	<div id="typerecoursModal" class="modal fade">
		<div class="modal-dialog">
			<form method="post" id="typerecoursForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> إظافة نوع طعن جديد</h4>
					</div>
					<div class="modal-body">
						<div class="form-group"
							<label for="dep_desl_ar" class="control-label">رمز الطعن</label>
							<input type="text" class="form-control" id="ldc_code" name="ldc_code" placeholder="رمز الطعن" required>
						</div>
						<div class="form-group"
							<label for="dep_desl_ar" class="control-label">إسم الطعن</label>
							<input type="text" class="form-control" id="ldc_desl_ar" name="ldc_desl_ar" placeholder="إسم الطعن" required>
						</div>
						<div class="form-group"
							<label for="dep_desl" dir=ltr class="control-label">Intitulé recours</label>
							<input type="text" dir=ltr class="form-control" id="ldc_desl" name="ldc_desl" placeholder="Intitulé recours" required>
						</div>
						<!--
						<div class="form-group">
							<label for="status" class="control-label">الحالة</label>				
							<select id="status" name="status" class="form-control">
							<option value="1">تفعيل</option>				
							<option value="0">إبطال</option>	
							</select>						
						</div>						
						-->
					</div>
					<div class="modal-footer">
						<input type="hidden" name="typerecoursId" id="typerecoursId" />
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