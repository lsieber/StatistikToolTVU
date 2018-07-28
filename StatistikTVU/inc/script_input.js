function post(selectable_mitglied) {

    var wettkampf = "no_w";
    var $year_input_field = document.getElementById("year");
    if ($year_input_field.value != "") {
        wettkampf = $year_input_field.value;
    }
    var whichWettkampf = document.getElementsByName("wettkampf");
    var lenW = whichWettkampf.length;
    for (i=0;i<lenW;i++)
    {
        if(whichWettkampf[i].checked)
        {
            var wettkampf = whichWettkampf[i].value;
            break;
        }
    } 
    var kat = "no_k";
    var whichKategorie = document.getElementsByName("kategorie");
    var lenK = whichKategorie.length;
    for (i=0;i<lenK;i++)
    {
        if(whichKategorie[i].checked)
        {
            var kat = whichKategorie[i].value;
            break;
        }
    }

    var dis = ["no_d"];
    var first_checked_disziplin =true;
    var whichDisziplin = document.getElementsByName("disziplin");
    var lenD = whichDisziplin.length;
    var input_type = "radio";
    if (lenD!=0) {
        input_type = whichDisziplin[0].type;
        for (i=0;i<lenD;i++)
        {
            if(whichDisziplin[i].checked)
            {
                if (first_checked_disziplin) {
                    dis[0] = whichDisziplin[i].value;
                    first_checked_disziplin = false;     
                }else{
                    dis.push(whichDisziplin[i].value);
                }
            }
        }
    }
    $.post('show_mitglied.php',{postkategorie:kat, postwettkampf:wettkampf},
         function(data){
         $('#result_m').html(data);
    });
    $.post('show_disziplin.php',{postkategorie:kat, checked_disziplins:dis, disziplin_input_type:input_type},
         function(data){
         $('#result_d').html(data);
    });    
    if (typeof(selectable_mitglied) != "undefined") {
        var mitglied = document.getElementsByName("mitglied");
        for (var i = 0; i < mitglied.length; i++) {
            if (mitglied[i].value == selectable_mitglied) {
            mitglied[i].checked = true;
            }
        }
    }
    var recheckDisziplin = document.getElementsByName("disziplin");

    var lenrecheckD = recheckDisziplin.length;
    
    for (i=0;i<lenrecheckD;i++)
        {
            if(recheckDisziplin[i].value==dis)
            {
                recheckDisziplin[i].checked = true;
                break;
            }
    
        }
}

function add_mitglied(){
    var vorname = 'Vorname: <input type="text" id="vorname" oninput="eval_mit()"" />  <br>';
    var name = 'Name: <input type="text" id="name" oninput="eval_mit()"/> <br />';
    var jg = 'Jg(z.B. 1993):  <input type="number" id="jg" oninput="activ()"" style="width: 4em" min="1850" max="2050" value="2007"/> <br />';
    var sex = 'Geschlecht (1:W, 2:M):  <input type="number" id="sex" style="width: 4em" min="1" max="5" value="2"/> <br />';
    var activity = 'Aktivität: <input type="number" id="activity" style="width: 4em" oninput="activ()"" value= "1"/> <div id="result_aktiv"></div>';
    var submit = '<input type="button" onclick="insert_mitglied()" value="Mittglied erfassen!" />';
    $('#new_mitglied_input').html("<p>"+vorname+name+jg+sex+activity+"<div id='existing_mitglied'></div>"+submit+"</p>");
    $('#new_mitglied_link').html('<a href="#"onclick="vanish_mitglied()">ausblenden!</a>');
}
function vanish_mitglied(){
    $('#new_mitglied_link').html('<a href="#"onclick="add_mitglied()">Mitglied hinzufügen</a>')
    $('#new_mitglied_input').html("");
    $('#insert_mitglied_result').html("");

}
function insert_mitglied(){
    var vorname = document.getElementById('vorname').value;
    var name = document.getElementById('name').value;
    var jg = document.getElementById('jg').value;
    var sex = document.getElementById('sex').value;
    var activity = document.getElementById('activity').value;

    if(name == "" || vorname == "" || jg == "" || sex == "" || activity == "") //  
    {
        alert("Bitte Fülle alle Felder aus");
    } 
    else 
    {
        if (vorname.slice(-1)==" ") 
        {
            alert("Überprüfe deine Eingabe: Der Vorname darf nicht mit einem Leerschlag enden");
        } else if (name.slice(-1)==" ") 
        {
            alert("Überprüfe deine Eingabe: Der Nachname darf nicht mit einem Leerschlag enden");
        }
        else
        {
                //insert into db
            $.post('insert_w_d_m.php',{type:"mitglied", name:name, vorname:vorname, jg:jg, sex:sex, activity:activity },
                function(data){
                    var data_arr = data.split("-----");
                    $('#insert_mitglied_result').html(data_arr[0]);
                    post(data_arr[1]);
            }); 
        }
    }
}
function activ()
    {
        var aktiv = document.getElementById('activity').value;
        var jg = document.getElementById('jg').value;
        var end = Number(jg)+Number(aktiv)+15;
        var txt = "Athlet ist aktiv bis und mit " + end;
        document.getElementById('result_aktiv').innerHTML = txt;
    }           
    
