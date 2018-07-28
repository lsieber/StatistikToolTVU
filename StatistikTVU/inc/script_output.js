function myFunction() {
	document.getElementById("demo").innerHTML = "Hello World";
}


function clear_field() {
    document.getElementById("topnumb").value="";
    if(document.getElementById("Rekorde").checked){
      document.getElementById("submit").value="zu den Rekorden";
    }
}

function clear_radio() {
    document.getElementById("alltop").checked = false;
    document.getElementById("top5").checked = false;
    document.getElementById("top10").checked = false;
    document.getElementById("Rekorde").checked = false;
}

function all_years_check() {
	var year = document.getElementsByName("year");
	year[0].checked = true;
}

function kat_rad2check() {
	var kat = document.getElementsByName("kategorie[]");
	var but = document.getElementById("divkat");
	if (kat[0].type == 'radio') {
		for (var i=0;i<kat.length;i++){
	  		kat[i].type = 'checkbox';
		}
	but.value = "einzeln";
	}else{
		for (var i=0;i<kat.length;i++){
	  		kat[i].type = 'radio';
		}
	but.value = "mehrere";
	}
}

function year_rad2check() {
	var year = document.getElementsByName("year[]");
	var but = document.getElementById("divyear");
	if (year[0].type == 'radio') {
		for (var i=0;i<year.length;i++){
	  		year[i].type = 'checkbox';
		}	
	but.value = "einzeln";
	}else{
		for (var i=0;i<year.length;i++){
	  		year[i].type = 'radio';
		}
	but.value = "mehrere";
	}
}

function kat_rad2ckeck() {
	var kat = document.getElementsByName("kategorie[]");
	for (var i=0;i<kat.length;i++){
  		kat[i].type = 'checkbox';
	}
}

function eval_mit() {
// Ermittle Kategorien die ausgewählt wurden
	var kat = document.getElementsByName('kategorie[]');
	if(kat[0].checked && kat[0].type == 'checkbox'){
		for (var i=0;i<kat.length;i++){
			kat[i].checked = true;	
		}
	}
	var id = 0;
	var chosen_kat = new Array();
	for (var i=0;i<kat.length;i++){
	  if ( kat[i].checked ) {	  	
	  	chosen_kat[id] = kat[i].value;
	    id = id + 1;
		}
	}
	// wert 1000 wenn nichts asgewählt wurde
	if (chosen_kat.length == 0) {
		chosen_kat[0] = 1000;
	};

// Ermittle Geschlecht das ausgewählt wurde
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
		chosen_sex[0] = 3;
	};

// ermittle ob alle disziplinen ausgewählt sind
	var dis = document.getElementsByName('disziplin[]');
		if(dis[0].checked){
			for (var i=0;i<dis.length;i++){
				dis[i].checked = true;	
				if (i == 1) {
					dis[1].checked = false;
				}
			}
		}	
	var id = 0;
	var chosen_dis = new Array();
	for (var i=0;i<dis.length;i++){
	  if ( dis[i].checked && i!=1) {	  	
	  	chosen_dis[id] = dis[i].value;
	    id = id + 1;
		}
	}
	if(!dis[0].checked || dis.length > 1 ){
// sende ermittlete Daten an anzeige_d.php, wo die Disziplinen ermittelt werden.
	    $.post('anzeige_d.php',{postkategorie:chosen_kat, postgeschlecht:chosen_sex},
	        function(data){
	        $('#result_d').html(data);
	    }); 
	}

}

function all_none_dis(){
	var dis = document.getElementsByName('disziplin[]');
	if(dis[0].checked){
		dis[1].checked = false;
		for (var i=2;i<dis.length;i++){
			dis[i].checked = true;	
		}
	}else{
		dis[1].checked = true;
		for (var i=2;i<dis.length;i++){
			dis[i].checked = false;	
		}
	}
}

function no_all_dis(){
	var dis = document.getElementsByName('disziplin[]');
	var one_check = false;
	var all_check = true;
	for (var i=1;i<dis.length;i++){
		if (!one_check) {
			one_check = dis[i].checked;
		}
		if (all_check) {
			all_check = dis[i].checked;
		}			
	}
	if (one_check && !all_check) {
		dis[0].checked = false;
		dis[1].checked = true;
	}else{
		if (all_check) {
			dis[0].checked = true;
			dis[1].checked = false;
		}else{
			if (!one_check) {

			}
		}
	}
}

function all_none_kat(){
	var kat = document.getElementsByName('kategorie[]');
	if (kat[0].type == 'checkbox') {
		if(kat[0].checked){
			for (var i=0;i<kat.length;i++){
				kat[i].checked = true;	
			}
		}else{
			for (var i=0;i<kat.length;i++){
				kat[i].checked = false;	
			}
		}
	}
}

function no_all_kat(){
// ändere häcken 
	var kat = document.getElementsByName('kategorie[]');
	var one_check = false;
	var all_check = true;
	for (var i=1;i<(kat.length);i++){
		if (!one_check) {
			one_check = kat[i].checked;
		}
		if (all_check) {
			all_check = kat[i].checked;
		}		
	}
	if (one_check && !all_check) {
			kat[0].checked = false;
	}else{
		if (all_check) {
			kat[0].checked = true;
		}
	}
}

function all_none_year(){
	var year = document.getElementsByName('year[]');
	if(year[0].checked && year[0].type == "checkbox"){
		for (var i=0;i<year.length;i++){
			year[i].checked = true;	
		}
	}else{
		if(!year[0].checked && year[0].type == "checkbox"){	
			for (var i=0;i<year.length;i++){
				year[i].checked = false;	
			}
		}
	}
}

function no_all_year(){
// ändere häcken 
	var year = document.getElementsByName('year[]');
	var one_check = false;
	var all_check = true;
	for (var i=1;i<(year.length);i++){
		if (!one_check) {
			one_check = year[i].checked;
		}
		if (all_check) {
			all_check = year[i].checked;
		}			
	}
	if (one_check && !all_check) {
		year[0].checked = false;
	}else{
		if (all_check) {
			year[0].checked = true;
		}
	}
}
function clear_alle_dis() {
	var dis = document.getElementsByName('disziplin[]');
	dis[0].checked = !dis[1].checked;
}

function all_year() {
	alert('hei');

	var year = document.getElementsByName('year');
}

function get_years() {
// Ermittle Kategorien die ausgewählt wurden
	var year = document.getElementsByName('year[]');
	var input_type = year[0].type;
	alert(input_type);
// sende ermittlete Daten an anzeige_d.php, wo die Disziplinen ermittelt werden.
    $.post('anzeige_year.php',{posttype:input_type},
        function(data){
        $('#years').html(data);
	    }); 

    var new_year = document.getElementsByName('year[]');
	new_year[0].checked = true ;

}

function get_years_from_db(){
	$.post('existing_entries.php',{type:"year", number:10},
        function(data){
        $('#years').html(data);
	    });
}

function get_wettkampf_year(){
	var years = document.getElementsByName("year[]");
	var chosen_years = []
	for (var i = 0; i < years.length; i++) {
		if (years[i].checked == true){
			chosen_years.push(years[i].value);
		}
	}
	$.post(
		"create_list_of_wettkampf.php",
		{years:2017},
		function(data){
			alert(data);
		}
	);
}
