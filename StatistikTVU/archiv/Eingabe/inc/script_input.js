function eval_dis(){
    document.getElementById


}
function post(eingabe) {
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

    var dis = "no_d";
    var whichDisziplin = document.getElementsByName("disziplin");
    var lenD = whichDisziplin.length;
    if (lenD!=0) {
        for (i=0;i<lenD;i++)
        {
            if(whichDisziplin[i].checked)
            {
                var dis = whichDisziplin[i].value;
                break;
            }
        }
    };
    //alert(lenD);

    $.post('anzeige_m.php',{postkategorie:kat, postwettkampf:wettkampf},
         function(data){
         $('#result_m').html(data);
    });
    
    $.post('anzeige_d.php',{postkategorie:kat},
         function(data){
         $('#result_d').html(data);
    });    

    //alert("hello");

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
        //alert(lenrecheckD);
        //alert(recheckDisziplin.length);

/*var dis2 = "no_d";
    var whichDisziplin = document.getElementsByName("disziplin");
    var lenD = whichDisziplin.length;
    if (lenD!=0) {
        for (i=0;i<lenD;i++)
        {
            if(whichDisziplin[i].checked)
            {
                var dis2 = whichDisziplin[i].value;
                break;
            }
        }
    };
alert(dis2);
*/
}

function insert(){
    /* --------------- Gewählte Disziplin ermitteln --------------- */        
    var whichDisziplin = document.getElementsByName("disziplin");
    var lenD = whichDisziplin.length;

    for (i=0;i<lenD;i++)
    {
        if(whichDisziplin[i].checked)
        {
            var disziplin = whichDisziplin[i].value;
            break;   
        }
        else
        {
            var disziplin = "no_d";    
        }  
    } 

    /* --------------- Gewählten Wettkampf ermitteln --------------- */        
    var whichWettkampf = document.getElementsByName("wettkampf");
    var lenW = whichWettkampf.length;

    for (i=0;i<lenW;i++)
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
    var whichMitglied = document.getElementsByName("mitglied");
    var lenM = whichMitglied.length;
    if (lenM==0){
        var mitglied = "no_k";
    }else{
        for (i=0;i<lenM;i++)
        {
            if(whichMitglied[i].checked)
            {
                var mitglied = whichMitglied[i].value;
                break;
            }
            else
            {
                var mitglied = "no_m";
            }
        } 
    }    
    /* ------------------- eingegebene Leistung ermittlen -------------*/
    var leistung = $('#leistung').val();
    if(leistung==""){
        leistung="no_l";
    }
    /* ---------------- senden der Daten an eingabe.php ----------- */        
    $.post('eingabe.php',
        {postdisziplin:disziplin, 
        postwettkampf:wettkampf,
        postmitglied:mitglied,
        postleistung:leistung},
    function(data){
     $('#eingabeout').html(data);
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

    $.post('wk_out.php',{postwettkampf:wettkampf},
         function(data){
         $('#result_wk').html(data);
    });   
}