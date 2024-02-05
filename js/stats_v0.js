$(document).ready(function() {        
	var Stats_SaisieNotesData = $('#stats_SaisieNotes').DataTable({
                "serverSide":true,
                "responsive" : true,
                "deferRender"    : true,
                "processing" : true,
                "paging"     : true,
                "pageLength" : 25,
                "searching": false,
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
        
        

        $(document).on('click', '.FilterStats', function(){
        //alert(action);
                 var eta_code = null, cycle = null, par_code = null, ann_code = null;
                 var sem_code = null, mat_code = null, ctc_code = null, matricule_ens = null;
                 
                 var e = document.getElementById("etablissement");
                 //alert(e);
                 if (e !== null) { eta_code = e.value;}		
		
	var e = document.getElementById("cycle");
	if (e !== null) { cycle = e.value;}
		
	var e = document.getElementById("parcours");
	if (e !== null) { par_code = e.value;}
		
	var e = document.getElementById("annee");
	if (e !== null) { ann_code = e.value;}

                var e = document.getElementById("semestre");
                 //alert(e);
                 if (e !== null) { sem_code = e.value;}		
	
                var e = document.getElementById("matiere");
	if (e !== null) { mat_code = e.value;}
	
	var e = document.getElementById("examen");
	if (e !== null) { ctc_code = e.value;}
		
	var e = document.getElementById("enseignant");
	if (e !== null) { matricule_ens = e.value;}
		
	
	var action = "stats_SaisieNotes";
	//$_SESSION["sem_code"] = $sem_code;
	//alert(mat_code+':'+ctc_code+':'+sec_code+':'+grp_code);
	$.ajax({
                             url:"stats_action.php",
                             method:"POST",
                             //dataType: 'json',
                             data:{eta_code:eta_code,cycle:cycle,par_code:par_code, ann_code:ann_code, sem_code:sem_code, mat_code:mat_code, ctc_code:ctc_code, matricule_ens:matricule_ens, action:action, draw:1},
                             //Success:function() {return; }
                             success:function(data) {
                                 //alert('rechargement');
		Stats_SaisieNotesData.ajax.reload();
		}
                 });

        });
        
        $(".etablissement").change(function()
        {
                //alert('établissement-->cycles');
                var myDropDownList = $(".cycle");
                var eta_code=$(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_Cycles';
                $.ajax({
                    url: "stats_action.php",
                    type: "POST",
                    data:{eta_code:eta_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_cycle = "";
                        var j = 0;
                        $("#cycle").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_cycle = response[i]['cycle'];}
                            
                            $("#cycle").append("<option value='"+response[i]['cycle']+"'>"+response[i]['cycle_desl']+"</option>");
                            //alert(first_cycle + ':' + response[i]['cycle_desl']);
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".cycle").val(first_cycle);
                         $(".cycle").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

       $(".cycle").change(function()
        {
                //alert('cycle-->parcours');
                var myDropDownList = $(".parcours");
                var cycle=$(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_Parcours';
                $.ajax({
                    url: "stats_action.php",
                    type: "POST",
                    data:{cycle:cycle, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_par_code = "";
                        var j = 0;
                        $("#parcours").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_par_code = response[i]['par_code'];}
                            
                            $("#parcours").append("<option value='"+response[i]['par_code']+"'>"+response[i]['par_desl']+"</option>");
                            //alert(first_cycle + ':' + response[i]['cycle_desl']);
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".parcours").val(first_par_code);
                         $(".parcours").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

        $(".parcours").change(function()
        {
                //alert('parcours-->années');
                var myDropDownList = $(".annee");
                var par_code=$(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_Annees';
                $.ajax({
                    url: "stats_action.php",
                    type: "POST",
                    data:{par_code:par_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_ann_code = "";
                        var j = 0;
                        $("#annee").empty();
                        for( var i = 0; i<len; i++){
                            j++;
                            if (j === 1){first_ann_code = response[i]['ann_code'];}
                            
                            $("#annee").append("<option value='"+response[i]['ann_code']+"'>"+response[i]['ann_desl']+"</option>");
                             }
                        //}           
                        //alert('first_mat_code: ' + first_mat_code);
                         $(".annee").val(first_ann_code);
                         $(".annee").change();

                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });

         $(".annee").change(function()
        {
                //alert('années-->semestres');
                var myDropDownList = $(".semestre");
                var ann_code = $(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_Semestres';
                $.ajax({
                    url: "stats_action.php",
                    type: "POST",
                    data:{ann_code:ann_code, action:action},
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
        });
   
        $(".semestre").change(function()
        {
                //alert('semestres-->matières');
                var myDropDownList = $(".matiere");
                var sem_code = $(this).val();
                //var dataString = 'mat_code=' + mat_code;
                var action = 'get_Matieres';
                $.ajax({
                    url: "stats_action.php",
                    type: "POST",
                    data:{sem_code:sem_code, action:action},
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
                         $(".matiere").val(first_mat_code);
                         $(".matiere").change();
                   },
                    failure: function (response) {
                        alert(response.d);
                    }
                
                });
        });
    
        $(".matiere").change(function()
        {
                //alert('matières-->examens');
                var myDropDownList = $(".examen");
                var mat_code = $(this).val();
                //var dataString = mat_code=' + mat_code;
                var action = 'get_Examens';
                $.ajax({
                    url: "stats_action.php",
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
                        //alert('first_mat_code: ' + first_mat_code);
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
                //alert('matières-->examens');
                var myDropDownList = $(".enseignant");
                var ctc_code = $(this).val();
                //var dataString = mat_code=' + mat_code;
                var action = 'get_Enseignants';
                $.ajax({
                    url: "stats_action.php",
                    type: "POST",
                    data:{ctc_code:ctc_code, action:action},
                    dataType:"json",
                    //data: dataString,
                    cache: false,
                    success: function (response) {
                        //alert(response.length);
                        var len = response.length;
                        var first_matricule_ens = "";
                        var j = 0;
                        $("#enseignant").empty();
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        });

