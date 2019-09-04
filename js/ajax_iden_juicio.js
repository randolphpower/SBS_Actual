/******** FUNCION OBJETO AJAX PRINCIPAL *********/
function objetoAjax() {
    var xmlhttp = false;
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

/******** FUNCION INSERTAR IDENIFICADOR CLIENTE *********/
function insertar_op_idenJuicio(datos) {
    divResultado = document.getElementById('act_op_cheq');
    ajax = objetoAjax();
    ajax.open("GET", datos);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            divResultado.innerHTML = ajax.responseText;
        }
    }
    ajax.send(null)
}

/******** FIN FUNCION INSERTAR IDENIFICADOR CLIENTE *********/