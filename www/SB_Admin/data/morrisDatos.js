function getData(grafico) {

    if (grafico == "tempInt")
    {
        var fecha = $('#opciones option:selected').val();
        
        if ( fecha == "dia")
        {
            var phpDia = 'morrisCopiado.php?fecha=dia&nodeId=1&childId=3';
        }
        else if ( fecha == "semana")
        {
            var phpDia = 'morrisCopiado.php?fecha=semana&nodeId=1&childId=3';
        }
        else if ( fecha == "mes")
        {
            var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=1&childId=3';
        }
    }
    else if (grafico == "tempExt")
    {
        var fechaTempExt = $('#opcionesTempExt option:selected').val();
        if ( fechaTempExt == "dia")
        {
            var phpDia = 'morrisCopiado.php?fecha=dia&nodeId=2&childId=1';
        }
        else if ( fechaTempExt == "semana")
        {
            var phpDia = 'morrisCopiado.php?fecha=semana&nodeId=2&childId=1';
        }
        else if ( fechaTempExt == "mes")
        {
            var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=2&childId=1';
        }
        //var phpDia = 'morrisCopiado.php?fecha=dia';
    }
    else if (grafico == "HumInt")
    {
        var fechaTempExt = $('#opcionesHumInt option:selected').val();
        if ( fechaTempExt == "dia")
        {
            var phpDia = 'morrisCopiado.php?fecha=dia&nodeId=1&childId=2';
        }
        else if ( fechaTempExt == "semana")
        {
            var phpDia = 'morrisCopiado.php?fecha=semana&nodeId=1&childId=2';
        }
        else if ( fechaTempExt == "mes")
        {
            var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=1&childId=2';
        }
        //var phpDia = 'morrisCopiado.php?fecha=dia';
    }
    else if (grafico == "HumExt")
    {
        var fechaTempExt = $('#opcionesHumExt option:selected').val();
        if ( fechaTempExt == "dia")
        {
            var phpDia = 'morrisCopiado.php?fecha=dia&nodeId=2&childId=".0."';
        }
        else if ( fechaTempExt == "semana")
        {
            var phpDia = 'morrisCopiado.php?fecha=semana&nodeId=2&childId=".0."';
        }
        else if ( fechaTempExt == "mes")
        {
            var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=2&childId=".0."';
        }
        //var phpDia = 'morrisCopiado.php?fecha=dia';
        //var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=1&childId=2';
    }
    else if (grafico == "Luz")
    {
        var fechaTempExt = $('#opcionesLuz option:selected').val();
        if ( fechaTempExt == "dia")
        {
            var phpDia = 'morrisCopiado.php?fecha=dia&nodeId=1&childId=".0."';
        }
        else if ( fechaTempExt == "semana")
        {
            var phpDia = 'morrisCopiado.php?fecha=semana&nodeId=1&childId=".0."';
        }
        else if ( fechaTempExt == "mes")
        {
            var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=1&childId=".0."';
        }
        //var phpDia = 'morrisCopiado.php?fecha=dia';
        //var phpDia = 'morrisCopiado.php?fecha=mes&nodeId=1&childId=2';
    }

    

     var json = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': phpDia,
        'dataType': "json",
        'success': function (data) {
            json = data;
            
        }
    });

    return json;

}

function getDataBarras(grafico) {

    if (grafico == "tempInt")
    {
        var phpDia = 'morrisMaxMin.php?fecha=mes&nodeId=1&childId=3';
    }
    else if (grafico == "TempExt")
    {
        var phpDia = 'morrisMaxMin.php?fecha=mes&nodeId=2&childId=1';
    }
       

    var json = null;
    
    $.ajax({
        'async': false,
        'global': false,
        'url': phpDia,
        'dataType': "json",
        'success': function (data) {
            json = data;
            
        }
    });

    return json;

}


function convertirFecha(fecha)
{
    f = new Date(fecha);
    //return f.getDate()+'-'+(f.getMonth()+1)+'-'+f.getFullYear()+' '+f.getHours()+':'+f.getMinutes()+':'+f.getSeconds() ;

    var d = f.getDate();
    var m = (f.getMonth()+1);
    var y = f.getFullYear();
    var h = f.getHours();
    var min = f.getMinutes();
    var s = f.getSeconds();


    var dia = d<10? "0"+d: ""+d;
    var mes = m<10? "0"+m: ""+m;
    var hora = h<10? "0"+h: ""+h;
    var minutos = min<10? "0"+min: ""+min;
    var segundos = s<10? "0"+s: ""+s;

    return  dia+'-'+mes+'-'+f.getFullYear()+' '+hora+':'+minutos+':'+segundos ;

}

