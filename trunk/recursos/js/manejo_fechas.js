/**
 * Funcion que retorna la fecha actual.
  @param format Formato de la fecha, si no se especifica toma "dd/MM/yyyy":
	d		El día sin el cero (1 to 31)
	dd		El día con el cero a la izquierda (01 to 31)
	ddd		La abreviación del día ('lun' a 'dom')
	dddd	El nombre del día ('lunes' a 'domingo')
	M		El mes sin el cero (1 to 12)
	MM		El mes con el cero a la izquierda (01 to 12)
	MMM		La abreviación del mes ('ene' a 'dic')
	MMMM	El nombre del mes ('enero' a 'diciembre')
	yy		El año como número de 2 dígitos (00 to 99)
	yyyy	El año como un número de 4 dígitos
  @return Fecha actual en el formato dado.
*/
function fechaActual(format) {
	if (!format) format = "dd/MM/yyyy";
	var dias = new Array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
	var meses = new Array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
	var fecActual = new Date();
	var d = fecActual.getDate();
	var M = fecActual.getMonth() + 1;
	var yyyy = fecActual.getFullYear();
	var yy = fecActual.getYear();
	var dd = "";
	var MM = "";
	var dddd = dias[fecActual.getDay()];
	var MMMM = meses[M-1];
	var ddd = dddd.substring(0,3);
	var MMM = MMMM.substring(0,3);
	if (d < 10) dd = "0" + d;
	else dd = "" + d;
	if (M < 10) MM = "0" + M;
	else MM = "" + M;
	return format.replace("yyyy", ""+yyyy).replace("yy", ""+yy).replace("MMMM", MMMM).replace("MMM", MMM).replace("MM", MM).replace("M", ""+M).replace("dddd", dddd).replace("ddd", ddd).replace("dd", dd).replace("d", ""+d);
}
/**
 * Funcion que cambia las fecha de un formato a otro (formatos deben contener dd, MM, yyyy).
  @param fecha Fecha a cambiar.
  @param origen Formato de origen.
  @param destino Formato de destino.
  @return Fecha re-formateada.
*/
function cambiaFormatoFecha(fecha, origen, destino) {
	if (fecha.length != origen.length) return false;
	var dd = fecha.substring( origen.indexOf("dd"), origen.indexOf("dd")+2 );
	var MM = fecha.substring( origen.indexOf("MM"), origen.indexOf("MM")+2 );
	var yyyy = fecha.substring( origen.indexOf("yyyy"), origen.indexOf("yyyy")+4 );
	var nueva = destino.replace("dd", dd).replace("MM", MM).replace("yyyy", yyyy);
	return nueva;
}