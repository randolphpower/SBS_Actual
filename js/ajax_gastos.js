/******** FUNCION OBJETO AJAX PRINCIPAL *********/
function objetoAjax(){
		
        var xmlhttp=false;
        try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
                try {
                   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                        xmlhttp = false;
                }
        }

        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
                xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
}
/******** FIN FUNCION OBJETO AJAX PRINCIPAL *********/

/******** FUNCION BUSCAR TIPOS DE JUICIO *********/
function mostrar_desc_gasto(datos, campo){
		
		
	        if (campo.length==0) { 
		document.getElementById("gastos").innerHTML="";
		document.getElementById("gastos").style.border="0px";
		return;
	    }
        
		divResultado = document.getElementById('gastos');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
						document.getElementById("gastos").innerHTML=ajax.responseText;
      					document.getElementById("gastos").style.border="1px solid #A5ACB2";
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION BUSCAR TIPOS DE JUICIO *********/

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
function accionaDesGastos(id, codigo, desc, monto){
	
	document.form.descGasto.value=desc;
	document.form.tipoGasto.value=id;
	document.form.subtipoGasto.value=codigo;
	document.form.montoGas.value=monto;
	document.getElementById("gastos").innerHTML="";
        document.getElementById("gastos").style.border="0px";
	document.getElementById("descGasto").readOnly = true;
}
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/

/******** FUNCION INSERTAR GASTOS *********/
function insertar_gastos(datos){
	
        divResultado = document.getElementById('act_op_cheq');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
                }
        }
        ajax.send(null)
}

/******** FIN FUNCION INSERTAR GASTOS *********/

/******** FUNCION ENVIAR IDENIFICADOR CLIENTE *********/
function enviarGastos(datos){
        divResultado = document.getElementById('gastos');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
                }
        }
        ajax.send(null)
}

/******** FIN FUNCION ENVIAR IDENIFICADOR CLIENTE *********/

/******** FUNCION BORRAR REGISTRO *********/
function borrarRegistroGastos(datos){
        divResultado = document.getElementById('gastos');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        //divResultado.innerHTML = ajax.responseText
						location.href=window.location.pathname;
                }
        }
        ajax.send(null)
}

/******** FIN FUNCION ENVIAR BORRAR REGISTRO *********/