function eval_mit() {
    var vorname = document.getElementById('vorname').value;
    var name = document.getElementById('name').value;
    var jg = document.getElementById('jg').value;

    $.post('existing_entries.php',{type:"mitglied",vorname:vorname,name:name,jg:jg },
         function(data){
            $('#existing_mitglied').html(data);
        });
}
/*
function eval_dis() {
    var disziplin = document.getElementById('disziplin').value;
    var lauf = document.getElementById('lauf').value;

    $.post('anzeige_d.php',{postdisziplin:disziplin, postlauf:lauf },
         function(data){
            $('#result_d').html(data);
        });
}*/


function add_disziplin(){
    var name = 'Name der Disziplin: <input type="text" size="25" id="d_name"  value="Drehwurf"/>  <br>';
    var lauf = 'Typ der Disziplin ( 1=Lauf, 2=Sprung 3=Wurf 4=Mehrkampf 5=Staffel 6=Team Mehrkampf (LMM/SVM/...) ): <input type="number" max="7" id="d_lauf" value="3"/> <br />';
    var min = 'Minimalwert: <input type="number" size="25" id="d_min_value" value ="5" /> <br />';
    var max = 'Maximalwert: <input type="number" size="25" id="d_max_value" value ="40" /> <br />';
    var visible = "<table border='1'>sichtbar für: <thead> <th>U10</th><th>U12</th><th>U14</th><th>U16</th><th>U18</th><th>U20</th><th>Frauen</th><th>Männer</th></thead><tr><td><input type='checkbox' name='sich[]' value='U10' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U12' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U14' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U16' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U18' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U20' checked='yes'></td><td><input type='checkbox' name='sich[]' value='wom' checked='yes'></td><td><input type='checkbox' name='sich[]' value='man' checked='yes'></td></tr></table>";
    var submit = '<input type="button" onclick="insert_disziplin();post()" value="Disziplin erfassen!" />';
    $('#new_disziplin_input').html("<p>"+name+lauf+min+max+visible+submit+"</p>");
    $('#new_disziplin_link').html('<a href="#"onclick="vanish_disziplin()">ausblenden!</a>');
}
function vanish_disziplin(){
    $('#new_disziplin_link').html('<a href="#"onclick="add_disziplin()">Disziplin hinzufügen</a>')
    $('#new_disziplin_input').html("");
}
function insert_disziplin(){
    var name = document.getElementById('d_name');
    var lauf = document.getElementById('d_lauf');
    var min = document.getElementById('d_min_value');
    var max = document.getElementById('d_max_value');
    var visible = document.getElementsByName('sich[]');
    var vis_kat = {};
    for (var i = 0; i < visible.length; i++) {
        vis_kat[visible[i].value] = visible[i].checked;
    }

    if(name.value == "" || lauf.value == "" || min.value == "" || max.value == "") //  
    {
        alert("Bitte Fülle alle Felder aus");
    }
    else
    {
            //insert into db
        $.post('insert_w_d_m.php',{type:"disziplin", name:name.value, lauf:lauf.value, min_value:min.value , max_value:max.value, vis:vis_kat },
            function(data){
                var data_arr = data.split("-----");
                $('#insert_disziplin_result').html(data_arr[0]);
                //$('#disziplin_list').html(data_arr[1]);
        }); 
    }
}


