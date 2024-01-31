$(function() {
    $(".num").keydown(function(e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right
                                (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
});

function f_submit_replace(form, validar, path_replace) {
    var post_url = form.attr("action");
    var post_data = form.serialize();

    if (validar) {
        $.ajax({
            type: 'POST',
            url: post_url,
            data: post_data,
            dataType: "json",
            success: function(registro) {
                alert(registro.texto);
                if (registro.estado)
                {
                    window.location.replace(path_replace);
                }
            }
        });
    }
    return false;
}

function f_eliminar_reload(form, confirmar) {
    var post_url = form.attr("action");
    var post_data = form.serialize();

    if (confirmar) {
        $.ajax({
            type: 'POST',
            url: post_url,
            data: post_data,
            dataType: "json",
            success: function(registro) {
                if (registro.estado == 1)
                {
                    window.location.reload();
                }
                else
                {
                    alert(registro.texto);
                }
            }
        });
    }
    return false;
}

function ocultarMensaje(divMensaje)
{
    if (document.getElementById(divMensaje) != null)
    {
    setTimeout(function(){document.getElementById(divMensaje).style.visibility='hidden';},5000);
    }
}

var nav4 = window.Event ? true : false;
function acceptNum(evt)
{
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
        var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57));
}

var nav4 = window.Event ? true : false;
function acceptNumyPuntos(evt)
{
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 ':'=58 '.'=46
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57) || key ==46 );
}


// ocultar Mensaje
$(document).ready(function() {
    setTimeout(function() {
        $(".ocultarMensaje").fadeOut(1500);
    },3000);
}); 


    jQuery(function($){
   $("#cuit").mask("99-99999999-9");
   $("#cbu").mask("99999999-99999999999999");
  
});