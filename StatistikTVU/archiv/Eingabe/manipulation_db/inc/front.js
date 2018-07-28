function eval_mit() {
	var kat = document.getElementsByName('kat');
	var id = 0;
	var chosen_kat = new Array();
	for (var i=0;i<kat.length;i++){
	  if ( kat[i].checked ) {
	  	chosen_kat[id] = kat[i].value;
	    id = id + 1;
		}
	}
	if (chosen_kat.length == 0) {
		chosen_kat[0] = 'no_kat';
	};

   	var sex = document.getElementsByName('sex');
	var id = 0;
	var chosen_sex = new Array();
	for (var i=0;i<sex.length;i++){
	  if ( sex[i].checked ) {
	  	chosen_sex[id] = sex[i].value;
	    id = id + 1;
		}
	}
	if (chosen_sex.length == 0) {
		chosen_sex[0] = 'no_sex';
	};

	var year = document.getElementById('year').value;

    $.post('anzeige_m.php',{postkategorie:chosen_kat, postgeschlecht:chosen_sex, postyear:year },
        function(data){
        $('#result').html(data);
    }); 
}

function search_leistung() {
	var kat = document.getElementsByName('kat');
	var id = 0;
	var chosen_kat = new Array();
	for (var i=0;i<kat.length;i++){
	  if ( kat[i].checked ) {
	  	chosen_kat[id] = kat[i].value;
	    id = id + 1;
		}
	}
	if (chosen_kat.length == 0) {
		chosen_kat[0] = 'no_kat';
	};

   	var sex = document.getElementsByName('sex');
	var id = 0;
	var chosen_sex = new Array();
	for (var i=0;i<sex.length;i++){
	  if ( sex[i].checked ) {
	  	chosen_sex[id] = sex[i].value;
	    id = id + 1;
		}
	}
	if (chosen_sex.length == 0) {
		chosen_sex[0] = 'no_sex';
	};

	var year = document.getElementById('year').value;

    $.post('anzeige_data.php',{postkategorie:chosen_kat, postgeschlecht:chosen_sex, postyear:year },
        function(data){
        $('#result_best').html(data);
    }); 

}

function change_aktiv(mit_id, numb_id) {
	var aktiv = document.getElementById(numb_id).value;

	$.post('change/change_aktiv.php',{postid:mit_id, postaktiv:aktiv},
        function(data){
        $('#result_change').html(data);
    });  
}


function change_aktiv_all(){
	var val=document.getElementById("all_aktiv_input").value;

	var x = document.getElementsByName('aktiv');
	for(i = 0; i < x.length; i++) {
		x[i].value = val;
	}
	
	document.getElementById("demo").innerHTML = val;
}
