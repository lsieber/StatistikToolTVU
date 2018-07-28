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
        $('#result_m').html(data);
    });
    
    $.post('anzeige_d.php',{postkategorie:chosen_kat, postgeschlecht:chosen_sex},
        function(data){
        $('#result_d').html(data);
    }); 
}
