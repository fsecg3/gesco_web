<!--  <script src="js/notes.js"></script> -->
<?php
include_once 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
$rec_message = "";

if(isset($_GET['rec_id'])) {
    $rec_id = $_GET['rec_id'];
    $uem_ckey = $_GET['uem_ckey'];
    $ctc_code = $_GET['ctc_code'];
    $recours = $notes->get_RecoursDetails($rec_id, $uem_ckey, $ctc_code);
    $rec_message = $recours['rec_message'];
};
?>

<div id="recoursModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="recoursForm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Ajouter Recours</h4>
				</div>

				<div class="modal-body">
					<div class="panel  panel-primary">
  					<div class="panel-heading" id="panel_name">معلومات أساسية عن الطعن</div>
  					<div class="panel-body" style="min-height: 2; max-height: 2;">
<!--					
					  <span class="input-group-addon" style="width: 10%;">Last Name</span>
					  <select id="type_recours_ctc" name="type_recours_ctc" class="form--control"">
                			<?php $notes->getTypesRecours('CTC'); ?>
						</select>
<br>
						<span class="input-group-addon" style="width: 10%;">Last Name</span>
					  <textarea class="form-control" rows="3" id="message" name="message"></textarea>

<input id="lname" type="text" class="form-control input-lg" name="lname" placeholder="Last Name">
-->
  					<div class="form-group">
						<label for="type_recours" class="control-label">سبـب الطعن</label>
						<select id="type_recours" name="type_recours" class="form--control"">
                			<?php $notes->getTypesRecours('CTC'); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="departement" class="control-label">الطعن موجه الى قسم</label>
						<select id="departement" name="departement" class="form--control">
							<?php $notes->getDepartements();?> 
						</select>						
					</div>	
					<div class="form-group">
						<label for="enseignant" class="control-label">الطعن موجه الى الأستاذ</label>
            			<select name="enseignant" id="enseignant" class="form--control"> 
<!--                                                            <?php $notes->getEnseignants($uem_ckey);?> -->
                                            </select>
					</div>																	
																
  				</div>
				<div class="panel-heading">معلومات إضافية عن الطعن</div>
  				<div class="panel-body">
				  <div class="form-group">
<!--						<label for="message" class="control-label">معلومات إضافية عن الطعن</label>--
                                                                            <textarea class="form-control" rows="3" id="message" name="message"></textarea>-->
                                                                            <?php if($_SESSION['DB'] == 'FSIC_WEB'): ?> 
                                                                                <textarea class="form-control" rows="3" id="message" name="message" readonly></textarea>
                                                                            <?php endif; ?> 
                                                                             <?php if($_SESSION['DB'] != 'FSIC_WEB'): ?>    
                                                                                <textarea class="form-control" rows="3" id="message" name="message"></textarea>
                                                                            
                                                                            <?php endif; ?>
                                                                            
					</div>	

				  <div class="form-group">
						
						<?php if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] != "4") { ?>
							<label class="radio-inline">
								<input type="radio" name="status" id="close" value="1" required>مغلق
							</label>
                                                        <label for="status" class="control-label">حالة الطعن</label>
                                                                <label class="radio-inline">
                                                                <input type="radio" name="status" id="open" value="0" checked required>مفتوح     
                                                        </label>
						<?php } ?>	
					</div>

  			</div>
		</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="recoursId" id="recoursId" />
                    <input type="hidden" name="matiereId" id="matiereId" />
					<input type="hidden" name="ctcId" id="ctcId" />
					<input type="hidden" name="action" id="action" value="" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
				</div>
			</div>
		</form>
	</div>


</div>