function add_wettkampf(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    if (dd<10) {dd = '0'+dd;}
    if (mm<10) {mm = '0'+mm;}
    var formated_today = yyyy+'-'+mm+'-'+dd;
    var name = 'Name des Wettkampfs: <input type="text" size="25" id="w_name"  value=""/>  <br>';
    var place = 'Ort: <input type="text" size="25" id="w_place" value=""/> <br />';
    var date = 'Datum: <input type="date" size="25" id="w_date" value ="'+formated_today+'" />  <br />';
    var submit = '<input type="button" onclick="insert_wettkampf()" value="Wettkampf erfassen!" />';
    $('#new_wettkampf_input').html("<p>"+name+place+date+submit+"</p>");
    $('#new_wettkampf_link').html("");
}

function insert_wettkampf(){
    var name = document.getElementById('w_name');
    var place = document.getElementById('w_place');
    var date = document.getElementById('w_date');
    if(name.value == "" || place.value == "" || date.value == "") //  
    {
        alert("Bitte Fülle alle Felder aus");
    }
    else
    {
        date_array = date.value.split("-");
        if (date.value.split("-").length == 3)
        {
            //insert into db
            $.post('insert_w_d_m.php',{type:"wettkampf", name:name.value, place:place.value, date:date.value},
                function(data){
                    var data_arr = data.split("-----");
                    $('#new_wettkampf_link').html(data_arr[0]);
                    $('#competition_list').html(data_arr[1]);
            }); 
        }
        else
        {
            alert("date format not correct")
        }
    }
}

function check_wk_year(int){
    var year = document.getElementById("year");
    if (int == 0) {
        // write empty value into number
        year.value = "";
    } else if (int == 1){
        // uncheck all checkboxes
        var checkboxes = document.getElementsByName('wettkampf');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
        }
        if (year.value == ""){
            year.value = 2000;
        } 
    } else{
        alert("something went wrong with checking the wettkampf boxes")
    }
}

function insert(insert_outside_range, onload=false ){
    var disziplin = "no_d";
    var leistung = "no_l";
    /* --------------- Gewählten Wettkampf ermitteln --------------- */        
    var whichWettkampf = document.getElementsByName("wettkampf");
    for (i=0;i<whichWettkampf.length;i++)
    {
        if(whichWettkampf[i].checked)
        {
            var wettkampf = whichWettkampf[i].value;
            break;
        }
        else
        {
            var wettkampf = "no_w";
        }
    }

    /* ---------------- gewähltes Mitglied ermitteln ------------------*/
    var mitglied = "no_m";
    var whichMitglied = document.getElementsByName("mitglied");
    var lenM = whichMitglied.length;
    if (lenM==0){
        var mitglied = "no_m";
    }else{
        for (i=0;i<lenM;i++)
        {
            if(whichMitglied[i].checked)
            {
                var mitglied = whichMitglied[i].value;
                break;
            }
        } 
    }    
    /* ---------------- senden der Daten an eingabe.php ----------- */
    performance = document.getElementsByName("performance");
    if (onload) {        
        $.post('insert.php',
            {postdisziplin:disziplin, 
            postwettkampf:wettkampf,
            postmitglied:mitglied,
            postinsert_outside_range:insert_outside_range,
            postleistung:leistung},
         function(data){
            var data_arr = data.split("-----");
            $('#eingabeout_above').html(data_arr[0]+data_arr[2]);
            $('#eingabeout_below').html(data_arr[3]+data_arr[4]+data_arr[5]);
        });
    } else {
        if (performance.length == 0) {
            alert("Disziplin wählen");
        } else {
            for (var i = 0; i < performance.length; i++) {
                disziplin = performance[i].id;
                if (performance[i].value != 0) {
                    leistung = performance[i].value;
                } else {
                    leistung = "no_l";
                }
                var to_alert;
                var correct_insert="58";
                $.post('insert.php',
                    {postdisziplin:disziplin, 
                    postwettkampf:wettkampf,
                    postmitglied:mitglied,
                    postinsert_outside_range:insert_outside_range,
                    postleistung:leistung},
                 function(data){
                    var data_arr = data.split("-----");
                    $('#eingabeout_above').html(data_arr[0]+data_arr[2]);
                    $('#eingabeout_below').html(data_arr[3]+data_arr[4]+data_arr[5]);
                    to_alert = data_arr[3]+". Falls min und max Value nicht stimmen, einzeleingabe benützen";  
                    if(data_arr[1]==1){
                        alert(to_alert);
                    }                  
                });
            }   
        }
    }
}


function delete_entry(id){
    $.post('delete_db_entry.php',{postid:id},
         function(data){
        var data_arr = data.split("-----");
        $('#eingabeout_above').html(data_arr[0]);
        alert(data_arr[0]);
        $('#eingabeout_below').html(data_arr[1]+data_arr[2]);
    });
}

