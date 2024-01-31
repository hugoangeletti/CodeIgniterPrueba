/* Arabic Translation for jQuery UI date picker plugin. */
/* Khaled Alhourani -- me@khaledalhourani.com */
/* NOTE: monthNames are the original months names and they are the Arabic names, not the new months name فبراير - يناير and there isn't any Arabic roots for these months */


jQuery(function($){
	        $.datepicker.regional['es'] = {
	                clearText: 'Limpiar', clearStatus: '',
	                closeText: 'Cerrar', closeStatus: '',
	                prevText: '&#x3c;Ant', prevStatus: '',
	                prevBigText: '&#x3c;&#x3c;', prevBigStatus: '',
	                nextText: 'Sig&#x3e;', nextStatus: '',
	                nextBigText: '&#x3e;&#x3e;', nextBigStatus: '',
	                currentText: 'Hoy', currentStatus: '',
	                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
	                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
	                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
	                'Jul','Ago','Sep','Oct','Nov','Dic'],
	                monthStatus: '', yearStatus: '',
	                weekHeader: 'Sm', weekStatus: '',
	                dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
	                dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
                        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
	                dayStatus: 'DD', dateStatus: 'D, M d',
	                dateFormat: 'dd/mm/yy', firstDay: 0, 
	                initStatus: '', isRTL: false};
	        $.datepicker.setDefaults($.datepicker.regional['es']);
	});