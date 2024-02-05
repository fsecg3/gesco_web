$(document).ready(function() {        
	
	var notesData = $('#listNotes').DataTable({
		fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			//$('td', nRow).css('background-color', 'Red');
			if (aData[0] == "UEM") {
				$('td', nRow).css('background-color', 'LightBlue');
				$('td', nRow).css('color', 'Red');
			} else if(aData[0] == "MAT"){
				$('td', nRow).css('background-color', 'White');			
			} else if(aData[0] == "SEM"){
				$('td', nRow).css('background-color', 'navy');
				$('td', nRow).css('color', 'White');
			} else if(aData[0] == "ANN"){
				$('td', nRow).css('background-color', 'Red');
				$('td', nRow).css('color', 'White');
			}
		},
                                    "info": false,
		"paging": true,
		"searching": false,
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"notes_action.php",
			type:"POST",
			data:{action:'listNotes'},
			dataType:"json"
		},
		"columnDefs":[
			{
				//"targets": [0], visible: false,
				"targets": 1, width: "1px",
				"targets": 2, width: "150px",
				//"targets":[0,1, 2, 3, 4],
				"orderable":false,
			},
		],
		//"columns": [
		//		{ "data": "nord" },
		//		{ "data": "name" },
		//		{ "data": "email" },
		//		{ "data": "phone" },
		//		{ "data": "country_id" },
		//		{ "data": "created_at" },
		//		{ "data": "action" },
		//	],			
		//"createdRow": function( row, data, dataIndex ) {
		//		if ( data["uem_type"] == "UEM" ) {
		//			$( row ).css( "background-color", "green" );
		//		}else{
		//			$( row ).css( "background-color", "orange" );
		//		}
		//	},
			
		//]//,
		//"pageLength": 20

		//fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		//	if (aData.uem_type == "UEM") {
		//		$('td', nRow).css('background-color', '#D2D2D2');
		//	} else {
		//		$('td', nRow).css('background-color', 'Orange');
		//	}
		//}				
	});	

	$(document).on('click', '.update', function(){
		var recoursId = $(this).attr("id");
		var matiereId = $(this).attr("value");
		var action = 'getRecoursDetails';
		///////alert(matiereId);
		$.ajax({
			url:'notes_action.php',
			method:"POST",
			data:{recoursId:recoursId, matiereId:matiereId, action:action},
			dataType:"json",
			success:function(data){
				//alert(val(data.uem_ckey));
				$('#recoursModal').modal('show');
				$('#recoursId').val(data.id);
		$('#matiereId').val(matiereId); //data.uem_ckey);
                $('#type_recours').val(data.rec_type);
				$('#departement').val(data.eta_code);
				$('#enseignant').val(data.matricule_ens);
				$('#message').val(data.rec_message);
				$('#status').val(data.resolved);
				//$('#uem_ckey').val(matiereId); //data.uem_ckey);
				$('.modal-title').html("<i class='fa fa-plus'></i> تعديل الطعن");
				$('#action').val('updateRecours');
				$('#save').val('حفظ');
				//alert('Data:' + val(data.dep_desl);
			}
		})
	});		
	
	//$('addRecours').click(function(){
	$(document).on('click', '.addRecours', function(){
                                    var rec_id = $(this).attr("rec_id");
                                    var uem_ckey = $(this).attr("uem_id");
		var ctc_code = $(this).attr("ctc_id");
                                    if (ctc_code == "RAT"){
                                        rec_desl = "<i class='fa fa-plus'></i> تقديم طعن في علامة الإستدراكي";
                                    }else if (ctc_code == "EXA"){
                                        rec_desl = "<i class='fa fa-plus'></i> تقديم طعن في علامة الإمتحان";
                                    }else if (ctc_code == "CC1"){
                                        rec_desl = "<i class='fa fa-plus'></i> تقديم طعن في علامة الأعمال الموجهة";
                                    }else if (ctc_code == "CC2"){
                                        rec_desl = "<i class='fa fa-plus'></i>تقديم طعن في علامة الأعمال التطبيقية";
                                    }else if (ctc_code == "RAT"){
                                        rec_desl = "<i class='fa fa-plus'></i> تقديم طعن في علامة الإستدراكي";
                                    }

                                    //var message = 'voici le message';
                                    
                                    //var uem_desl = $(this).attr("value");
                                    var action  = "getRecoursDetails"; //createRecours";
                 
		//////////////alert(rec_id + '/' + uem_ckey + '/' + ctc_code);
                                    //////$("#recoursModal").modal('show');
                                    $.ajax({
                                        url: "notes_action.php",
                                        //////////////url:"add_recours.php?rec_id=" + rec_id + "&&uem_ckey=" + uem_ckey + "&&ctc_code=" + ctc_code,
                                        method:"POST",
                                        //data:formData,
                                        data:{rec_id:rec_id, uem_ckey:uem_ckey, ctc_code:ctc_code, action:action},
                                        success:function(data){
                                            var rec_type = '1';
                                            var eta_code = '0';
                                            var matricule_ens = '000';
                                            var message = '';
                                            //if(data != '[][]'){
                                            //$.each( data, function( key, value ) {
                                              //  alert(key + ':' + value); 
                                            //$( '#' + key ).val( value ); 
                                            //});}
                                        //alert(data);
                                        //   var result = JSON.parse(data);
		   //alert(data);
                                       //var message = 'data[0].rec_message';
                                       //alert(message + data.rec_message); // + data[0].rec_message);
                                    $('#recoursModal').modal('show');
		$('#recoursForm')[0].reset();
		$('.modal-title').html(rec_desl); //"<i class='fa fa-plus'></i> تقديم طعن في علامة الأعمال الموجهة");
                                    $('#recoursId').val(rec_id);
		$('#matiereId').val(uem_ckey);
                                    $('#ctcId').val(ctc_code);
                                    $('#type_recours').val(rec_type);
                                    $('#departement').val(eta_code);
                                    $('#enseignant').val(matricule_ens);
                                    $('#message').val(message);
		$('#action').val('createRecours');
		$('#save').val('حفظ');
                //alert('fin success');
                                        }
                            })
	});	


    	$(document).on('click', '.__addRecours', function(){
		var uem_ckey = $(this).attr("id");
                                    var uem_desl = $(this).attr("value");
                                    var action  = "createRecours";
                                     uni_modal("Télécharger un fichier ","add_recours.php?rec_id=" + $(this).attr('rec_id'),"mid-large");
                                    return;
                                    
                 
//		alert(uem_ckey);
                                    $.ajax({
                                        url:"notes_action.php",
                                        method:"POST",
                                        //data:formData,
                                        data:{uem_ckey:uem_ckey, action:action},
                                        success:function(data){				
		                
                                    $('#recoursModal').modal('show');
		$('#recoursForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> تقديم طعن في علامة الإمتحان");
                                    $('panel_name').val('علامة الإمتحان');
                                    $('#uem_ckey').val(uem_ckey);
		$('#matiereId').val(uem_ckey);
		$('#action').val('createRecours');
		$('#save').val('حفظ');
                                        }
                            })
	});	

	$(document).on('submit','#recoursForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"notes_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#recoursForm')[0].reset();
				$('#recoursModal').modal('hide');				
				$('#save').attr('disabled', false);
				notesData.ajax.reload();
			}
		})
	});			
			
	$(document).on('click', '.delete', function(){
		var recoursId = $(this).attr("id");		
		var action = "deleteRecours";
		if(confirm("Voulez-vous réellement supprimer ce recours ?")) {
			$.ajax({
				url:"notes_action.php",
				method:"POST",
				data:{recoursId:recoursId, action:action},
				success:function(data) {					
					notesData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	


	$(document).on('click', '.getNotesSemestre', function(){
		//var sem_code = $(this).attr("id");	
		var sem_code = null;
		var e = document.getElementById("semestre");
        //alert(e);
        if (e !== null) { sem_code = e.value;}		
		//alert(sem_code);
					
		var action = "listNotesSemestre";
		//$_SESSION["sem_code"] = $sem_code;
		//alert(action);
		$.ajax({
                url:"notes_action.php",
			method:"POST",
			//dataType: 'json',
			data:{sem_code:sem_code, action:action, draw:1},
			success:function(data) {
				notesData.ajax.reload();
			}
		})

	});

        $("#departement").change(function()
        {
                
                var myDropDownList = $(".enseignant");
                var eta_code=$(this).val();
                var uem_ckey, ctc_code;
                var e = document.getElementById("matiereId");
                if (e !== null) { uem_ckey = e.value;}
                var e = document.getElementById("ctcId");
                if (e !== null) { ctc_code = e.value;}
                
                 //alert('info:'  + eta_code + '/'  + uem_ckey + '/' + ctc_code);
                
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_EnseignantRecours';
                $.ajax({
                    url: "notes_action.php",
                    type: "POST",
                    data:{eta_code:eta_code, uem_ckey:uem_ckey, ctc_code:ctc_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_matricule_ens = "";
                        var j = 0;
                        $("#enseignant").empty();
                        if(len == 0){
                            $("#enseignant").append("<option value='"+'0'+"'>"+'غير محدد'+"</option>");
                        }
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_matricule_ens = response[i]['matricule_ens'];}
                            
                            $("#enseignant").append("<option value='"+response[i]['matricule_ens']+"'>"+response[i]['nom_prenom']+"</option>");
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".enseignant").val(first_matricule_ens);
                         $(".enseignant").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

    
        $(".ens-eignant").change(function()
        {
                //alert('matières');
                var myDropDownList = $(".examen");
                var mat_code=$(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_EnseignantMatiereCtcs';
                $.ajax({
                    url: "saisie_notes_action.php",
                    type: "POST",
                    data:{mat_code:mat_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_ctc_code = "";
                        var j = 0;
                        $("#examen").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_ctc_code = response[i]['ctc_code'];}
                            $("#examen").append("<option value='"+response[i]['ctc_code']+"'>"+response[i]['ctc_desl']+"</option>");
                             }
                        //}                        
                         $(".examen").val(first_ctc_code);
                         $(".examen").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

        $(".parcours").change(function()
        {
                //alert('parcours-->semestre');
                var par_code="", ann_code="", cycle="";
                var lpos=0;
                var par_ann_cycle=$(this).val();
                var params = par_ann_cycle.split("/");
                //alert(params);
                par_code = params[0];
                ann_code = params[1];
                cycle = params[2];
                /*
                lpos = par_ann_code.indexOf("/");
                if (lpos > 0){
                    par_code = par_ann_code.substring(0, lpos);
                    ann_code = par_ann_code.substring(par_ann_code.length-1);
                }
                */
                var action = 'get_EtudiantSemestres';
                $.ajax({
                    url: "notes_action.php",
                    type: "POST",
                    data:{par_code:par_code, ann_code:ann_code, cycle:cycle, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_sem_code = "";
                        var j = 0;
                        $("#semestre").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_sem_code = response[i]['sem_code'];}
                            
                            $("#semestre").append("<option value='"+response[i]['sem_code']+"'>"+response[i]['sem_desl']+"</option>");
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".semestre").val(first_sem_code);
                         $(".semestre").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });

                
                
                
                
                
                
                
  /*              
                var action = 'get_EtudiantSemestres';
                $.ajax({
                    url: "notes_action.php",
                    type: "POST",
                    data:{par_code:par_code,ann_code:ann_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {

                //alert(lpos + "->" + par_ann_code + "[" + par_code + "][" + ann_code+"]");
	var s1, s2;
	if(ann_code=='1'){s1 = 1; s2 = 2;}
	if(ann_code=='2'){s1 = 3; s2 = 4;}
	if(ann_code=='3'){s1 = 5; s2 = 6;}
                  sem_desl1 = "السداسي" + " " + s1;   
                  sem_desl2 = "السداسي" + " " + s2;   
                //var dataString = 'mat_code=' + mat_code;
                $("#semestre").empty();
                $("#semestre").append("<option value='"+s1+"'>"+sem_desl1+"</option>");				
                $("#semestre").append("<option value='"+s2+"'>"+sem_desl2+"</option>");				
                $(".semestre").val(s1);
            	$(".semestre").change();
				//notesData.ajax.reload();
*/
        });

		
        $(".semestre").change(function() {
                //alert('semestre');
            	//$(".getNotesSemestre").click();
	var sem_code = null;
	var e = document.getElementById("semestre");
                if (e !== null) { sem_code = e.value;}		
	//alert(sem_code);
					
	var action = "listNotesSemestre";
		$.ajax({
                url:"notes_action.php",
			method:"POST",
			//dataType: 'json',
			data:{sem_code:sem_code, action:action, draw:1},
			success:function(data) {
				notesData.ajax.reload();
			}
		})
				
        });
        
       $(document).on('click', '.saveEtudiantChoix', function(){
                                    alert("save choix");
		var uem_ckey = $(this).attr("id");
                                    var uem_desl = $(this).attr("value");
                                    var action  = "createRecours";
                                     uni_modal("Télécharger un fichier ","add_recours.php?rec_id=" + $(this).attr('rec_id'),"mid-large");
                                    return;
                                    
                 
//		alert(uem_ckey);
                                    $.ajax({
                                        url:"notes_action.php",
                                        method:"POST",
                                        //data:formData,
                                        data:{uem_ckey:uem_ckey, action:action},
                                        success:function(data){				
		                
                                    $('#recoursModal').modal('show');
		$('#recoursForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> تقديم طعن في علامة الإمتحان");
                                    $('panel_name').val('علامة الإمتحان');
                                    $('#uem_ckey').val(uem_ckey);
		$('#matiereId').val(uem_ckey);
		$('#action').val('createRecours');
		$('#save').val('حفظ');
                                        }
                            })
	});	

});

