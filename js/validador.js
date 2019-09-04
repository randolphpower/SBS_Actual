function formatear(nombre_obj){
			
			document.getElementById(nombre_obj).readOnly=false;
			document.getElementById(nombre_obj).value="";
			document.getElementById(nombre_obj).focus();
	
			document.getElementById("informacion").value = 1;		
	
}

//FUNCION SOLO NUMEROS Y LETRAS MINUSCULAS
function solonum_LetraMin(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = "1234567890abcdefghijklmnñopqrstuvwxyz-";//Se define todo el abecedario que se quiere que se muestre.
 	especiales = [9, 8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 127]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

	
	tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
	  	
	
	if(letras.indexOf(tecla) == -1 && !tecla_especial){
	//alert('Tecla no aceptada');
        return false;
      }
	
}

//FUNCION SOLO NUMEROS
function solonum(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = "1234567890";//Se define todo el abecedario que se quiere que se muestre.
 	especiales = [9, 8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 127]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

	
	tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
	  	
	
	if(letras.indexOf(tecla) == -1 && !tecla_especial){
	//alert('Tecla no aceptada');
        return false;
      }
	
}

//FUNCION SOLO NUMEROS Y LETRAS MAYUSCULAS PARA CODIGOS
function soloNum_LetraMay(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = "1234567890ABCDEFGHIJKLMNÑOPQRSTUVWXYZ-";//Se define todo el abecedario que se quiere que se muestre.
 	especiales = [46, 8, 107]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

	if(letras.indexOf(tecla) == -1){
	//alert('Tecla no aceptada');
        return false;
      }
	
	
	
}

//FUNCION QUE PERMITE SOLO LETRAS
function soloLetras(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNÑOPQRSTUVWXYZ.()";//Se define todo el abecedario que se quiere que se muestre.
    especiales = [8, 37, 39, 46, 6]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

    tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if(letras.indexOf(tecla) == -1 && !tecla_especial){
     //alert('Tecla no aceptada');
        return false;
      }
}

//FUNCION QUE PERMITE SOLO LETRAS MAYUSCULAS
function soloLetrasMay(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";//Se define todo el abecedario que se quiere que se muestre.
    especiales = [8, 37, 39, 6]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

    tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if(letras.indexOf(tecla) == -1 && !tecla_especial){
     //alert('Tecla no aceptada');
        return false;
      }
}

//FUNCION SOLO NUMEROS Y LETRAS MINUSCULAS
function solonum_Letras(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = " 1234567890abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";//Se define todo el abecedario que se quiere que se muestre.
 	especiales = [9, 8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 127]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

	
	tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
	  	
	
	if(letras.indexOf(tecla) == -1 && !tecla_especial){
	//alert('Tecla no aceptada');
        return false;
      }
	
}

//FUNCION SOLO NUMEROS Y LETRA J MAYUSCULA
function solonum_LetraJ(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = "1234567890J";//Se define todo el abecedario que se quiere que se muestre.
 	especiales = [9, 8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 127]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

	
	tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
	  	
	
	if(letras.indexOf(tecla) == -1 && !tecla_especial){
	//alert('Tecla no aceptada');
        return false;
      }
	
}

//FUNCION SOLO NUMEROS Y LETRA Kk POR RUT
function solonum_Rut(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = "1234567890Kk";//Se define todo el abecedario que se quiere que se muestre.
 	especiales = [9, 8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 127]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

	
	tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
	  	
	
	if(letras.indexOf(tecla) == -1 && !tecla_especial){
	//alert('Tecla no aceptada');
        return false;
      }
	
}

//FUNCION QUE PERMITE SOLO LETRAS SIN ACENTOS, SIN Ñ, NI TILDES
function soloLetras_sin_acentos(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letras = " eiouabcdefghijklmnopqrstuvwxyzAEIOUABCDEFGHIJKLMNOPQRSTUVWXYZ";//Se define todo el abecedario que se quiere que se muestre.
    especiales = [8, 9, 6, 95]; //Es la validación del KeyCodes, que teclas recibe el campo de texto.

    tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if(letras.indexOf(tecla) == -1 && !tecla_especial){
     //alert('Tecla no aceptada');
        return false;
      }
}

function formato_rut(dato){

	var rut=dato;
	var cadena="";
	
	if(rut.length==8){
		
		cadena=rut.substr(0,1)+"."+rut.substr(2,3)+"."+rut.substr(5,3)+"-"+rut.substr(-1);
		
	}else if(rut.length==9){
		cadena=rut.substr(0,2)+"."+rut.substr(2,3)+"."+rut.substr(5,3)+"-"+rut.substr(-1);
	}else{
		cadena=rut;
	}
	return cadena;
}

// FUNCION QUE VALIDA EL RUT POR EL DIGITO VERIFICADOR
function valida_rut(T)
{  	  
	   var error="";
	   var resp="";
	   
	   if(T.length < 8 || T.length > 9 ) {
		   	error="El rut debe contener al menos 8 digitos";
			resp=false;
			return [resp, error];
       } else {
            
		  var cadena=T;
		  var dgv_ingresado = cadena.charAt(T.length-1); //RUT sin digito verificador
		  var rut_sin_dgv = cadena.substr(0, T.length-1); //RUT sin digito verificador
		  
		  if(isNaN(rut_sin_dgv)==true){
			error="Verifique que el rut ingresado sea valido";
			resp=false;
			return [resp, error];
		  }		  
		  
		  var dgv_calculado="";
		  //alert("RUT: "+cadena+" - RUT sin DGV:"+rut_sin_dgv+" - DGV:"+dgv_ingresado);
		  //var T=rut_sin_dv;
		  
		  var M=0,S=1;
		  for(;rut_sin_dgv;rut_sin_dgv=Math.floor(rut_sin_dgv/10))
		  S=(S+rut_sin_dgv%10*(9-M++%6))%11;
		  
		  dgv_calculado = S?S-1:'K';
		  
		  if(dgv_ingresado==dgv_calculado){
				
				//error="EL RUT es correcto";
				resp=true;
				return [resp, error];
		  }else{
			  	error="Verifica el RUT o comprueba el digito verificador ya que no coinciden";
				resp=false;
				return [resp, error];
		  }
		  //return S?S-1:'K';
		  //alert(S?S-1:'K');
	   }
 }

//Validar correo electronico
function validar_email(e){
	
	emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    //Se muestra un texto a modo de ejemplo, luego va a ser un icono
    if (!emailRegex.test(e)) {
      var men = "El email ingresado no es válido.";
	  return false;	  
    }
	
	return true;
}

