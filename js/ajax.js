
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
function mostrar_tipos_juicios(datos, campo){


		if (campo.length==0) {
		document.getElementById("livesearch").innerHTML="";
		document.getElementById("livesearch").style.border="0px";
		return;
	    }

		divResultado = document.getElementById('livesearch');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
						document.getElementById("livesearch").innerHTML=ajax.responseText;
      					document.getElementById("livesearch").style.border="1px solid #A5ACB2";
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION BUSCAR TIPOS DE JUICIO *********/

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
function accionaTipoJuicio(desc, id){


	document.form.tipoJuicio.value=desc;
	document.form.idenJui.value=id;
	document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
	document.getElementById("tipoJuicio").readOnly = true;


}
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/

/******** FUNCION BUSCAR JUZGADOS *********/
function mostrar_juzgados(datos, campo){


		if (campo.length==0) {
		document.getElementById("juzgados").innerHTML="";
		document.getElementById("juzgados").style.border="0px";
		return;
	    }

		divResultado = document.getElementById('juzgados');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
						document.getElementById("juzgados").innerHTML=ajax.responseText;
      					document.getElementById("juzgados").style.border="1px solid #A5ACB2";
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION BUSCAR JUZGADOS *********/

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
function accionaJuzgados(nombre,id){

	document.form.idJuzgado.value=nombre;
	document.form.idenJuz.value=id;
	document.getElementById("juzgados").innerHTML="";
    document.getElementById("juzgados").style.border="0px";
	document.getElementById("idJuzgado").readOnly = true;


}
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/


/******** FUNCION PARA BUSQUEDA ID DE CODIGOS DE ACCION *********/
function mostrar_idCliente(datos, campo){

	if (campo.length == 0) {
		document.getElementById("cliente").innerHTML="";
		document.getElementById("cliente").style.border="0px";
		return;
	}
		
	if (campo.length >=0) {
		divResultado = document.getElementById('cliente');
		ajax = objetoAjax();
		ajax.open("GET", datos);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("cliente").innerHTML = ajax.responseText;
					document.getElementById("cliente").style.border = "1px solid #A5ACB2";
			}
		}
	}
	ajax.send(null)
}
/******** FIN FUNCION PARA BUSQUEDA ID DE CODIGOS DE ACCION *********/

var tmp_fechaDemanda = '';
var tmp_fechaInicio = '';

/******** FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/
function accionaNumJuicio(id, cliente, juzgado, expediente, nJuz, feDema, idTip, fechaInicio){

	// alert("Fecha Demanda: " + feDema + ", Fecha de Inicio: " + fechaInicio);

	if (feDema != '') {
		f1 = feDema.split("-")[2] + '/' + feDema.split("-")[1] + '/' + feDema.split("-")[0];
	} else {
		f1 = '';
	}

	if  (fechaInicio != '') {
		f2 = fechaInicio.split("-")[2] + '/' + fechaInicio.split("-")[1] + '/' + fechaInicio.split("-")[0];
	} else {
		f2 = '';
	}

	tmp_fechaDemanda = f1;
	tmp_fechaInicio = f2;
	
	if (juzgado == "" || expediente == "" ) {

		document.form.numJuicio.value = id;
		document.form.idCliente.value = cliente;
		document.form.numExpe.value = expediente;
		document.form.idJuzgado.value = nJuz;
		document.form.idenJuz.value = juzgado;
		document.form.fechaDem.value = feDema;
		document.form.vacio.value = 1;

		document.form.idenJui.value = idTip;

		document.getElementById("cliente").innerHTML = "";
		document.getElementById("cliente").style.border = "0px";
		document.getElementById("idCliente").readOnly = true;

	} else if (juzgado == "POR ASIGNAR" || juzgado == "PORASIGNAR" || juzgado ==  "PorAsignar") {

		document.form.numJuicio.value = id;
		document.form.idCliente.value = cliente;
		
		document.getElementById("cliente").innerHTML = "";
		document.getElementById("cliente").style.border = "0px";
		document.getElementById("idCliente").readOnly = true;
		document.getElementById("numExpe").readOnly = true;

		document.form.numExpe.value = expediente;
		document.form.fechaDem.value = f1;
		document.form.idenJui.value = idTip;
		document.form.vacio.value = 2;

	} else {

		document.form.numJuicio.value = id;
		document.form.idCliente.value = cliente;
		document.form.idJuzgado.value = nJuz;
		document.form.idenJuz.value = juzgado;
		document.form.numExpe.value = expediente;
		document.form.fechaDem.value = f1;
		document.form.idenJui.value = idTip;

		document.getElementById("cliente").innerHTML = "";
		document.getElementById("cliente").style.border = "0px";
		document.getElementById("idCliente").readOnly = true;
		document.getElementById("idJuzgado").readOnly = true;
		document.getElementById("numExpe").readOnly = true;

	}

	funcion_iden_etapa('controlador/script_info_juicios.php?idTipoJui='+document.form.idenJui.value+'&opcion=9', 'identificadorEtapa');

}
/******** FIN FUNCION ACCIONA PARA CARGAR ID DE TIPOS DE JUICIO *********/

