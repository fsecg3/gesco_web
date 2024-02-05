$(document).ready(function() {        
	var stats_saisienotesData = $('#listSaisieNotes').DataTable({
                "serverSide":true,
                "responsive" : true,
                "deferRender"    : true,
                "processing" : true,
                "paging"     : true,
                "pageLength" : 25,
                "searching": true,
		"lengthChange": false,
		dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                
		"order":[],
		"ajax":{
			url:"stats_action.php",
			type:"POST",
			data:{action:'stats_SaisieNotes'},
			dataType:"json"
		}
                /*
                data: stats_saisienotesData,
                columns: [
                    { "data": "id" },
                    { "data": "pos" },
                    { "data": "matricule" },
                    { "data": "nom" },
                    { "data": "prenom" },
                    { "data": "date_nais" },
                    { "data": "ins_type" },
                    { "data": "note" },
                    { data: "absent",
                      render: function (data,type,row) {
                          if (data == true) {
                            return '<input type="checkbox" checked>';
                          } else {
                            return '<input type="checkbox">';
                          }
                        return data;
                      } 
                    },
                    { "data": "close" },
                    { "data": "block" },
                    { "data": "relation_exists" },
                    { "data": "end_date" },
                    { "data": "last_no_used" }
                ] */
        
                /*
                'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                    }
                }],
            *
		"columnDefs":[{
				"targets":[0, 3, 4],
				"orderable":false
			}
		]*/
                
		//"pageLength": 25
	});
        
        
        $('#note').keyup(function() {
            $(this).attr('size', $(this).val().length)
        });
        
        $('#listSaisieNotes').on('draw.dt', function(){
        $('#listSaisieNotes').Tabledit({
        url:'update_notes_action.php',
         dataType:'json',
        columns:{
        identifier : [12, 'id'],
        //editable:[[1, 'matricule'], [2, 'nom'], [3, 'prenom', '{"1":"Male","2":"Female"}']]
        editable:[[6, 'note'], [7, 'absent'], [8, 'exclu'], [11, 'obs']]
        
        },
        editButton: false,
        deleteButton: false,
        saveButton: false,
        restoreButton:false,
        onSuccess:function(data, textStatus, jqXHR)
        {
        if(data.action === 'delete')
        {
            $('#' + data.id).remove();
        $('#sample_data').DataTable().ajax.reload();
        }
        }
        });
        });
        
        $('#listSaisieNotes').Tabledit({
            // link to server script
            // e.g. 'ajax.php'
            url: 'update_notes_action.php',
            editButton: false,
            deleteButton: false,
            saveButton: false,
            restoreButton:false,
                columns: {
                identifier: [12, 'id'],
                editable: [[2, 'اللقب'], [3, 'الإسم']]
            },

            //inputClass:'form-control input-sm',
            inputClass:'form-control input-sm-2',
            // // class for toolbar
            toolbarClass:'btn-toolbar',
            // class for buttons group
            groupClass:'btn-group btn-group-sm',
            // class for row when ajax request fails
            dangerClass:'danger',
            // class for row when save changes
            warningClass:'warning',
            // class for row when is removed
            mutedClass:'text-muted',
            // trigger to change for edit mode.
            // e.g. 'dblclick'
            ///////eventType:'click',
            // change the name of attribute in td element for the row identifier
            rowIdentifier:'id',
            // activate focus on first input of a row when click in save button
            autoFocus:true,
            // hide the column that has the identifier
            hideIdentifier:true,
            // activate edit button instead of spreadsheet style
            //editButton:false,
            // activate delete button
            //deleteButton:false,
            // activate save button when click on edit button
            //saveButton:false,
            // activate restore button to undo delete action
            //restoreButton:false,
            // custom action buttons
        buttons: {
            edit: {
                class:'btn btn-sm btn-default',
                html:'<span class="glyphicon glyphicon-pencil"></span>',
                action:'edit'
            },
            delete: {
                class:'btn btn-sm btn-default',
                html:'<span class="glyphicon glyphicon-trash"></span>',
                action:'delete'
            },
            save: {
                class:'btn btn-sm btn-success',
                html:'Save'
            },
            restore: {
                class:'btn btn-sm btn-warning',
                html:'Restore',
                action:'restore'
            },
            confirm: {
                class:'btn btn-sm btn-danger',
                html:'Confirm'
            }
        }, //buttons
        // executed after draw the structure
        onDraw:function() {return; },
        // executed when the ajax request is completed
        // onSuccess(data, textStatus, jqXHR)
        onSuccess:function() {return; },
        // executed when occurred an error on ajax request
        // onFail(jqXHR, textStatus, errorThrown)
        onFail:function() {return; },
        // executed whenever there is an ajax request
        onAlways:function() {return; },
        // executed before the ajax request
        // onAjax(action, serialize)
        onAjax:function() {return; }
    });
    
        
	$('#addDepartement').click(function(){
		alert('add dep');
		$('#departementModal').modal('show');
		$('#departementForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> إضافة قسم جديد");
		$('#action').val('addDepartement');
		$('#save').val('Save');
	});	
		
			
	
    
	$(document).on('click', '.GetNotesSaisie', function(){
                    //alert(action);
                                    var mat_code = null, ctc_code = null, sec_code = null, grp_code = null;
		var e = document.getElementById("matiere");
                                    //alert(e);
                                    if (e !== null) { mat_code = e.value;}		
		
		var e = document.getElementById("examen");
		if (e !== null) { ctc_code = e.value;}
		
		var e = document.getElementById("section");
		if (e !== null) { sec_code = e.value;}
		
		var e = document.getElementById("groupe");
		if (e !== null) { grp_code = e.value;}

		var action = "listSaisieNotes";
		//$_SESSION["sem_code"] = $sem_code;
		//alert(mat_code+':'+ctc_code+':'+sec_code+':'+grp_code);
		$.ajax({
                                                      url:"saisie_notes_action.php",
			method:"POST",
			//dataType: 'json',
			data:{mat_code:mat_code, ctc_code:ctc_code, sec_code, grp_code:grp_code, action:action, draw:1},
                                                      //Success:function() {return; }
			success:function(data) {
				saisienotesData.ajax.reload();
			}
		});

	});
        
        $(document).on('click', '.ExportNotesExcel', function(){
		//alert(action);
		var e = document.getElementById("mat_code");
		var mat_code = e.value;		
		
		var e = document.getElementById("ctc_code");
		var ctc_code = e.value;
		
		var e = document.getElementById("sec_code");
		var sec_code = e.value;
		
		var e = document.getElementById("grp_code");
		var grp_code = e.value;

		var action = "ExportNotesExcel";
		//$_SESSION["sem_code"] = $sem_code;
		//alert(ctc_code+ctc_code+sec_code+grp_code);
		$.ajax({
                url:"saisie_notes_action.php",
			method:"POST",
			//dataType: 'json',
			data:{mat_code:mat_code, ctc_code:ctc_code, sec_code, grp_code:grp_code, action:action, draw:1},
			success:function(data) {
				saisienotesData.ajax.reload();
			}
		});

	});

        $(".parcours").change(function()
        {
                //alert('parcours-->matières');
                var myDropDownList = $(".matiere");
                var par_code=$(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_EnseignantMatieres';
                $.ajax({
                    url: "saisie_notes_action.php",
                    type: "POST",
                    data:{par_code:par_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_mat_code = "";
                        var j = 0;
                        $("#matiere").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_mat_code = response[i]['mat_code'];}
                            
                            $("#matiere").append("<option value='"+response[i]['mat_code']+"'>"+response[i]['mat_desl']+"</option>");
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".examen").val(first_mat_code);
                         $(".matiere").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

    
        $(".matiere").change(function()
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
        
      $(".examen").change(function()
        {
                //alert('examen');
                var myDropDownList = $(".section");
                var ctc_code=$(this).val();
                
                var action = 'get_EnseignantMatiereCtcSections';
                $.ajax({
                    url: "saisie_notes_action.php",
                    type: "POST",
                    data:{ctc_code:ctc_code, action:action},
                    dataType:"json",
                    cache: false,
                    success: function (response) {
                        
                        var len = response.length;
                         var first_sec_code = "";
                         var j = 0;
                        $("#section").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 2){first_sec_code = response[i]['sec_code'];}                            
                            $("#section").append("<option value='"+response[i]['sec_code']+"'>"+response[i]['sec_desl']+"</option>");
                            //alert(response[i]['sec_desl']);
                             }
                         $(".section").val(first_sec_code);
                         $(".section").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                });
        });   
        
        $(".section").change(function()
        {
                
                var myDropDownList = $(".groupe");
                var sec_code=$(this).val();
                //alert('section: ' + sec_code);
                var action = 'get_EnseignantMatiereCtcGroupes';
                $.ajax({
                    url: "saisie_notes_action.php",
                    type: "POST",
                    data:{sec_code:sec_code, action:action},
                    dataType:"json",
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;

                        $("#groupe").empty();
                        for( var i = 0; i<len; i++){
                            $("#groupe").append("<option value='"+response[i]['grp_code']+"'>"+response[i]['grp_desl']+"</option>");
                            //alert(response[i]['grp_desl']);
                             }
                         $(".groupe").val("0");
                         $(".groupe").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                });
        });   
});

