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
   $etudiant = $users->GetEtudiantInscription();
   $fiche_voeux = (($etudiant['par_code'] == '1010' || $etudiant['par_code'] == '1021') && $etudiant['ann_code'] == '2')? true: false;
?>

<div class="row home-sections">
    <h3><?php $site->GetSiteEtablissement(); ?> </h3>
    <h3><?php $site->getSitePageMessage(); ?> </h3>
</div>

<nav class="navbar navbar" style="background:aqua;color:black;">
<!-- <nav class="navbar navbar" style="background-color:mintcream;color: red;font-weight:bold;"> -->
<!-- <nav class="navbar navbar-dark bg-primary" style="background:#306EFF;font-color:white;font-weight:bold;"> -->
	<div class="container-fluid">
		<ul class="nav navbar-nav  navbar-left menus a">
			<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == '1') { ?>
				<li id="departement"><a href="departement.php" >Départements</a></li>
				<li id="user"><a href="user.php" >Utilisateurs</a></li>				
				<li id="recours"><a href="recours.php" >Recours</a></li>
                                
			<?php } ?>

			<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == '2') { ?>
                                                                         <li id="saisie_notes"><a href="saisie_notes.php" >Saisie des Notes</a></li>*
				<li id="recours"><a href="recours.php" >Recours</a></li>
			<?php } ?>

			<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == '3') { ?>
				<li id="saisie_notes"><a href="saisie_notes.php" >Saisie des Notes</a></li>
                                                                        <li id="recours"><a href="recours.php" >Recours</a></li>
			<?php } ?>
			
			<?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == "4"){?>
                                                                   <li id="notes"><a href="notes.php" >Résultats</a></li>
                                                                  <li id="recours"><a href="recours.php" >Recours</a></li>
                                                                  <?php if($fiche_voeux === true){?>
                                                                        <li id="fiche_voeux"><a href="fiche_voeux.php">Fiche de Voeux</a></li>
                                                                 <?php } ?>

			<?php } ?>	

		</ul>
            
            	<?php if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"] <= '2'))  { ?>
                    <ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img <?php echo md5('$user[userid]'); ?>?s=100" width="20px">&nbsp;<?php { echo 'Statistiques'; } ?></a>
				<ul class="dropdown-menu">					
                                    <li><a href="stats_saisie_notes.php">Inscriptions</a></li>
                                    <li><a href="stats_saisie_notes.php">Saisie des Notes</a></li>
                                    <li><a href="stats_saisie_notes.php">Délibérations</a></li>
				</ul>
			</li>
                    </ul>
                    <?php } ?>  
            
                    <?php if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"] <= '1'))  { ?>
                    <ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img <?php echo md5('$user[email]'); ?>?s=100" width="20px">&nbsp;<?php { echo 'Paramétrage'; } ?></a>
				<ul class="dropdown-menu">					
                                    <li><a href="type_recours.php">Types Recours</a></li>
                                    <li><a href="logout.php">Types Réponses Recours</a></li>
				</ul>
			</li>
                    </ul>
                    <?php } ?>

		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> 
				<img src="//gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=100" width="20px">&nbsp;<?php if(isset($_SESSION["userid"])) { echo $user['nom']."  ".$user['prenom']; } ?></a>
				<ul class="dropdown-menu">					
					<li><a href="logout.php">Quitter</a></li>
					<li><a href="change_pwd.php">Changer Mot de Passe</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>