function convertirFechaLabel(fecha)
{
    f = new Date(fecha);

    var d = f.getDate();
    var m = (f.getMonth()+1);
    


    var dia = d<10? "0"+d: ""+d;
    var mes = m<10? "0"+m: ""+m;
    

    return  dia+'-'+mes+'-'+f.getFullYear() ;
    
}

function convertirFechaDias(fecha)
{
	f = new Date(fecha);

    var d = f.getDate();
    var m = (f.getMonth()+1);
    


    var dia = d<10? "0"+d: ""+d;
    var mes = m<10? "0"+m: ""+m;
    

    return  dia+'-'+mes+'-'+f.getFullYear() ;
    
}

function convertirFechaHoras(fecha)
{
    f = new Date(fecha);
    //return f.getDate()+'-'+(f.getMonth()+1)+'-'+f.getFullYear()+' '+f.getHours()+':'+f.getMinutes()+':'+f.getSeconds() ;

    
    var h = f.getHours();
    var min = f.getMinutes();
    var s = f.getSeconds();


    var hora = h<10? "0"+h: ""+h;
    var minutos = min<10? "0"+min: ""+min;
    var segundos = s<10? "0"+s: ""+s;

    return  hora+':'+minutos+':'+segundos ;
}

function convertirFechaLabelTempInt(fecha)
{
	
	var tiempo = $('#opciones option:selected').val();
        
    if ( tiempo == "dia")
	{
		return convertirFechaHoras(fecha);
	}
	else
	{
		return convertirFechaDias(fecha);
	}  
}

function convertirFechaLabelTempExt(fecha)
{
	
	var tiempo = $('#opcionesTempExt option:selected').val();
        
    if ( tiempo == "dia")
	{
		return convertirFechaHoras(fecha);
	}
	else
	{
		return convertirFechaDias(fecha);
	}  
}

function convertirFechaLabelHumInt(fecha)
{
	
	var tiempo = $('#opcionesHumInt option:selected').val();
        
    if ( tiempo == "dia")
	{
		return convertirFechaHoras(fecha);
	}
	else
	{
		return convertirFechaDias(fecha);
	}  
}
function convertirFechaLabelHumExt(fecha)
{
	
	var tiempo = $('#opcionesHumExt option:selected').val();
        
    if ( tiempo == "dia")
	{
		return convertirFechaHoras(fecha);
	}
	else
	{
		return convertirFechaDias(fecha);
	}  
}

function convertirFechaLabelLuz(fecha)
{
	
	var tiempo  = $('#opcionesLuz option:selected').val();
        
    if ( tiempo == "dia")
	{
		return convertirFechaHoras(fecha);
	}
	else
	{
		return convertirFechaDias(fecha);
	}  
}

