<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: authenticate.php");	
}
include('inc/header.php');
$recoursDetails = $recours->recoursInfo($_GET['id']);
$recoursReplies = $recours->getrecoursReplies($recoursDetails['id']);
$user = $users->getUserInfo();
$recours->updaterecoursReadStatus($recoursDetails['id']);
?>	
<title>Gesco Portail Web 2023</title>
<script src="js/general.js"></script>
<script src="js/recours.js"></script>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<div class="container">
	<div class="row home-sections">
<!--	<h2>البوابة الرقمية لكلية العلوم الإجتماعية والإنسانية - الرد على الطعون</h2>	-->
	<?php include('menus.php'); ?>		
	</div> 
	
	<section class="comment-list">          
		<article class="row">            
			<div class="col-md-10 col-sm-10">
				<div class="panel panel-default arrow left">
					<div class="panel-heading right">
					<?php if($recoursDetails['resolved']) { ?>
					<button type="button" class="btn btn-danger btn-sm">
					  <span class="glyphicon glyphicon-eye-close"></span> مغلق
					</button>
					<?php } else { ?>
					<button type="button" class="btn btn-success btn-sm">
					  <span class="glyphicon glyphicon-eye-open"></span> مفتوح
					</button>
					<?php } ?>
					<span class="recours-title"><?php echo $recoursDetails['title']; ?></span>
             					

					</div>
                                    			<div class="panel-heading right">
						<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="22-05-2022 01:05"><i class="fa fa-clock-o"></i> <?php echo $time->ago($recoursDetails['date_recours']); ?></time>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> <?php echo $recoursDetails['creater']; ?>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-briefcase"></span> <?php echo $recoursDetails['department']; ?>
					</div>

					<div class="panel-body">						
						<div class="comment-post">
						<p>
						<?php echo $recoursDetails['message']; ?>
						</p>
						</div>                 
                                                                                           </div>
                                    </article>
                                    <!--
					<div class="panel-heading right">
						<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="22-05-2022 01:05"><i class="fa fa-clock-o"></i> <?php echo $time->ago($recoursDetails['date_recours']); ?></time>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> <?php echo $recoursDetails['creater']; ?>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-briefcase"></span> <?php echo $recoursDetails['department']; ?>
					</div>
                                    
--                                    		<article class="row">            
                                                                                                   <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0"> 
<tr>
<td><label for="old_note">العلامة الحالية:</label></td>
<td><input name="old_note" id="old_note" type="text" style="width:80px"/></td>
<td><label for="new_note">العلامة الجديدة بعد الطعن:</label></td> <td> 
<input name="new_note" id="new_note" type="text" style="width:80px"/></td> 
</tr>
---
<tr> 
<td colspan="2"><label for="Email">Email:</label></td> 
<td colspan="2"><input name="email" id="Email" type="email" /></td> 
</tr> 
<tr> 
<td colspan="4"><input type="submit" value="Submit"/></td> 
</tr> --
</table> --

                                                                                                   
                                                                                                   
                                                                                                    &nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> <?php echo $recoursDetails['creater']; ?>
                                                                                                    &nbsp;&nbsp;<span class="glyphicon glyphicon-briefcase"></span> <?php echo $recoursDetails['department']; ?>
                                                                                                    &nbsp;&nbsp;<span class="input-field"></span> <?php echo $recoursDetails['department']; ?>
                                                                                                                                   
					</div>
                                    
				</div>			 
			</div>
		</article>		-->
		
		<?php foreach ($recoursReplies as $replies) { ?>		
			<article class="row">
                            
				<div class="col-md-10 col-sm-10">
					<div class="panel panel-default arrow right">
						<div class="panel-heading">
							<?php if($replies['session_user_type'] == '1') { ?>
								<span class="glyphicon glyphicon-user"></span> <?php echo $replies['creater']; ?>
							<?php } else { ?>
								<span class="glyphicon glyphicon-user"></span> <?php echo $replies['creater']; ?>
							<?php } ?>
                                                        
						</div>

						<div class="panel-body">						
							<div class="comment-post">
							<p>
							<?php echo $replies['message']; ?>
							</p>
                                                        
                                                                                                                              <?php if(isset($replies['rep_note'])) { ?>
                                                                                                                                    
                                                                                                                                    <p style="color:red">
                                                                                                                                    
                                                                                                                                        <?php if($_SESSION['langue'] == 'AR') { 
                                                                                                                                            echo 'العلامة الجديدة بعد الطعن: '.$replies['rep_note'];?>
                                                                                                                                        <?php } else { 
                                                                                                                                            echo 'Note après recours: '.$replies['rep_note'];
                                                                                                                                            ?>
                                                                                                                                        <?php } ?>
                                                                                                                                    </p>
                                                                                                                              <?php } ?>
							</div>                  
						</div>

					</div>
				</div>            
			</article> 		
		<?php } ?>
		
		<form method="post" id="recoursReply">
	<article class="row">            
			<div class="col-md-10 col-sm-10">
                  <?php if($recoursDetails['resolved'] <> '1' && $recoursDetails['session_user_type'] <= '3') { ?>
                            
				<div class="panel panel-default arrow left">   
                                                                                                 	<div class="panel-heading right">
                                                                                                   <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0"> 
<tr>
<?php if($_SESSION['langue'] == 'AR') { ?>
    <td><label for="old_note">العلامة الحالية:</label></td>
<?php } else { ?>
    <td><label for="old_note">Ancienne Note:</label></td>
     
<?php } ?>
       
<td><input name="old_note" id="old_note" type="text" style="width:80px" readonly/></td>


<?php if($_SESSION['langue'] == 'AR') { ?>
    <td><label for="new_note">العلامة الجديدة بعد الطعن:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ------------------></label></td> <td> 
<?php } else { ?>
    <td><label for="old_note">Note Après Recours ----->:</label></td> <td>
<?php } ?>

<input name="new_note" id="new_note" type="text" style="width:80px; color:red"/></td> 
</tr>
<!---
<tr> 
<td colspan="2"><label for="Email">Email:</label></td> 
<td colspan="2"><input name="email" id="Email" type="email" /></td> 
</tr> 
<tr> 
<td colspan="4"><input type="submit" value="Submit"/></td> 
</tr> -->
</table>
                                              
                                </div>
                        </div>
               <?php } ?>                            
        </article>
                        <?php if($recoursDetails['resolved'] <> '1' && $recoursDetails['session_user_type'] <= '3') { ?>
			<article class="row">
				<div class="col-md-10 col-sm-10">				
					<div class="form-group">							
						<textarea class="form-control" rows="5" id="message" name="message" placeholder="أدخل ردك/جوابك..." ></textarea>	
					</div>				
			</div>
			</article>  
                        <?php } ?>
			<article class="row">
				<div class="col-md-10 col-sm-10">
					<div class="form-group">							
                                                <?php if($recoursDetails['resolved'] <> '1' && $recoursDetails['session_user_type'] <= '3') { ?>
						<input type="submit" name="reply" id="reply" class="btn btn-success" value="الرد" />
                                                <?php } ?>
                                                <a href="recours.php"><input type="button" name="exit" id="exit" class="btn btn-danger btn-xs update" value="خروج"></a>

                                        </div>
				</div>
			</article> 
			<input type="hidden" name="recoursId" id="recoursId" value="<?php echo $recoursDetails['id']; ?>" />	
			<input type="hidden" name="action" id="action" value="saveRecoursReplies" />			
		</form>
<!--	</section>	-->
	<!-- 
            <?php include('add_recours_modal.php'); ?> 
        -->
</div>
<?php include('inc/footer.php');?>