$(document).ready(function() {        
	
	var typerecoursData = $('#listTypeRecours').DataTable({
		"searching": false,
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"type_recours_action.php",
			type:"POST",
			data:{action:'listTypeRecours'},
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
		var typeRecoursId = $(this).attr("id");
		var action = 'getTypeRecoursDetails';
		//alert(typeRecoursId);
		$.ajax({
			url:'type_recours_action.php',
			method:"POST",
			data:{typeRecoursId:typeRecoursId, action:action},
			dataType:"json",
			success:function(data){
				//alert('AJAX call was successful!');
				//alert('Data from the server' + val(data.dep_desl));
				$('#typeRecoursModal').modal('show');
				$('#typerecoursId').val(data.id);
				$('#ldc_desl').val(data.dep_desl);
				$('#ldc_desl_ar').val(data.dep_desl_ar);
				//$('#status').val(data.status);				
				$('.modal-title').html("<i class='fa fa-plus'></i>تعديل أنواع الطعون ");
				$('#action').val('updateTypeRecours');
				$('#save').val('حفظ');
				//alert('Data:' + val(data.dep_desl);
			}
		})
	});		
	
	$('#addTypeRecours').click(function(){
		//alert('add type rec');
		$('#typerecoursModal').modal('show');
		$('#typerecoursForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> إضافة نوع طعن جديد");
		$('#action').val('addTypeRecours');
		$('#save').val('حفظ');
	});	
		
	$(document).on('submit','#typerecoursForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"type_recours_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#typerecoursForm')[0].reset();
				$('#typerecoursModal').modal('hide');				
				$('#save').attr('disabled', false);
				typerecoursData.ajax.reload();
			}
		})
	});			
			
	$(document).on('click', '.delete', function(){
		var typerecoursId = $(this).attr("id");		
		var action = "deleteTypeRecours";
                //alert(typerecoursId);
		if(confirm("Voulez-vous réellement supprimer ce type recours ?")) {
			$.ajax({
				url:"type_recours_action.php",
				method:"POST",
				data:{typerecoursId:typerecoursId, action:action},
				success:function(data) {					
				typerecoursData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
    
});

