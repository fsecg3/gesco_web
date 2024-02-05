
<?php 
include 'init.php';
if($users->isLoggedIn()) {
	header('Location: recours.php');
}
$errorMessage = $users->login();
include('inc/header.php');
?>
<title>Gesco Portail Web (2023)</title>
<?php include('inc/container.php');?>
<div class="container contact">	
	<style>
	h3 {text-align: center;}
	</style>'	
<!--        
	<h3>Ecole Nationale Supérieure de Technologie</h3>		
	<h3>Direction des Etudes de Graduation et des Diplômes</h3>	

                    <h3>جامعة الجيلالي بونعامة - خميس مليانة</h3>		
	<h3>مرحبا بكم في البوابة الرقمية لكلية العلوم الإجتماعية والإنسانية</h3>	
    
		
	<h3>جامعة الجزائر 3</h3>		
	<h3>مرحبا بكم في البوابة الرقمية لكلية العلوم الإقتصادية و العلوم التجارية و علوم التسيير </h3>	
-->
<h3>
<?php $site->GetSiteEtablissement(); ?>
</h3>

<h3>
<?php $site->GetSiteLoginMessage(); ?>
</h3>
	<div class="col-md-6">                    
		<div class="panel panel-info">
			<div class="panel-heading" style="background:#306EFF;color:white;">
				<div class="panel-title">الدخول الى الأرضية</div>                        
			</div> 
			<div style="padding-top:30px" class="panel-body" >
				<?php if ($errorMessage != '') { ?>
					<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $errorMessage; ?></div>                            
				<?php } ?>
				<form id="loginform" class="form-horizontal" role="form" method="POST" action="">                                    
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" id="username" name="username" placeholder="Utilisateur" style="background:white;" required>                                        
					</div>                                
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password"placeholder="Mot de passe" required>
					</div>
					<div style="margin-top:10px" class="form-group">                               
						<div class="col-sm-12 controls">
						  <input type="submit" name="login" value="Login" class="btn btn-success">
						</div>						
					</div>	
					<div style="margin-top:10px" class="form-group">                               
						<div class="col-sm-12 controls">
						إسم المستخدم: رقم التسجيل بالنسبة للطلبة<br>
						كلمة السر: تاريخ الميلاد بالنسبة للطلبة على شكل (1990-01-25 مثلا) jj-mm-aaaa<br><br>
						
						</div>						
					</div>	
				</form>   
			</div>                     
		</div>  
	</div>
</div>	
<?php include('inc/footer.php');?>