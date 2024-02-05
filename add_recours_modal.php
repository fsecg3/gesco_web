<!--  <script src="js/notes.js"></script> -->

<div id="recoursModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="recoursForm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Ajouter Recours</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="type_recours" class="control-label">سبـب الطعن</label>
						<select id="type_recours" name="type_recours" class="form-control"">
                                                    <?php $notes->getTypesRecours('CTC'); ?>
						</select>
					</div>									
					<div class="form-group">
						<label for="departement" class="control-label">الطعن موجه إلى مركز</label>

<!--						<label for="departement" class="control-label">الطعن موجه إلى القسم</label>
--                                                                                                            <select name="departement" id="departement" class="form-control"> -->
                                                                                                            <select name="departement" id="departement" class="departement" style="width: 40%; padding: 0.375rem 0.75rem;    border-color: #ced4da;">

<!--						<select id="departement" name="departement" class="form-control" placeholder="Département..."> -->
                                                                                                            

                                                    <?php $notes->getDepartements();?> 
						</select>						
					</div>
					<div class="form-group">
						<label for="enseignant" class="control-label">الطعن موجه إلى الأستاذ</label>
                                                <select name="enseignant" id="enseignant" class="enseignant"> </select>
						<select id="enseignant" name="enseignant" class="form-control" placeholder="Enseignant...">					
                                                        
                                                            $notes->getEnseignants('');
                                                    
						</select>					
					</div>																	
					<div class="form-group">
						<label for="message" class="control-label">معلومات إضافية عن الطعن</label>							
						<textarea class="form-control" rows="3" id="message" name="message"></textarea>
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
					<input type="hidden" name="recoursId" id="recoursId" />
                                        <input type="hidden" name="matiereId" id="matiereId" />
					<input type="hidden" name="action" id="action" value="" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
				</div>
			</div>
		</form>
	</div>


</div>