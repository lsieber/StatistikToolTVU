function activ()
    {
        var aktiv = document.getElementById('aktiv').value;
        var jg = document.getElementById('jg').value;
        var end = Number(jg)+Number(aktiv)+15;
        var txt = "Athlet ist aktiv bis und mit " + end;
        document.getElementById('result_aktiv').innerHTML = txt;
    }           
    
function eval_mit() {
	var vorname = document.getElementById('vorname').value;
	var name = document.getElementById('name').value;
	var jg = document.getElementById('jg').value;

	$.post('anzeige_m.php',{postvorname:vorname, postname:name, postjg:jg },
	     function(data){
	        $('#result_m').html(data);
	    });
}

function eval_dis() {
	var disziplin = document.getElementById('disziplin').value;
	var lauf = document.getElementById('lauf').value;

	$.post('anzeige_d.php',{postdisziplin:disziplin, postlauf:lauf },
	     function(data){
	        $('#result_d').html(data);
	    });
}
