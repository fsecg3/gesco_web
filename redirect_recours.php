<!--  <script src="js/notes.js"></script> -->

<div id="redirectRecoursModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="redirectRecoursForm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Ajouter Recours</h4>
				</div>
				<div class="modal-body">
<!--					<div class="form-group">
						<label for="type_recours" class="control-label">سبـب الطعن</label>
						<select id="type_recours" name="type_recours" class="form-control"">
                                                    <?php $notes->getTypesRecours(); ?>
						</select>
					</div>	-->								
					<div class="form-group">
						<label for="new_departement" class="control-label">الطعن موجه إلى القسم</label>
<!--                                                                                                            <select name="departement" id="departement" class="form-control"> -->
                                                                                                            <select name="new_departement" id="new_departement" class="new_departement" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">

<!--						<select id="departement" name="departement" class="form-control" placeholder="Département..."> -->
                                                                                                            

                                                    <?php $notes->getDepartements();?> 
						</select>						
					</div>
					<div class="form-group">
						<label for="enseignant" class="control-label">الطعن موجه إلى الأستاذ</label>
                                                <select name="new_enseignant" id="new_enseignant" class="new_enseignant"> </select>
<!--						<select id="enseignant" name="enseignant" class="form-control" placeholder="Enseignant...">					
                                                        
                                                            $notes->getEnseignants('');
                                                    
						</select>						-->
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
				<div class="modal-footer">
					<input type="hidden" name="redirectName" id="redirectid" />
                                        <input type="hidden" name="matiereId" id="matiereId" />
					<input type="hidden" name="new_action" id="new_action" value="" />
					<input type="submit" name="new_save" id="new_save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
				</div>
			</div>
		</form>
	</div>


</div>