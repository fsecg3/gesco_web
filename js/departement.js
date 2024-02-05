$(document).ready(function() {        
	
	var departementData = $('#listDepartement').DataTable({
		"searching": false,
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"departement_action.php",
			type:"POST",
			data:{action:'listDepartement'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	

	$(document).on('click', '.update', function(){
		var departementId = $(this).attr("id");
		var action = 'getDepartementDetails';
		
		$.ajax({
			url:'departement_action.php',
			method:"POST",
			data:{departementId:departementId, action:action},
			dataType:"json",
			success:function(data){
				//alert('AJAX call was successful!');
				//alert('Data from the server' + val(data.dep_desl));
				$('#departementModal').modal('show');
				$('#departementId').val(data.id);
				$('#dep_desl').val(data.dep_desl);
				$('#dep_desl_ar').val(data.dep_desl_ar);
				$('#status').val(data.status);				
				$('.modal-title').html("<i class='fa fa-plus'></i> تعديل القسم");
				$('#action').val('updateDepartement');
				$('#save').val('Save');
				//alert('Data:' + val(data.dep_desl);
			}
		})
	});		
	
	$('#addDepartement').click(function(){
		alert('add dep');
		$('#departementModal').modal('show');
		$('#departementForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> إضافة قسم جديد");
		$('#action').val('addDepartement');
		$('#save').val('Save');
	});	
		
	$(document).on('submit','#departementForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"departement_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#departementForm')[0].reset();
				$('#departementModal').modal('hide');				
				$('#save').attr('disabled', false);
				departementData.ajax.reload();
			}
		})
	});			
			
	$(document).on('click', '.delete', function(){
		var departementId = $(this).attr("id");		
		var action = "deleteDepartement";
		if(confirm("Voulez-vous réellement supprimer ce département ?")) {
			$.ajax({
				url:"departement_action.php",
				method:"POST",
				data:{departementId:departementId, action:action},
				success:function(data) {					
					departementData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
    
});

