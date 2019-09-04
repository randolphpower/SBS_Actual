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

function mostrar_tipos_juicios(datos, campo){
		
		
		if (campo.length==0) { 
		document.getElementById("tipo_juicio").innerHTML="";
		document.getElementById("tipo_juicio").style.border="0px";
		document.form.idenJui.value="";
		return;
	    }
        
		divResultado = document.getElementById('tipo_juicio');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
						document.getElementById("tipo_juicio").innerHTML=ajax.responseText;
      					document.getElementById("tipo_juicio").style.border="1px solid #A5ACB2";
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION BUSCAR TIPOS DE JUICIO *********/

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
function accionaTipoJuicio(desc, id){
	
	
	document.form.tipoJuicio.value=desc;
	document.form.idenJui.value=id;
	document.getElementById("tipo_juicio").innerHTML="";
    document.getElementById("tipo_juicio").style.border="0px";
	document.getElementById("tipoJuicio").readOnly = true;
	
	buscar_id_etapa(id)
		
}
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/

/******** FUNCION PARA CARGAR ID DE ETAPAS PROCESALES *********/
function buscar_id_etapa(identificador){
		
	 	
        divResultado = document.getElementById('identificadorEtapa');
        ajax=objetoAjax();
        ajax.open("GET", 'controlador/script_eta_procesales.php?texto='+identificador+'&opcion=2');
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

/******** FUNCION PARA BUSQUEDA ID DE CODIGOS DE ACCION *********/
function mostrar_idCliente(datos, campo){

        if (campo.length==0) { 
                document.getElementById("clientes").innerHTML="";
                document.getElementById("clientes").style.border="0px";
                return;
        }
 
        if (campo.length >=3) {
                divResultado = document.getElementById('clientes');
                ajax=objetoAjax();
                ajax.open("GET", datos);
                ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                                divResultado.innerHTML = ajax.responseText
                                document.getElementById("clientes").innerHTML=ajax.responseText;
                                document.getElementById("clientes").style.border="1px solid #A5ACB2";
                        }
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION PARA BUSQUEDA ID DE CODIGOS DE ACCION *********/

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
function accionaNumJuicio(id,cliente,tJui,dJui){
	
	if(tJui =="" && dJui ==""){
		document.form.idCliente.value=id;
		document.getElementById("clientes").innerHTML="";
		document.getElementById("clientes").style.border="0px";
		document.getElementById("idCliente").readOnly = true;
		
		document.form.vacio.value=1;
	}
	else{
	
	document.form.idCliente.value=id;
	document.form.tipoJuicio.value=dJui;
	document.form.idenJui.value=tJui;
	document.getElementById("clientes").innerHTML="";
    document.getElementById("clientes").style.border="0px";
	document.getElementById("idCliente").readOnly = true;
	document.getElementById("tipoJuicio").readOnly=true;
	
	buscar_id_etapa(tJui);
	}
		
}
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/

/******** FUNCION INSERTAR IDENIFICADOR CLIENTE *********/
function insertar_op_etapas_proc(datos){
		
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

/******** FIN FUNCION INSERTAR IDENIFICADOR CLIENTE *********/

/******** FUNCION ENVIAR IDENIFICADOR CLIENTE *********/
function enviarEtapasProce(datos){
        divResultado = document.getElementById('etapas_procesales');
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
function borrarRegistroProce(datos){
        divResultado = document.getElementById('etapas_procesales');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        //divResultado.innerHTML = ajax.responseText;
						location.href=window.location.pathname;
                }
        }
        ajax.send(null)
}

/******** FIN FUNCION ENVIAR BORRAR REGISTRO *********/