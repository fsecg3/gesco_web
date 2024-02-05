
$(document).ready(function() {
    
    $(document).on('submit','#recoursReply', function(event){        
		event.preventDefault();
        //alert('réponse au recours');
		$('#reply').attr('disabled','disabled');
		var formData = $(this).serialize();
		
		$.ajax({
			url:"recours_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#recoursReply')[0].reset();
				$('#reply').attr('disabled', false);
				////////////////////location.reload();
			}
		})
	});
        
	$('#createRecours1').click(function(){
		$('#recoursModal').modal('show');
		$('#recoursForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> إضافة طعن جديد");
		$('#action').val('createRecours');
		$('#save').val('Save Recours');
	});	

	$(document).on('click', '.redirect_recours', function(){
		var recoursId = $(this).attr("id");
		var matiereId = $(this).attr("value");
		var action = 'getRedirectRecoursDetails';
		//alert('Rediriger ' + recoursId);
		$.ajax({
			url:'notes_action.php',
			method:"POST",
			data:{recoursId:recoursId, matiereId:matiereId, action:action},
			dataType:"json",
			success:function(data){
                                                            //alert('redirect:' + data.id) ;
				//alert(val(data.uem_ckey));
				$('#redirectRecoursModal').modal('show');
				$('#redirectid').val(data.id);
	//	$('#matiereId').val(matiereId); //data.uem_ckey);
                //$('#type_recours').val(data.rec_type);
				$('#new_departement').val(data.eta_code);
				$('#new_enseignant').val(data.matricule);
				//$('#message').val(data.rec_message);
				$('#status').val(data.resolved);
				//$('#uem_ckey').val(matiereId); //data.uem_ckey);
				$('.modal-title').html("<i class='fa fa-plus'></i> تحويل الطعن");
				$('#new_action').val('updateRedirectRecours');
				$('#new_save').val('حفظ');
				//alert('Data:' + val(data.dep_desl);
			}
		})
	});		

	if($('#listRecours').length) {
		var recoursData = $('#listRecours').DataTable({
            "lengthChange": true,
			"processing":true,
			"serverSide":true,
			"order":[],
                        /*buttons: ['Excel', 'Pdf' ],*/

			"ajax":{
				url:"recours_action.php",
				type:"POST",
				data:{action:'listRecours'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0, 6, 7, 8, 9],
					"orderable":false,
				},
			],
			"pageLength": 10
		});
                
                /*table.buttons().container().appendTo( '#example_wrapper .col-md-6:eq(0)' );*/
                
		$(document).on('submit','#redirectRecoursForm', function(event){
                                                    event.preventDefault();
                                                    //alert(document.getElementsByName("redirectid")[0].value);
                                                    //alert('id = ' + ocument.getElementById("redirectid").value);
                                                     var e = document.getElementById("redirectid");
                                                      if (e !== null) { recoursid = e.value;}
                                                      // recoursId = $(this).attr("redirectid");
                                                      var e = document.getElementById("new_departement");
                                                      if (e !== null) { departement = e.value;}
                                                     var e = document.getElementById("new_enseignant");
                                                      if (e !== null) { enseignant = e.value;}
                                                      
                                                       //alert('save: + recoursid');
                                                      //alert('save:' + recoursid + ':' + departement + ':' + enseignant);
                                                      var action = 'updateRedirectRecours';
			
			$('#new_save').attr('disabled','disabled');
			//var formData = $(this).serialize();
                                                      
                                                        
			$.ajax({
				url:"notes_action.php",
				method:"POST",
				//data:formData,
                                                                        data:{recoursid:recoursid, departement:departement, enseignant:enseignant, action:action},
                                                                       // dataType:"json",
				success:function(data){				
					$('#redirectRecoursForm')[0].reset();
					$('#redirectRecoursModal').modal('hide');				
					$('#new_save').attr('disabled', false);
					recoursData.ajax.reload();
				}
			})
		});
                
	$(document).on('click', '.update', function(){
		var recoursId = $(this).attr("id");
		var matiereId = $(this).attr("value");
		var action = 'getRecoursDetails';
		throw new Error("fonctionnalité en préparation!");
		//alert('update recours');
		$.ajax({
			url:'notes_action.php',
			method:"POST",
			data:{recoursId:recoursId, matiereId:matiereId, action:action},
			dataType:"json",
			success:function(data){
				//alert('AJAX call was successful!');
				//alert('Data from the server' + val(data.dep_desl));
				$('#recoursModal').modal('show');
				$('#recoursId').val(data.id);
				$('#matiere').val(matiereId);
				$('#departement').val(data.dep_id);
				$('#enseignant').val(data.ens_id);
				$('#message').val(data.rec_message);
				$('#status').val(data.resolved);
				$('#uem_ckey').val(data.uem_ckey);
				$('.modal-title').html("<i class='fa fa-plus'></i> تعديل الطعن");
				$('#action').val('updateRecours');
				$('#save').val('حفظ');
				//alert('Data:' + val(data.dep_desl);
			}
		})
	});		

                $(document).on('click', '.read_recours', function(){
		var recoursId = $(this).attr("id");
		var matiereId = $(this).attr("value");
		var action = 'getRecoursDetails';
		throw new Error("fonctionnalité en préparation!");
		//alert('update recours');
		$.ajax({
			url:'notes_action.php',
			method:"POST",
			data:{recoursId:recoursId, matiereId:matiereId, action:action},
			dataType:"json",
			success:function(data){
				//alert('AJAX call was successful!');
				//alert('Data from the server' + val(data.dep_desl));
				$('#recoursModal').modal('show');
				$('#recoursId').val(data.id);
				$('#matiere').val(matiereId);
				$('#departement').val(data.dep_id);
				$('#enseignant').val(data.ens_id);
				$('#message').val(data.rec_message);
				$('#status').val(data.resolved);
				$('#uem_ckey').val(data.uem_ckey);
				$('.modal-title').html("<i class='fa fa-plus'></i> تعديل الطعن");
				$('#action').val('updateRecours');
				$('#save').val('حفظ');
				//alert('Data:' + val(data.dep_desl);
			}
		})
	});		

            $(document).on('click', '.closeRecours', function(){
			var recoursId = $(this).attr("id");		
			var action = "closeRecours";
			//throw new Error("fonctionnalité en préparation!");
			if(confirm("Voulez-vous réellement cloturer ce recours?")) {
				
				$.ajax({
					url:"recours_action.php",
					method:"POST",
					data:{recoursId:recoursId, action:action},
					success:function(data) {					
						recoursData.ajax.reload();
					}
				})
			} else {
				return false;
			}
		});	
                
            $(document).on('click', '.delete_recours', function(){
		var recoursId = $(this).attr("id");		
                                    //alert('Supprimer recours:' + recoursId);
		var action = "deleteRecours";
		if(confirm("Voulez-vous réellement supprimer ce recours ?")) {
			$.ajax({
				url:"notes_action.php",
				method:"POST",
				data:{recoursId:recoursId, action:action},
				success:function(data) {					
					recoursData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	

    }
    
    
    $(".new_departement").change(function()
        {
                
                var myDropDownList = $(".enseignant");
                var eta_code=$(this).val();
                //alert(eta_code);
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_EnseignantRecours';
                $.ajax({
                    url: "notes_action.php",
                    type: "POST",
                    data:{eta_code:eta_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_matricule_ens = "";
                        var j = 0;
                        $("#new_enseignant").empty();
                        if(len >= 0){
                            $("#new_enseignant").append("<option value='"+'0'+"'>"+'غير محدد'+"</option>");
                        }
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_matricule_ens = response[i]['matricule_ens'];}
                            
                            $("#new_enseignant").append("<option value='"+response[i]['matricule_ens']+"'>"+response[i]['nom_prenom']+"</option>");
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".new_enseignant").val(first_matricule_ens);
                         $(".new_enseignant").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

    $("#type_recours").change(function()
        {
                
                var type_recours=$(this).val();
                //alert(type_recours);
                var action = 'refreshRecours';
                $.ajax({
                    url: "recours_action.php",
                    type: "POST",
                    data:{type_recours:type_recours, action:action},
                    cache: false,
                    success: function (data) {
                        recoursData.ajax.reload();                        
                        //alert(data);
                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

    $("#cycle_recours").change(function()
        {
                
                var cycle=$(this).val();
                alert(cycle);
                var action = 'refreshRecours_Cycle';
                $.ajax({
                    url: "recours_action.php",
                    type: "POST",
                    data:{cycle:cycle, action:action},
                    cache: false,
                    success: function (data) {
                       recoursData.ajax.reload();
                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

});

