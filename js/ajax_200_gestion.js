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

/******** FUNCION BUSCAR CODIGOS DE ACCION *********/
function mostrar_codigos_resultados(datos, campo){
		
		
		if (campo.length==0) { 
		document.getElementById("codigo_accion").innerHTML="";
		document.getElementById("codigo_accion").style.border="0px";
		return;
	    }
        
		divResultado = document.getElementById('codigo_accion');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
						document.getElementById("codigo_accion").innerHTML=ajax.responseText;
      					document.getElementById("codigo_accion").style.border="1px solid #A5ACB2";
                }
        }
        ajax.send(null)
}
/******** FUNCION OBJETO AJAX PRINCIPAL *********/

/******** FUNCION ACCIONA PARA CARGAR CODIGOS ACCION *********/
function accionaCodA(desc, codigo, codigoR){
	
	
	document.form.desCodA.value=desc;
	document.form.codA.value=codigo;
	document.getElementById("codigo_accion").innerHTML="";
    document.getElementById("codigo_accion").style.border="0px";
	
	buscar_codigo_resultado(codigo, codigoR)
		
}
/******** FIN FUNCION ACCIONA PARA CARGAR CODIGOS ACCION *********/

/******** FUNCION PARA CARGAR ID DE ETAPAS PROCESALES *********/
function buscar_codigo_resultado(identificador, codigoR){
		
	 	
        divResultado = document.getElementById('codigoResultado');
        ajax=objetoAjax();
        ajax.open("GET", 'controlador/script_200_gestion.php?texto='+identificador+'&texto2='+codigoR+'&opcion=2');
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
						document.getElementById("tipo_juicio").innerHTML="";
    					document.getElementById("tipo_juicio").style.border="0px";
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION PARA CARGAR ID DE ETAPAS PROCESALES *********/

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
/*function accionaNumJuicio(id,cliente, fechaDe, codigoA, codigoR, desc){
	
	document.form.idCliente.value="J"+id;
	document.form.fecha.value=fechaDe;	
	document.getElementById("clientes").innerHTML="";
    document.getElementById("clientes").style.border="0px";
	
	accionaCodA(desc, codigoA, codigoR)
	
		
}*/
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/

/******** FUNCION ENVIAR IDENIFICADOR CLIENTE *********/
function enviarFtp_200_gestion(datos){
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

/******** FIN FUNCION ENVIAR IDENIFICADOR CLIENTE *********/

/******** FUNCION BORRAR REGISTRO *********/
function borrarRegistro200(datos){
        divResultado = document.getElementById('act_op_cheq');
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

function mostrarCodR(datos){
		divResultado = document.getElementById('comen');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.value = ajax.responseText
                }
        }
        ajax.send(null)
}