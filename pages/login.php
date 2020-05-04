<?php 
session_start();
require "../includes/header_account.php"; 

session_destroy();

if(strtolower($_GET['men'])=="cerrar"){
	session_destroy() or die();
}else{
	if(($_GET['men']!="cerrar")&&($_GET['men']!="")){
		$mensaje=$_GET['men'];
	}else{
		$mensaje="";
	}
}

?>
<script type="text/javascript" src="../js/validador.js"></script>    
<script type="text/javascript">
    
	function objetoAjax(){
			/*var xmlhttp=false;
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
			
		/*	var xhttp;
				if (window.XMLHttpRequest) {
					// code for modern browsers
					xhttp = new XMLHttpRequest();
				}else{
					// code for IE6, IE5
					xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  		}
			return xhttp; */
	}
	
	
	function busca(){
			var user= document.formulario.user.value;
			var password=document.formulario.password.value;
			
			divResultado = document.getElementById('resultado');
			
			if((user=="") || (password=="")){
			
				divResultado.innerHTML='<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> Los campos de usuario y/o contraseña no pueden estar vacios. Intente de nuevo...</div>';
				return;
			
			}else{
			
			document.getElementById('precarga').style.display = 'block';
			document.getElementById('boto_ingresar').disabled=true;
			
			ajax=objetoAjax();
			var resp = 0;
			ajax.open("GET", '../controlador/script_global.php?user='+user+'&password='+password+'&opcion=1');
			ajax.onreadystatechange=function() {
				
					if (ajax.readyState==4 && ajax.status==200) {
						
						resp = ajax.responseText;
										
						if(resp==1){
							location.href = "../principal.php";
							return;
						}if(resp==0){
							document.getElementById('boto_ingresar').disabled=false;
							divResultado.innerHTML = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">ATENCION:</span> Problema en la conexion con la base de datos. Intente mas tarde...</div>';
							document.formulario.password.value="";
							document.getElementById('precarga').style.display = 'none';
							return;
						}else{
							document.getElementById('boto_ingresar').disabled=false;
							divResultado.innerHTML = ajax.responseText
							document.formulario.password.value="";
							document.getElementById('precarga').style.display = 'none';
						}
					}
			};
			ajax.send(null);
			
			}//Fin validacion
	}
	
	
	
	function presiono_enter(e) {
		key = e.keyCode || e.which;
		tecla = String.fromCharCode(key).toString();
		especiales = [13]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.
	
		
		
		// ANTES DE MODIFICAR
		tecla_especial = false
		for(var i in especiales) {
			if(key == especiales[i]) {
				tecla_especial = true;
				break;
			}
		}
		
		if(tecla_especial==true){
			busca();
		  }
	}
	
</script>
<div id="resultado" class="" align="center"></div>
    <div class="account-bg">
       
       <?php
              
                if($mensaje!=""){
                    ?>
                <div class="alert alert-warning alert-dismissible text-center" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?php echo $mensaje; ?></div>
			   <?php
                }else{
              ?>
              <div class="alert text-center" role="alert">&nbsp;</div>
              <?php
                }
              ?>
       
        <div class="card-box m-b-0">
            <div class="text-xs-center m-t-20">
                <a href="index.html" class="logo">
                    <i class="zmdi zmdi-group-work icon-c-logo"></i>
                    <span>ServiCobranza..</span>
                </a>
            </div>
            <div class="m-t-30 m-b-20">
                <div class="col-xs-12 text-xs-center">
                    <h6 class="text-muted text-uppercase m-b-0 m-t-0">Sign In</h6>
                </div>
                <form class="form-horizontal m-t-20" name="formulario" role="form">
				  <fieldset>	
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" placeholder="Usuario" name="user" id="user" tabindex="1" type="text" autofocus value="" onKeyPress="return soloLetras_sin_acentos(event); return evitapegar(event);">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" placeholder="Contrase&ntilde;a" name="password" id="password" type="password" tabindex="2" onKeyUp="presiono_enter(event)" value="">
                        </div>
                    </div>
                    
                    <div class="form-group text-center m-t-30">
                        <div class="col-xs-12">
                            <button type="button" onClick="busca()" id="boto_ingresar" tabindex="3" class="btn btn-success btn-block waves-effect waves-light">Ingresar</button>
                        </div>
                    </div>                    
                   </fieldset> 
                   
                    <div id="precarga" align="center" style="display:none;"><img src="../logos/gif-load.gif" width="25" height="25"></div>
                    
                </form>

            </div>
        </div>
    </div>
    <!-- end card-box-->

    </div>
    <!-- end wrapper page -->


<?php require "../includes/footer_account.php"; ?>