/******** FUNCION INSERTAR IDENIFICADOR CLIENTE *********/
function insertar_info_juicio(datos){

        divResultado = document.getElementById('act_op_cheq');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
			if (ajax.readyState == 4) {
				divResultado.innerHTML = ajax.responseText
			}
        }
        ajax.send(null)
}

/******** FIN FUNCION INSERTAR IDENIFICADOR CLIENTE *********/

/******** FUNCION ENVIAR IDENIFICADOR CLIENTE *********/
function enviarInfoJuicios(datos){
        divResultado = document.getElementById('info_juicios');
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
function borrarRegistroInfo(datos){
        divResultado = document.getElementById('info_juicios');
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

function funcion_iden_etapa(datos, nomb_div){

		ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                       if(nomb_div!=""){
						   	divResultado = document.getElementById(nomb_div);
					   		divResultado.innerHTML = ajax.responseText;

					   		funcion_iden_etapa2('controlador/script_info_juicios.php?idTipoJui='+document.form.idenJui.value+'&opcion=12', 'identificadorEt');

					   }
                }
        }
        ajax.send(null)
}

function funcion_iden_etapa2(datos, nomb_div){

		ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                       if(nomb_div!=""){
						   	divResultado = document.getElementById(nomb_div);
					   		divResultado.innerHTML = ajax.responseText;
					   }
                }
        }
        ajax.send(null)
}

/******** FUNCION CARGAR EL TIMEVALUE DEL SCIENTIST  *********/
function identificador(datos){
		divResultado = document.getElementById('identificadorEt');
        ajax=objetoAjax();
        ajax.open("GET", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.value = ajax.responseText
                }
        }
        ajax.send(null)
}
/******** FIN FUNCION CARGAR EL TIMEVALUE DEL SCIENTIST *********/

