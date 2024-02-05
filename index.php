
<?php 
include 'init.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo phpinfo();
//die; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
} else {
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
<script src="js/recours.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<div class="container">	
	<div class="row home-sections">
	<h2>نظام لتسجيل الطعون</h2>	
	<?php include('menus.php'); ?>		
	</div> 
	<div class="">   		
		<p>>aaaa Consultez vos notes & résultats, adressez vos recours directement à l'administration.</p>	

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-10">
					<h3 class="panel-title"></h3>
				</div>
				<div class="col-md-2" align="right">
					<button type="button" name="add" id="createRecours" class="btn btn-success btn-xs">إضافة طعن جديد</button>
				</div>
			</div>
		</div>
		<table id="listRecours" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>الرقم</th>
					<th>عنوان الطعن</th>
					<th>القسم</th>
					<th>الأستاذ</th>
					<th>حرر من طرف</th>					
					<th>حرر في</th>	
					<th>aaaa</th>
                    <th>الحالة</th>

					<th>a</th>
					<th>b</th>
					<th>c</th>					
				</tr>
			</thead>
		</table>
	</div>
	<?php include('add_recours_model.php'); ?>
</div>	
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      </div>
      </div>
    </div>
  </div>


<?php include('inc/footer.php');?>
<script>
    window.uni_modal = function($title = '' , $url='',$size=""){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                if($size != ''){
                    $('#uni_modal .modal-dialog').addClass($size)
                }else{
                    $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                }
                $('#uni_modal').modal({
                  show:true,
                  backdrop:'static',
                  keyboard:false,
                  focus:true
                })
                end_load()
            }
        }
    })
</script>