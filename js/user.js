$(document).ready(function() {        
	var userData = $('#listUser').DataTable({
                "paging": true,
		"searching": false,
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"user_action.php",
			type:"POST",
			data:{action:'listUser'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 6, 7],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	

	$(document).on('click', '.update', function(){
		var userId = $(this).attr("id");
		var action = 'getUserDetails';
                $.ajax({
			url:'user_action.php',
			method:"POST",
			data:{userId:userId, action:action},
			dataType:"json",
			success:function(data){
				$('#userModal').modal('show');
				$('#userId').val(data.id);
				$('#username').val(data.username);
				$('#nom').val(data.nom);
				$('#prenom').val(data.prenom);
				$('#nom_ar').val(data.nom_ar);
				$('#prenom_ar').val(data.prenom_ar);
				$('#sexe').val(data.sexe);
				$('#email').val(data.email);
				$('#departement').val(data.dep_id);
				$('#cycle').val(data.cycle);
				$('#user_type').val(data.user_type);
				//$(‘.select-dropdown’).val(data.user_type);
				$('#status').val(data.status);				
				$('.modal-title').html("<i class='fa fa-plus'></i> تعديل المستخدم ");
				$('#action').val('updateUser');
				$('#save').val('حفظ');
			}
		})
	});		
	
	$('#addUser').click(function(){
		$('#userModal').modal('show');
		$('#userForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> إضافة مستخدم جديد");
		$('#action').val('addUser');
		$('#save').val('Save');
	});	
		
	$(document).on('submit','#userForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
                
		$.ajax({
			url:"user_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#userForm')[0].reset();
				$('#userModal').modal('hide');				
				$('#save').attr('disabled', false);
				userData.ajax.reload();
			}
		})
	});			
			
	$(document).on('click', '.delete', function(){
		var userId = $(this).attr("id");		
		var action = "deleteUser";
		if(confirm("Voulez-vous réellement supprimer cet utilisateurv?")) {
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:{userId:userId, action:action},
				success:function(data) {					
					userData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
    
});