function crearGrafico() {
    
 
    var json = getData('tempInt');
    var fecha = $('#opciones option:selected').val();
   

        	var morrisLine = Morris.Line({
	        element: 'morris-line-chart',
	        data: json,
	        xkey: 'fecha',
	        ykeys: ['dato'],
	        labels: ['valor del sensor'],        
	        hideHover: 'auto',
	        resize: true,
	        dateFormat: convertirFecha,
	        xLabelFormat: convertirFechaLabelTempInt

	    });

    

    setInterval(function(){
        var json = getData('tempInt');
        morrisLine.setData(json);
    }, 5000);

    $('#opciones').change(function(event) {
        var json = getData('tempInt');
        morrisLine.setData(json);
    });

    var jsonTempExt = getData('tempExt');

    var morrisLineTempExt = Morris.Line({
        element: 'morris-line-chartTempExt',
        data: jsonTempExt,
        xkey: 'fecha',
        ykeys: ['dato'],
        labels: ['valor del sensor'],        
        hideHover: 'auto',
        resize: true,
        dateFormat: convertirFecha,
        xLabelFormat: convertirFechaLabelTempExt

    });

    setInterval(function(){
        var jsonTempExt = getData('tempExt');
        morrisLineTempExt.setData(jsonTempExt);
    }, 5000);

    $('#opcionesTempExt').change(function(event) {
        var jsonTempExt = getData('tempExt');
        morrisLineTempExt.setData(jsonTempExt);
    });

    var jsonHumInt = getData('HumInt');

    var morrisAreaHumInt = Morris.Area({
        element: 'morris-area-chartHumInt',
        fillOpacity: 0.4,
        data: jsonHumInt,
        xkey: 'fecha',
        ykeys: ['dato'],
        labels: ['valor del sensor'],        
        hideHover: 'auto',
        resize: true,
        dateFormat: convertirFecha,
        xLabelFormat: convertirFechaLabelHumInt

    });

    setInterval(function(){
        var jsonHumInt = getData('HumInt');
        morrisAreaHumInt.setData(jsonHumInt);
    }, 5000);

    $('#opcionesHumInt').change(function(event) {
        var jsonHumInt = getData('HumInt');
        morrisAreaHumInt.setData(jsonHumInt);
    });


    // HUMEDAD EXTERIOR

    var jsonHumExt = getData('HumExt');

    var morrisAreaHumExt = Morris.Area({
        element: 'morris-area-chartHumExt',
        fillOpacity: 0.4,
        data: jsonHumExt,
        xkey: 'fecha',
        ykeys: ['dato'],
        labels: ['valor del sensor'],        
        hideHover: 'auto',
        resize: true,
        dateFormat: convertirFecha,
        xLabelFormat: convertirFechaLabelHumExt

    });

    setInterval(function(){
        var jsonHumExt = getData('HumExt');
        morrisAreaHumExt.setData(jsonHumExt);
    }, 5000);

    $('#opcionesHumExt').change(function(event) {
        var jsonHumExt = getData('HumExt');
        morrisAreaHumExt.setData(jsonHumExt);
    });

    // NIVEL LUZ

    var jsonLuz = getData('Luz');


    var morrisLineLuz = Morris.Line({
        element: 'morris-line-chartLuz',
        data: jsonLuz,
        xkey: 'fecha',
        ykeys: ['dato'],
        labels: ['valor del sensor'],        
        hideHover: 'auto',
        resize: true,
        dateFormat: convertirFecha,
        xLabelFormat: convertirFechaLabelLuz

    });

    setInterval(function(){
        var jsonLuz = getData('Luz');
        morrisLineLuz.setData(jsonLuz);
    }, 5000);

    $('#opcionesLuz').change(function(event) {
        var jsonLuz = getData('Luz');
        morrisLineLuz.setData(jsonLuz);
    });

    // BARRAS TEMP INT

    var jsonBarrasTempInt = getDataBarras('tempInt');


    var morrisBarrasTempInt = Morris.Bar({
        element: 'morris-bar-chartTemInt',
        data: jsonBarrasTempInt,
        xkey: 'fecha',
        ykeys: ['dato','dato2'],
        labels: ['valor max','valor min'],        
        hideHover: 'auto',
        resize: true,
        

    });

    setInterval(function(){
        var jsonBarrasTempInt = getDataBarras('tempInt');
        morrisBarrasTempInt.setData(jsonBarrasTempInt);
    }, 5000);

    /*$('#opcionesLuz').change(function(event) {
        var jsonLuz = getData('Luz');
        morrisLineLuz.setData(jsonLuz);
    }); */  

    // BARRAS TEMP EXR

    var jsonBarrasTempExt = getDataBarras('TempExt');


    var morrisBarrasTempExt = Morris.Bar({
        element: 'morris-bar-chartTempExt',
        data: jsonBarrasTempExt,
        xkey: 'fecha',
        ykeys: ['dato','dato2'],
        labels: ['valor max','valor min'],        
        hideHover: 'auto',
        resize: true,


    });

    setInterval(function(){
        var jsonBarrasTempExt = getDataBarras('TempExt');
        morrisBarrasTempExt.setData(jsonBarrasTempExt);
    }, 5000);

/*
    Morris.Line({
        element: 'morris-line-chart',
        data: json,
        xkey: 'fecha',
        ykeys: ['dato'],
        labels: ['valor del sensor'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true,
        dateFormat: function (x) { return new Date(x).toDateString(); }

    });
*/
    
}




crearGrafico();

 