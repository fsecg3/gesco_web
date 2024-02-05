var stop_saisie =   "0";
$(document).ready(function() {
    
    
    getModeSaisie(); //'0'; //Arrêter la saisie des notes par les enseignants
    /////alert(stop_saisie);
    
    
    var editCheck = function(cell){
    //cell - the cell component for the editable cell
    //alert('editcheck: ' + stop_saisie);
    //get row data
    //var data = cell.getRow().getData();
    var saisie_ok = false;
    if(stop_saisie == 0) {saisie_ok = true;}
    //alert('saisie_ok=' + saisie_ok);
    return saisie_ok;
    }
    
    var table = new Tabulator("#SaisieNotesTable", {
        printRowRange:"All",
        printAsHtml:true,
        printStyled:true,
                
        placeholder:"اختر المسلك أو التخصص ثم اضغط على الزر موافق",
        //placeholder:"Veuilez sélectionner un parcours, une matière, un examen et une section.",
        //placeholder:"اختر المؤسسة ثم اضغط على الزر موافق",
        autoResize:true,
          //ajaxContentType : "application/json; charset=utf-8",
         
          height:601, // hauteur de la table de saisie des notes
          //data:tabledata, //assign data to table
          layout:"fitColumns", 
          
          columns:[ 
          {title:"رقم", field:"pos", width:20, hozAlign:"center", headerSort:false},
          {title:" رقم التسجيل", field:"matricule", width:125, hozAlign:"center", headerSort:false, headerFilter:"input"},
          {title:"اللقب", field:"nom", width:150, hozAlign:"right", headerSort:false, headerFilter:"input"},
          {title:"الإسم", field:"prenom", width:150, hozAlign:"right", headerSort:false, headerFilter:"input"},
          {title:"تاريخ الميلاد", field:"date_nais", width:90, hozAlign:"center", headerSort:false, print:false},
          {title:"الوضعية", field:"situation", width:80, hozAlign:"right", headerSort:false},
          {title:"المجموعة", field:"section", width:65, hozAlign:"center", headerSort:false},
          {title:"الفوج", field:"groupe", width:50, hozAlign:"center", headerSort:false},
          {title:"العلامة/20", field:"note", width:70, hozAlign:"center", headerSort:false, editor:"input", validator:["min:0", "max:20", "numeric"], editable:editCheck}, //formatter:function(cell, formatterParams){var value = cell.getValue();cell.getElement().css({"background-color":"#f00"}); return value;}},
          {title:"غياب/إقصاء", field:"etat", width:85, hozAlign:"center", editor:"list", editable:editCheck, editorParams:{values:{" ":" ", "غياب مبرر":"غياب مبرر", "غياب غير مبرر":"غياب غير مبرر", "إقصاء":"إقصاء"}}, headerSort:false},
          //{title:"مقصى", field:"etat", editor:"tickCross", editorParams:{trueValue:"", falseValue:""},width:50, hozAlign:"center", headerSort:false},
          {title:"ملاحظة", field:"obs", hozAlign:"right", width:216, editor:"input", editable:editCheck, headerSort:false, print:false},
          {title:"id", field:"id", sorter:"number", hozAlign:"center", width:10,  editor:"input", validator:["min:0", "max:20", "numeric"], editable:true, visible:false, print:false},
          ],
          /*
          columns:[ 
          {title:"N°", field:"pos", width:20, hozAlign:"center", headerSort:false},
          {title:" Matricule", field:"matricule", width:125, hozAlign:"center", headerSort:false},
          {title:"Nom", field:"nom", width:150, hozAlign:"left", headerSort:false},
          {title:"Prénom", field:"prenom", width:150, hozAlign:"left", headerSort:false},
          {title:"Date Nais.", field:"date_nais", width:90, hozAlign:"center", headerSort:false, print:false},
          {title:"Situation", field:"situation", width:80, hozAlign:"left", headerSort:false},
          {title:"Section", field:"section", width:65, hozAlign:"center", headerSort:false},
          {title:"Groupe", field:"groupe", width:65, hozAlign:"center", headerSort:false},
          {title:"Note/20", field:"note", width:70, hozAlign:"center", headerSort:false, editor:"input", validator:["min:0", "max:20", "numeric"]}, //formatter:function(cell, formatterParams){var value = cell.getValue();cell.getElement().css({"background-color":"#f00"}); return value;}},
          {title:"Abs/Exclu", field:"etat", width:85, hozAlign:"center", editor:"list", editorParams:{values:{" ":" ", "Abs. justifiée":"Abs. justifiée", "Abs. non justifiée":"Abs. non justifiée", "Exclu(e)":"Exclu(e)"}}, headerSort:false},
          //{title:"مقصى", field:"etat", editor:"tickCross", editorParams:{trueValue:"", falseValue:""},width:50, hozAlign:"center", headerSort:false},
          {title:"Observation", field:"obs", hozAlign:"left", width:201, editor:"input", headerSort:false, print:false},
          {title:"id", field:"id", sorter:"number", hozAlign:"center", width:10,  editor:"input", validator:["min:0", "max:20", "numeric"], editable:true, visible:false, print:false},
          
      ],*/
    /*  
      rowFormatter: function(row){
		//console.log(row.getData());
        alert('test');
		if (row.getData().name === 'note'){
			const children = row.getElement().childNodes;
			children.forEach((child) => {
				child.style.backgroundColor = 'green';
			})
		}
	}*/
      //$("#add-row").click(function(){
      //    $("#SaisieNotesTable").tabulator("addRow", {}, true);
      
    });
          
    table.on("dataLoaded", function(){
      //  $('.GetNotesSaisie').click();

    });
    //jQuery('#parcours').trigger('change');
/*
 var editCheck = function(cell){
    //cell - the cell component for the editable cell
    alert('editcheck')
    //get row data
    var data = cell.getRow().getData();
    
    return false; ///!!(data.name) // only allow the name cell to be edited if the age is over 18
    }*/
    
    //in your column definition for the column
    //{title:"Name", field:"name", editor:"input", editable:editCheck}}

    table.on("cellEdited", function(cell){
       //var note = null, exclu = '0', absent = '0', obs = null; 
       var dataModified = false;
       var re = cell.getRow(cell); // get the row of the cell that was clicked
       var id = re.getData().id; // gets the value of column "id" in the same row
       
       var fieldname = cell.getColumn().getField();
       var newValue = cell.getValue();
       var oldValue = cell.getOldValue();
       
       if (fieldname == "note"){
          if(oldValue != null && newValue != oldValue){dataModified = true;}
          if(oldValue == null && newValue != ''){dataModified = true;}
       }

       if (fieldname == "etat"){
          if(oldValue != null && newValue != oldValue){dataModified = true;}
          if(oldValue == null && newValue != '0'){dataModified = true;}
          if(newValue == " "){newValue = "0";}
          if(newValue == "غياب مبرر"){newValue = "1";}
          if(newValue == "غياب غير مبرر"){newValue = "2";}
          if(newValue == "إقصاء"){newValue = "3";}
        }
        if (fieldname == "obs"){
            if(oldValue != null && newValue != oldValue){dataModified = true;}
            if(oldValue == null && newValue != ''){dataModified = true;}
        }
       if (dataModified){
          var action = "Save_Note";
          $.ajax({
            url:"saisie_notes_action.php",
            method:"POST",
            //dataType: 'json',
            data:{id:id, fieldname:fieldname, oldValue:oldValue, newValue:newValue, action:action},
 
            success:function(data) {
            //   alert(note + 'sauvegarde ok' + oldNote);
            }
          
          });
        }
    });

    $('#note').keyup(function() {
       $(this).attr('size', $(this).val().length)
    });
        
      
     function getModeSaisie(){
         var action = "get_StopSaisie";
         //alert(action);
          $.ajax({
            url:"saisie_notes_action.php",
            method:"POST",
            //dataType: 'json',
            data:{action:action},
 
            success:function(data) {
             stop_saisie = data;
          //   alert(data + ':' + stop_saisie);
            }
          
          });
          return stop_saisie;
        }
    // }
        
    
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
				table.setData(data);
			}
		});

	});
    
    $(document).on('click', '.PrintEnseignantPVM', function(){
        table.print(false, true);
        return;
        
        var parcours = null, matiere = null, examen = null, section = null, groupe = null;
		var e = document.getElementById("parcours");
        //alert(e);
        if (e !== null) { parcours = e.options[e.selectedIndex].text;}		
		//alert(parcours);

        var e = document.getElementById("matiere");
        //alert(e);
        if (e !== null) { matiere = e.options[e.selectedIndex].text;}		

		var e = document.getElementById("examen");
		if (e !== null) { examen = e.options[e.selectedIndex].text;}
		
		var e = document.getElementById("section");
		if (e !== null) {
                                            if(e.value === '0'){
                                                section = e.options[e.selectedIndex].text;
                                            }else{
                                                section = e.value;
                                            }
                                            
                                    }
		
		var e = document.getElementById("groupe");
		if (e !== null) {
                                            if(e.value === '0'){
                                                groupe = e.options[e.selectedIndex].text;
                                            }else{
                                                groupe = e.value;
                                            }
                                            
                                    }

        var action = "PrintEnseignantPVM";
        $.ajax({
            url:"saisie_notes_action.php",
            method:"POST",
            //dataType: 'json',
            data:{parcours:parcours, matiere:matiere, examen:examen, section:section, groupe:groupe, action:action},
 
            success:function(data) {
                 $("#msg").attr("data", data);
                //alert('ok');
                
                 window.open('pvm.pdf','_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=650, height=440, toolbar=0, status=0');
                 return;
                  //exit;
           /*       
            //Convert the Byte Data to BLOB object.

                    var blob = new Blob([data], {type: "application/pdf" });
                    //var blob = new Blob([data], { type: "application/octetstream" });


                    //Check the Browser type and download the File.

                    var isIE = false || !!document.documentMode;

                    if (isIE) {

                        window.navigator.msSaveBlob(blob, 'pvm.pdf');

                    } else {

                        var url = window.URL || window.webkitURL;

                        link = url.createObjectURL(blob);

                        var a = $("<a />");

                        a.attr("download", 'pvm.pdf');

                        a.attr("href", link);

                        $("body").append(a);

                        a[0].click();

                        $("body").remove(a);
*/
///                    }

                //}

                // window.open("data:application/pdf," + escape(data));
                //window.open('pvm.pdf', 'width=500,height=500,top=100,left=500');
                 //var url = '../Home/GetPDF?fileName=' + 'pvm.pdf';
                //window.open(url, '_blank');

       ///---          window.open('pvm.pdf','_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=650, height=440, toolbar=0, status=0')
       ///---          window.open(data, '_blank', height=500,width=750);

                 
                //window.open("", "pdfWindow","scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400"');
               // $('#' + formID).attr('action','e:\xampp\htdocs\gesco_web\pvm.pdf').attr('target','pdfWindow');
            //   alert(note + 'sauvegarde ok' + oldNote);
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
                         $(".matiere").val(first_mat_code);
                         $(".matiere").change();
                        getModeSaisie();
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
                        getModeSaisie();
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
                         getModeSaisie();
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

    $(".groupe").change(function(){
        //alert('groupe changed');
        $('.GetNotesSaisie').click();
       // saisienotesData.ajax.reload();
        //alert('rechargement');
    });
                
});

