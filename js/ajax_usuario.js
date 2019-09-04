/******** FUNCION OBJETO AJAX PRINCIPAL *********/
function objetoAjax_Global(){
       /* var xmlhttp=false;
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
        return xmlhttp;*/

	var xhttp;

	if (window.XMLHttpRequest)
	{
		//El explorador implementa la interfaz de forma nativa
		xhttp = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		//El explorador permite crear objetos ActiveX
		try {
			xhttp = new ActiveXObject("MSXML2.XMLHTTP");
		} catch (e) {
			try {
				xhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {

			}
		}
	}

	return xhttp;

}

function valida_usuario(accion){

	divResultado = document.getElementById('usuarios_guarda');

	var datos = new Array();
	datos = valida_rut(document.form.rut.value);

	var nombre = document.form.nombre.value;
	var apellido = document.form.apellido.value;
	var email = document.form.email.value;
	var contrasena = document.form.contrasena.value;
	var celular = document.form.celular.value;
	var activo = document.form.activo.value;
	var rol = document.form.rol.value;

	divResultado.innerHTML='<div align="center" ><img src="logos/gif-load.gif" width="25" height="25"></div>';

	if(datos[0]==false){

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> '+datos[1]+'.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("rut").focus();
		return;

	}

	if(nombre.length==0 || nombre==""){

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> El Usuario debe tener un nombre valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("nombre").focus();
		return;

	}

	if(apellido.length==0 || apellido==""){

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> El Usuario debe tener un Apellido valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("apellido").focus();
		return;

	}

	if(validar_email(email)==false){

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> El Usuario debe tener un Email Valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("email").focus();
		return;

	}

	if(accion=="insertar" || (accion=="actualizar" && contrasena!=""))

	if(contrasena.length<6){

			mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> El usuario debe tener una contraseña mayor a cinto (05) digitos.</div>';
			divResultado.innerHTML = mensaje;
			document.getElementById("contrasena").focus();
			return;
	}

	if(celular==""){

		mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡ATENCION!</strong> El Usuario debe tener un telefono de contacto valido.</div>';
		divResultado.innerHTML = mensaje;
		document.getElementById("celular").focus();
		return;

	}

	if(accion=="insertar"){

		document.getElementById("guardar").disabled=true;

		var resp="";
		var mensaje="";

		ajax=objetoAjax_Global();
		ajax.open("GET", 'controlador/script_usuario.php?rut='+document.form.rut.value+'&nombre='+document.form.nombre.value+'&apellido='+document.form.apellido.value+'&contrasena='+document.form.contrasena.value+'&email='+document.form.email.value+'&celular='+document.form.celular.value+'&activo='+document.form.activo.value+'&rol='+document.form.rol.value+'&opcion=1');

		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					resp=ajax.responseText;

						if(resp=="inserto"){

							mensaje='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Bien Hecho!</strong>  El usuario con el <b>RUT '+formato_rut(document.form.rut.value)+'</b>  a sido guardado con exito.</div>';
							divResultado.innerHTML = mensaje;


						}else if(resp=="existe"){
							mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="justify"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>¡Error!</strong> El usuario bajo el <b>RUT '+formato_rut(document.form.rut.value)+'</b> se encuentra registrado.</div>';
							divResultado.innerHTML = mensaje;

						}

						document.getElementById("guardar").disabled=false;

                        //divResultado.innerHTML = ajax.responseText

				}
		}
		ajax.send(null)

	}else if(accion=="actualizar"){

		document.getElementById("guardar").disabled=true;

		var resp="";
		var mensaje="";

		ajax=objetoAjax_Global();
		ajax.open("GET", 'controlador/script_usuario.php?rut='+document.form.rut.value+'&nombre='+document.form.nombre.value+'&apellido='+document.form.apellido.value+'&contrasena='+document.form.contrasena.value+'&email='+document.form.email.value+'&celular='+document.form.celular.value+'&activo='+document.form.activo.value+'&rol='+document.form.rol.value+'&opcion=2');

		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					resp=ajax.responseText;

						if(resp=="actualizo"){

							mensaje='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Bien Hecho!</strong>  El usuario con el <b>RUT '+formato_rut(document.form.rut.value)+'</b>  a sido actualizado con exito. Para visualizar la actualización refresque la página</div>';
							divResultado.innerHTML = mensaje;


						}else if(resp=="fallo"){
							mensaje='<div class="alert alert-danger alert-dismissible" role="alert" align="justify"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error!</strong> Se ha producido un error al intentar actualizar datos del usuario, por favor intente nuevamente...</div>';
							divResultado.innerHTML = mensaje;

						}
						document.getElementById("guardar").disabled=false;

                        //divResultado.innerHTML = ajax.responseText

				}
		}
		ajax.send(null)

	}

}