function guardar_datos(div){

	divResultado = document.getElementById(div);

	var rut = document.form.idCliente.value;
	var numJuicio = document.form.numJuicio.value;
	var idenJuz = document.form.idenJuz.value;
	var numExpe = document.form.numExpe.value;
	var fechaDem = document.form.fechaDem.value;
	var idenJui = document.form.idenJui.value;

	if (document.form.idEtapa == true){
		var idEtapa = document.form.idEtapa.value;
	}

	var fechaInicio = document.form.fechaInicio.value;
	var fechaFin = document.form.fechaFin.value;

	var fecha = document.form.fecha.value;
	var codA = document.form.codA.value;

	if (document.form.codR == true){
		var codR = document.form.codR.value;
	}

	var comen = document.form.comen.value;

	if(document.form.identEtapa == true){
		var identificadorEtapa = document.form.identEtapa.value;
	}
	var proveedor = document.form.proveedor.value;
	var numFactura = document.form.numFactura.value;
	var montoGas = document.form.montoGas.value;
	var fechaAuto = document.form.fechaAuto.value;
	var descGasto = document.form.descGasto.value;
	var tipoGasto = document.form.tipoGasto.value;
	var subtipoGasto = document.form.subtipoGasto.value;
	var desFact = document.form.desFact.value;

	var informacion = document.form.informacion.value;

	var proce = 0;
	var gestion = 0;
	var gasto = 0;

	if (rut.length==0 || rut==""){

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un rut valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("idCliente").focus();

	}

	if (document.form.idEtapa.value!="sel" || fechaInicio !="" || fechaFin !=""){

		if(document.form.idEtapa.value == "sel"){

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Seleccione una etapa.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("idEtapa").focus();

			return;

		}

		else if((fechaInicio.length==0 || fechaInicio=="") && (fechaFin.length==0 || fechaFin=="")){

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese una Fecha Valida.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("fechaInicio").focus();

			return;

		}

		proce = 1;

	}

	if (fecha.length != 0 || fecha != "" || codA.length != 0 || codA != "") {

		if (fecha.length == 0 || fecha == "") {

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese una Fecha valida.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("fecha").focus();

			return;

		}
		else if (codA.length == 0 || codA == "") {

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un codigo de accion valido.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("desCodA").focus();

			return;

		}
		else if(document.form.codR.value == "defecto"){

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un codigo de resultado valido.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("CodR").focus();

			return;

		}
		else if(document.form.comen.value == "defecto"){

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un comentario.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("comen").focus();

			return;

		}
		gestion = 1;

	}

	/*alert("Existe:"+document.form.identEtapa	+" / Valor:"+document.form.identEtapa.value);*/

	if (document.form.identEtapa.value!="sel"){

			if(fecha=="" && document.form.identEtapa.value!="sel"){

					mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Debe completar 200 gestiones.</div>';
					divResultado.innerHTML = mensaje;
					document.getElementById("fecha").focus();

					return;

			}

			if(document.form.identEtapa.value=="sel" && (proveedor.length>=1 || numFactura.length>=1 || montoGas.length>=1 || fechaAuto.length>=1 || descGasto.length>=1 || desFact.length>=1)){

				mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Debe seleccionar un identificador de etapa valido.</div>';
				divResultado.innerHTML = mensaje;
				document.getElementById("identEtapa").focus();

				return;

			}

			if (fechaAuto.length==0 || fechaAuto==""){

					mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Debe Ingresar una Fecha de diligencia valida.</div>';
					divResultado.innerHTML = mensaje;
					document.getElementById("fechaAuto").focus();

					return;

			}

			if (descGasto != "" && montoGas == "") {

				mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Debe ingresar un monto de gasto valido .</div>';
				divResultado.innerHTML = mensaje;
				document.getElementById("montoGas").focus();

				return;

			}
			if (document.form.identEtapa.value != "sel" && descGasto == "" ){

				mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Debe ingresar una descripcion de gasto valida .</div>';
				divResultado.innerHTML = mensaje;
				document.getElementById("descGasto").focus();

				return;

			}

			gasto = 1;

	}

	// GUARDAR DATOS INFERIOR
	funcion_guarda_info('controlador/script_info_juicios.php?rut='+document.form.idCliente.value+'&numJuicio='+document.form.numJuicio.value+'&idenJuz='+document.form.idenJuz.value+'&numExpe='+document.form.numExpe.value+'&fechaDem='+document.form.fechaDem.value+'&idenJui='+document.form.idenJui.value+'&idEtapa='+document.form.idEtapa.value+'&fechaInicio='+document.form.fechaInicio.value+'&fechaFin='+document.form.fechaFin.value+'&fecha='+document.form.fecha.value+'&codA='+document.form.codA.value+'&codR='+document.form.codR.value+'&comen='+document.form.comen.value+'&proveedor='+document.form.proveedor.value+'&numFactura='+document.form.numFactura.value+'&montoGas='+document.form.montoGas.value+'&fechaAuto='+document.form.fechaAuto.value+'&descGasto='+document.form.descGasto.value+'&tipoGasto='+document.form.tipoGasto.value+'&subtipoGasto='+document.form.subtipoGasto.value+'&desFact='+document.form.desFact.value+'&identificadorEtapa='+document.form.identEtapa.value+'&proce='+proce+'&gestion='+gestion+'&gasto='+gasto+'&informacion='+informacion+'&opcion=11',div);

}

