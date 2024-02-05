<style>
	h3 {text-align: center;}
	nav .navbar-nav li a{
  color: #009150 !important;
  font-size: 18px;
  }

  navbar-inverse {
background-color:red;
     }
</style>'	
<?php
   $etudiant = $users->getEtudiantInscription();
   $fiche_voeux = false;
   if($_SESSION['DB'] == 'UFC_WEB'){
   $fiche_voeux = (($etudiant['par_code'] == '1010' || $etudiant['par_code'] == '1021') && $etudiant['ann_code'] == '2')? true: false;
   }
   
   if($_SESSION['DB'] == 'FSPRI_WEB'){
   $fiche_voeux = ($etudiant['par_code'] == '051' && $etudiant['ann_code'] == '2')? true: false;
   }
   
?>

<div class="row home-sections">
    <h3><?php $site->GetSiteEtablissement(); ?> </h3>
    <h3><?php $site->getSitePageMessage(); ?> </h3>
</div>

<nav class="navbar navbar" style="background:aqua;color:black;">
<!-- <nav class="navbar navbar" style="background-color:mintcream;color: red;font-weight:bold;"> -->
<!-- <nav class="navbar navbar-dark bg-primary" style="background:#306EFF;font-color:white;font-weight:bold;"> -->
	<div class="container-fluid">
		<ul class="nav navbar-nav  navbar-right menus a">
			<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == '1') { ?>
				<li id="departement"><a href="departement.php" >الأقسام</a></li>
				<li id="user"><a href="user.php" >المستخدمون</a></li>				
				<li id="recours"><a href="recours.php" >الطعون</a></li>
                                
			<?php } ?>

			<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == '2') { ?>
				<li id="recours"><a href="recours.php" >الطعون</a></li>
				<li id="saisie_notes"><a href="saisie_notes.php" >رصد العلامات</a></li>
			<?php } ?>

			<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == '3') { ?>
				<li id="recours"><a href="recours.php" >الطعون</a></li>
				<li id="saisie_notes"><a href="saisie_notes.php" >رصد العلامات</a></li>
			<?php } ?>
			
			<?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == "4"){?>
                        <?php if($fiche_voeux === true){?>
                            <li id="fiche_voeux"><a href="fiche_voeux.php">إختيار التخصص</a></li>
                        <?php } ?>
<!--				        <li id="recours"><a href="view_pwd.php" >الأرضية</a></li> -->
                        <li id="recours"><a href="recours.php" >الطعون</a></li>
                        <li id="notes"><a href="notes.php" >النتائج</a></li>
			<?php } ?>	

		</ul>
            
            	<?php if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"] <= '2'))  { ?>
                    <ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img <?php echo md5('$user[userid]'); ?>?s=100" width="20px">&nbsp;<?php { echo 'إحصائيات'; } ?></a>
				<ul class="dropdown-menu">					
                    <li><a href="stats_saisie_notes.php">التسجيلات</a></li>
                    <li><a href="stats_saisie_notes.php">رصد العلامات</a></li>
                    <li><a href="stats_saisie_notes.php">نتائج المداولات</a></li>
				</ul>
			</li>
                    </ul>
                    <?php } ?>  
            
                    <?php if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"] <= '1'))  { ?>
                    <ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img <?php echo md5('$user[email]'); ?>?s=100" width="20px">&nbsp;<?php { echo 'الإعدادات'; } ?></a>
				<ul class="dropdown-menu">					
                                    <li><a href="type_recours.php">أنواع الطعون</a></li>
                                    <li><a href="logout.php">أنواع الردود على الطعون</a></li>
				</ul>
			</li>
                    </ul>
                    <?php } ?>

		<ul class="nav navbar-nav navbar-left">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img src="//gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=100" width="20px">&nbsp;<?php if(isset($_SESSION["userid"])) { echo $user['nom_ar']."  ".$user['prenom_ar']; } ?></a>
				<ul class="dropdown-menu">					
					<li><a href="logout.php">خروج</a></li>
					<li><a href="change_pwd.php">تغيير كلمة السر</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>