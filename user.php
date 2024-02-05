<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
?>
<title>Gesco Portail Web (2023)</title>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/general.js"></script>
<script src="js/user.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>

<div class="container">	
	<!--
	<div class="row home-sections">
	<h2>البوابة الرقمية لكلية العلوم الإجتماعية والإنسانية  - المستخدمون</h2>	
	
	</div> 
	-->

	<div class="panel-heading">
		<div class="row">
			<div class="col-md-10">
				<h3 class="panel-title"></h3>
			</div>
			<div class="col-md-2" align="right">
				<button type="button" name="add" id="addUser" class="btn btn-success btn-xs">إضافة مستخدم جديد</button>
			</div>
		</div>
	</div>
			
	<table id="listUser" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>الرقم</th>
				<th>القب والإسم</th>
				<th>إسم المستخدم</th>
				<th>العنوان الإلكتروني</th>
				<th>تاريخ الإنشاء</th>
				<th>طبيعة المستخدم</th>
				<th>الحالة</th>
				<th>...</th>
				<th>...</th>				
			</tr>
		</thead>
	</table>
	
	<div id="userModal" class="modal fade">
		<div class="modal-dialog">
			<form method="post" id="userForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> إضافة مستخدم جديد</h4>
					</div>
					<div class="modal-body">
						<div class="form-group" style="float:right"
							<label for="nom_ar" class="control-label">اللقب*</label>
							<input type="text" class="form-control" id="nom_ar" name="nom_ar" placeholder="اللقب" required>
						</div>
						<div class="form-group" style="float:left"
                                                            <label for="nom" dir=ltr class="control-label">Nom</label>
							<input type="text" dir=ltr class="form-control" id="nom" name="nom" placeholder="Nom">			
						</div>
						<div class="form-group" style="float:right"
							<label for="prenom_ar" class="control-label">الإسم*</label>
							<input type="text" class="form-control" id="prenom_ar" name="prenom_ar" placeholder="الإسم" required>
						</div>
						<div class="form-group" style="float:left"
							<label for="prenom" dir=ltr class="control-label">Prénom</label>
							<input type="text" dir=ltr class="form-control" id="prenom" name="prenom" placeholder="Prénom">
						</div>
						<div class="form-group" style="float:right"
							<label for="username" class="control-label">إسم المستخدم*</label>
							<input type="text" dir=ltr class="form-control" id="username" name="username" placeholder="Nom de l'utilisateur" required>
						</div>
						<div class="form-group" style="float:left"
							<label for="email" class="control-label">العنوان الإلكتروني</label>
							<input type="email" dir=ltr class="form-control" id="email" name="email" placeholder="Email">
						</div>

						<div style="clear:both;"></div>
						<div class="form-group" style="float:right"
							<label for="date_nais" class="control-label">تاريخ الميلاد</label>
							<input type="date" dir=ltr class="form-control" id="date_nais" name="date_nais" placeholder="تاريخ الميلاد">
						</div>
                                                
						<div class="form-group" style="float:left"
							<label for="sexe" class="control-label">الجنس</label>				
							<select id="sexe" name="sexe" class="form-control">
							<option value="1">ذكر</option>				
							<option value="2">أنثى</option>	
							</select>						
						</div>													

                        <div style="clear:both;"></div>
<!--						
			<div dir=ltr class="form-group">
			<label for="name" class="col-lg-2">A</label>
			<label for="name" class="col-lg-2">B:</label>
			<label for="name" class="col-lg-2">C:</label>
</div>
-->			
			<div dir=rtl class="form-group">
            <div class="col-lg-4">
			<select id="user_type" name="user_type" class="form-control">
							<option value="1">أدمن الكلية</option>				
							<option value="2">أدمن القسم</option>	
							<option value="3" selectedS>أستاذ</option>				
							<option value="4">طالب</option>	
							</select>							
                
            </div>

			<div class="form-group">
            <div class="col-lg-4">
			<select id="cycle" name="cycle" class="form-control">
							<option value="1">ليسانس</option>				
                            <option value="2"> ماستر</option>	
                            <option value="3">ليسانس + ماستر</option>	
							</select>	
            </div>

			<div class="form-group">
			<div class="col-lg-4">
				<select id="departement" name="departement" class="form-control">
<!--                                    
					<option value="0">جميع الأقسام</option>				
					<option value="1">علوم إنسانية</option>	
					<option value="2">علوم إجتماعية</option>				
-->					
												
					<?php $notes->getDepartements('ALL');?> 				

				</select>									
			</div>
        </div>	
		<!--					
						<div class="form-group" style=="float: right"
							<label for="user_type" class="control-label">طبيعة المستخدم</label>				
							<select id="user_type" name="user_type" class="form-control">
							<option value="1">أدمن الكلية</option>				
							<option value="2">أدمن القسم</option>	
							<option value="3" selectedS>أستاذ</option>				
							<option value="4">طالب</option>	
							</select>			

						</div>	
						
                        <div class="form-group" style="float: left"
							<label for="cycle" class="control-label">الطور</label>				
							<select id="cycle" name="cycle" class="form-control">
							<option value="1">ليسانس</option>				
                            <option value="2"> ماستر</option>	
                            <option value="3">ليسانس + ماستر</option>	
							</select>	

						<div style="clear:both;"></div>						                                                
                        <div class="form-group" style="float: left"
							<label for="departement" class="control-label">القسم</label>				
							<select id="departement" name="departement" class="form-control">
                            <option value="0" selected="0">جميع الأقسام</option>				
							<option value="2">علوم</option>	
							<option value="3">تجارة</option>				
							<option value="4">سياسية</option>	
							</select>						
						</div>	                                                
-->			
						</div>
                        
                        <div style="clear:both;">&nbsp;</div>
						<div class="form-group"
							<label for="password" class="control-label">كلمة السر *</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
						</div>											
						
					</div>
					<div class="modal-footer">
						<input type="hidden" name="userId" id="userId" />
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