function funcion_guarda_info(getparams, nomb_div) {
	
	// console.log(datos);
	
	$.ajax({
		url: getparams,
		success:function(data) {

			if (data == 'gastos_warn') {
				alert("Ingrese NOTIFICACION DE LA DEMANDA - Fecha Inicio y Fecha Fin Obligatorias");
			} else if (data == 'ask create') {
				alert("Ingrese NOTIFICACION DE LA DEMANDA");
				
			} else if (data == 'ask date') {

				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth() + 1; //January is 0!
				var yyyy = today.getFullYear();

				if (dd < 10) dd = '0' + dd;
				if (mm < 10) mm = '0' + mm;
				
				var today = dd + '/' + mm + '/' + yyyy;
				var date = prompt("Ingrese fecha de término de \"Notificación de la demanda\" (dd/mm/aaaa) : ", today);

				if (date) {

					// to insert or update 
					$.ajax({
						url: 'ajax_update_notif.php',
						type: 'POST',
						dataType: 'json',
						data: {
							'fechafin': date,
							'numjuicio': document.form.numJuicio.value,
						},
						success:function(data) {
							alert(data.status);
						},
						error: function(jqXHR, textStatus, errorThrown)  {
							alert("ERROR: " + textStatus);
						}
					});

				} 

			} else if (data == 'duplicate data') {
				divResultado = document.getElementById(nomb_div);
				divResultado.innerHTML = "";
				alert("Etapa de la demanda ya existe");				
			}else {
				if (nomb_div != "") {				
					divResultado = document.getElementById(nomb_div);
					divResultado.innerHTML = data;
				}

			}
        },
        error: function(errorThrown) {
			console.log("ERROR: " + errorThrown);
		}
	});
	
	// ajax = objetoAjax();
	// ajax.open("GET", datos);
	// ajax.onreadystatechange=function() {
	// 	if (ajax.readyState == 4) {
	// 		if(nomb_div != ""){
	// 			divResultado = document.getElementById(nomb_div);
	// 			divResultado.innerHTML = ajax.responseText;
	// 		}
	// 	}
	// }

	// ajax.send(null)
}

function guardar_info(div){

	divResultado = document.getElementById(div);

	var rut = document.form.idCliente.value;
	var numJuicio = document.form.numJuicio.value;
	var idenJuz = document.form.idenJuz.value;
	var numExpe = document.form.numExpe.value;
	var idJuzgado = document.form.idJuzgado.value;
	var idenJui = document.form.idenJui.value;
	var fechaDem = document.form.fechaDem.value;

	if (rut == "" || rut.length == 0){

		mensaje = '<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un rut valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("idCliente").focus();

		return;

	} else if (idJuzgado == "" || idJuzgado.length == 0) {

		mensaje = '<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un Juzgado valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("idJuzgado").focus();

		return;

	} else if (numExpe == "" || numExpe.length == 0) {

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> Ingrese un Rol valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("numExpe").focus();

		return;

	}

	funcion_guarda_info('controlador/script_info_juicios.php?numJuicio=' + numJuicio
															+ '&idenJuz=' + idenJuz
															+ '&numExpe=' + numExpe
															+ '&rut=' + rut
															+ '&idenJui=' + idenJui
															+ '&fechaDem=' + fechaDem
															+ '&opcion=14', div);

}

function loadDates(obj) {

	var tipo = obj.value;

	if (tipo == 1) {
		document.getElementById('fechaInicio').value = tmp_fechaInicio;
		document.getElementById('fechaFin').value = tmp_fechaDemanda;
	}
	else{
		document.getElementById('fechaInicio').value = "";
		document.getElementById('fechaFin').value = "";
	}
}