function out_wk() {
    var wettkampf = "no_w";
    var whichWettkampf = document.getElementsByName("wettkampf");
    var lenW = whichWettkampf.length;
    for (i=0;i<lenW;i++)
    {
        if(whichWettkampf[i].checked)
        {
            var wettkampf = whichWettkampf[i].value;
            break;
        }
    }

    $.post('show_wettkampf.php',{postwettkampf:wettkampf},
         function(data){
         $('#result_wk').html(data);
    });   
}


function change_multiple_input() {
    // change disziplin to checkboxes for multiple selection
    var disziplin = document.getElementsByName("disziplin");
    for (var i = disziplin.length - 1; i >= 0; i--) {
        disziplin[i].type = 'checkbox';
    }
    //change existing input field to multiple input style: " dis: [input] "
    add_input_field();
    // change link to back to normal input
    $('#change_multiple_input').html("<a href='#'onclick='simple_insert()'>Einfache Eingabe</a>");
}

function simple_insert() {
    // change disziplin back to radio
    var disziplin = document.getElementsByName("disziplin");
    for (var i = disziplin.length - 1; i >= 0; i--) {
        disziplin[i].type = 'radio';
    }
    //change input field to normal style input
    add_input_field();
    //change link to multiple input
    $('#change_multiple_input').html("<a href='#'onclick='change_multiple_input()'>Mehrkampf eingeben</a>");
}


function add_input_field(){
    var disziplin = document.getElementsByName("disziplin");
    var input ="";
    for (var i=0; i < disziplin.length; i++) {
        if (disziplin[i].checked==true) {
            var oninput =  'oninput="calculatepoints()"';
            if (get_Lauf(disziplin[i].value) == 4 ){
                oninput = 0;
            }
            input = input+disziplin[i].id+': <input type="text" size="10" name="performance" id="'+disziplin[i].value+'"'+oninput+' /></br>'; //<div id="points_'+disziplin[i].value+'"></div>
        }
    }
    
    $('#performance_input').html(input);

    var performance = document.getElementsByName('performance');
    if (performance.length == 0) {
        $('#performance_input').html("Disziplin wählen!");
    }

}

function get_Lauf (disziplin_id){
    var Lauf = false;
    $.ajax({
        type: "POST",
        url: 'existing_entries.php',
        data: ({ type: "Lauf_of_Disziplin", disziplin_id: disziplin_id}),
        async: false,
        success: function(data){
            Lauf = data;
        } 
    });
    return Lauf
}

function calculatepoints(){
    var performances = document.getElementsByName("performance");
    var mitglied = document.getElementsByName("mitglied");
    var selected_mitglied = "no_m";
    for (var i = 0; i < mitglied.length; i++) {
        if(mitglied[i].checked == true){
            selected_mitglied = mitglied[i].value
            break;
        } 
    }
    if (selected_mitglied == "no_m") {
        $('#result_wk').html("kein Mitglied gewählt");
    } else{
        var disziplin_ids = [];
        var performances = [];
        for (var i = 0; i < performance.length; i++) {
            disziplin_ids[i] = performance[i].id;
            performances[i] = performance[i].value;
        }
        $.post('calculate_points.php',{
                disziplin_id:disziplin_ids, 
                performance:performances, 
                mitglied_id:selected_mitglied},
            function(data){
                var data_arr = data.split("//");
                var disziplin_id_point = data_arr[0].split(",");
                var disziplin_Lauf_point = data_arr[1].split(",");
                var points = data_arr[2].split(",");
                var mehrkampf_id = 0;
                for (var i = 0; i < disziplin_id_point.length; i++) {
                    if (disziplin_Lauf_point[i] == 4) {
                        mehrkampf_id = disziplin_id_point[i];
                    } 
                }
                var mehrkampf_points_sum = 0;
                if (mehrkampf_id > 0) {
                    for (var i = 0; i < disziplin_id_point.length; i++) {
                        if(disziplin_id_point[i] != mehrkampf_id){
                            if (points[i] == "no points") {
                                var p = 0;
                            }else{
                                var p = points[i];
                            }
                            mehrkampf_points_sum = Number(mehrkampf_points_sum) + Number(p);
                        }
                    }
                    var string_mk_id = mehrkampf_id;
                    var mehrkampf_input_field = document.getElementById(string_mk_id);
                    mehrkampf_input_field.value = mehrkampf_points_sum;
                }
        });            
    }
}
