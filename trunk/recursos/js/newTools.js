//--------------------------------------------------------------------------------
//								  C O N S T A N T E S
//--------------------------------------------------------------------------------
var aAcute = '\u00e1';
var eAcute = '\u00e9';
var iAcute = '\u00ed';
var oAcute = '\u00f3';
var uAcute = '\u00fa';
var AAcute = '\u00c1';
var EAcute = '\u00c9';
var IAcute = '\u00cd';
var OAcute = '\u00d3';
var UAcute = '\u00da';
var nTilde = '\u00f1';
var NTilde = '\u00d1';
var nbsp = '\u00a0';
var amp = '\u0022';
var lt = '\u003c';
var gt = '\u003e';
var quot = '\u0022';
var apos = '\u0027';
var copy = '\u00a9';
var reg = '\u00ae';
var euro = '\u20ac';
var frac14 = '\u00bc';
var frac12 = '\u00bd';
var frac34 = '\u00be';
var quest = '\u00bf';


//--------------------------------------------------------------------------------
//								V A L I D A C I O N E S
//--------------------------------------------------------------------------------

/** * Función para validación de campos utilizando íconos
	  @param obj: Objeto DOM que se va a validar.
	  @param controlValue: Valor del objeto DOM (obj.value).
	  @param tipo: Cadena que contiene el tipo de validación (mail, float, alpha, num, date, etc.). 
*/
function valCampo(obj, controlValue, tipo) {
	var filter = "";
	var errorMsg = "";
	
	var img = document.getElementById(obj.name+"V");
	if (img == null)
		return;
	
	if (controlValue == "") {
		img.src = '../bbve_ve_web_pub/images/bNC.gif';
		img.title = "";
		return;
	}
	
	if (tipo=="mail") {
		filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		errorMsg = "Debe ser un email.\nEj.: usuario@dominio.com";
	} else if (tipo=="float") {
		filter = /^[\+\-]?[0-9]*[\.\,]?[0-9]*$/;
		errorMsg = "Debe ser un número real.\nEj.: 2500.25";
	} else if (tipo=="int") {
		filter = /^[\+\-]?[0-9]*$/;			
	} else if (tipo=="alpha") {
		filter = /^[a-zA-ZÑñáéíóúÁÉÍÓÚüÜ\ \.\,\;\-]*$/;
		errorMsg = "Debe contener s"+oAcute+"lo caracteres alfab"+eAcute+"ticos";
	} else if (tipo=="alphanum") {
		filter = /^[a-zA-Z0-9ÑñáéíóúÁÉÍÓÚüÜ\ \.\,\;\-]*$/;
		errorMsg = "Debe contener s"+oAcute+"lo caracteres alfanum"+eAcute+"ricos";
	} else if (tipo=="num") {
		filter = /^[0-9]*$/;
		errorMsg = "Debe ser un n"+uAcute+"mero";
	} else if (tipo=="phone") {
		filter = /^[0-9]{7}$/;
		errorMsg = "Debe ser un n"+uAcute+"mero de 7 dígitos";
	} else if (tipo=="any") {
		filter = /^[a-zA-Z0-9ÑñáéíóúÁÉÍÓÚüÜ\\# \.\,\;\(\)\-\_\"\'\?\¿\¡\!\/\:\=\*\+\%\$\&]*$/;
		errorMsg = "";
	}  else if (tipo=="date") {
		filter = /^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{2,4}$/;
		errorMsg = "Debe ser una fecha.\nFormato: dd/MM/yyyy";
	}  else if (tipo=="cta") {
		filter = /^[0-9]{4}\-[0-9]{4}\-[0-9]{2}\-[0-9]{10}$/;
		errorMsg = "Debe ser un n"+uAcute+"mero de cuenta.\nFormato: 9999-9999-99-9999999999";
	}
	
	if (filter.test(controlValue)) {
		img.src = '../bbve_ve_web_pub/images/valid.gif';
		img.title = "Campo correcto";
		
		if (tipo=="any" || tipo=="alpha" || tipo=="alphanum") {
			var car = controlValue.charAt(0);
			var cant = 0;
			for (var i=1; i<controlValue.length;i++) {
				if (car == " ") {
					car = controlValue.charAt(i);
					continue;
				}
				var carNew = controlValue.charAt(i);
				if (carNew == " ") {
					car = " ";
					cant = 0;
					continue;
				}
				if (car.toUpperCase() == carNew.toUpperCase())
					cant++;
				else {
					cant = 0;
					car = carNew;
				}				
				if (cant == 2) {
					img.src = '../bbve_ve_web_pub/images/invalid.gif';
					img.title = "No es v" + aAcute + "lido 3 caracteres iguales consecutivos";
					return;
				}
			}
			if (controlValue.length < 2) {
				img.src = '../bbve_ve_web_pub/images/invalid.gif';
				img.title = "Debe tener al menos 2 caracteres";
				return;
			}
		}		
		if (tipo=="phone") {
			var car = controlValue.charAt(0);
			var cant = 0;
			for (var i=1; i<controlValue.length;i++) {
				if (car == " ") {
					car = controlValue.charAt(i);
					continue;
				}
				var carNew = controlValue.charAt(i);
				if (carNew == " ") {
					car = " ";
					cant = 0;
					continue;
				}
				if (car.toUpperCase() == carNew.toUpperCase())
					cant++;
				else {
					cant = 0;
					car = carNew;
				}				
				if (cant == 4) {
					img.src = '../bbve_ve_web_pub/images/invalid.gif';
					img.title = "No es v" + aAcute + "lido 5 d"+iAcute+"gitos iguales consecutivos";
					return;
				}
			}				
		}		
	} else {
		img.src = '../bbve_ve_web_pub/images/invalid.gif';
		img.title = errorMsg;
	}
}

/** * Función para cambiar los íconos dependiendo si el valor es correcto.
	  @param obj: Objeto DOM que se va a validar.
	  @return: Booleano que indica si todo está correcto.
*/
function valCorrecto(obj) {
	var img = document.getElementById(obj.name+"V");
	if (img == null)
		return true;
		
	if (img.src.indexOf("invalid") == -1) {
		img.src = '../bbve_ve_web_pub/images/bNC.gif';
		img.title = "";
		return true
	}

	return false;
}

/** * Función para cambiar los íconos dependiendo si el valor es requerido.
	@param obj: Objeto DOM que se va a validar.
	@param tipo: Cadena que contiene el tipo de objeto ("T"ext o "S"elect).
	@return: Booleano que indica si todo está correcto.
*/
function valRequerido(obj, tipo) {
	var malo = false;
	
	var name = obj.getAttribute("name");
	if (!name)
		return true;
	if (tipo == "T") {
		malo = ((name.charAt(name.length-1) != 'N' && obj.value == "") || (name.charAt(name.length-1) == 'N' && parseFloat(obj.value) == 0));
	} else {
		malo = (obj.selectedIndex == 0 || obj.value == "");
	}
	if (malo && name.substring(0,2) == "ob") {
		var img = document.getElementById(obj.name+"V");
		if (img == null) 
			return true;
		img.src = '../bbve_ve_web_pub/images/invalid.gif';
		img.title = "Este campo es obligatorio";
		return false;
	}
	return true;
}

/** * Función para la validación de todos los campos correctos y requeridos de una pantalla.
	  @return: Booleano que indica si todo está correcto.
*/
function validarTodo() {
	var i = 0;
	var ready = true;
	var inputs = document.getElementsByTagName("input");
	for (i=0; i < inputs.length; i++) {
		if (inputs[i].type == "text") {
			var type = inputs[i].getAttribute("onKeyUp");
			if (type) {
				var algo = new String(type);
				algo = algo.substring( algo.indexOf("'") + 1, algo.lastIndexOf("'") );
				valCampo(inputs[i], inputs[i].value, algo.toString());
			}			
			if (!valRequerido(inputs[i], "T")  || !valCorrecto(inputs[i]))
				if (inputs[i].disabled != true) {
					ready = false;
				}
		}
	}

	var selects = document.getElementsByTagName("select");
	for (i=0; i < selects.length; i++) {
		if (!valRequerido(selects[i], "C"))
			if (selects[i].disabled != true) {
				ready = false;
			}
	}
	
	if (!ready)
		alert("Verifique que todos los campos estén correctos");
	
	return ready;
}

/** * Función para quitar el ícono al seleccionar un valor en un objeto select.
	@param obj: Objeto DOM que se va a validar.
*/
function leaveCombo(obj) {
	var img = document.getElementById(obj.name+"V");
	if (img == null)
		return;
	if (obj.selectedIndex != 0) {
		img.src = '../bbve_ve_web_pub/images/bNC.gif';
		img.title = "";
	}		
}

/** * Función para validar cajas de texto utilizando el keyPress.
	@param val: Valor del objeto.
	@param e: Siempre fijo, "event".
	@param tipo: Cadena con el tipo de dato.
	@return: bool
	@example: onKeyPress="return valText(this.value, event, 'alpha');"
*/
function valText(val, e, tipo) {
	var filter;
	var keynum;
	
	if (tipo=="float") {
		filter = /^[\+\-]?[0-9]*[,\.]?[0-9]{0,2}$/;			
	} else if (tipo=="floatP") {
		filter = /^[\+]?[0-9]*[,\.]?[0-9]{0,2}$/;			
	} else if (tipo=="floatN") {
		filter = /^[\-]{1}[0-9]*[,\.]?[0-9]{0,2}$/;
	} else if (tipo=="int") {
		filter = /^[\+\-]?[0-9]*$/;
	} else if (tipo=="intP") {
		filter = /^[\+]?[0-9]*$/;
	} else if (tipo=="intN") {
		filter = /^[\-]{1}[0-9]*$/;
	} else if (tipo=="alpha") {
		filter = /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\ \.\,\;\(\)\-\_]*$/;
	} else if (tipo=="alphanum") {
		filter = /^[a-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ\ \.\,\;\(\)\-\_]*$/;
	} else if (tipo=="num") {
		filter = /^[0-9]*$/;
	} else if (tipo=="any") {
		filter = /^[a-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ\\# \.\,\;\(\)\-\_\"\'\?\¿\¡\!\/\:\=\*\+\%\$\&]*$/;
	}
	
	keynum = (document.all) ? e.keyCode : e.which; 

	if (keynum == 0 || keynum==8)
		return true;
	
	keychar = String.fromCharCode(keynum);

	return filter.test(val+keychar);
}

/** * Función que valida un double en una caja de texto.
	@param obj: Objeto.
	@param e: Siempre fijo, "event".
	@return: bool
	@example: onKeyPress="return valTextDouble(this.value, event);"
*/
function valTextDouble(obj, e) {
	var filter = /^[0-9]*$/;
	var keynum = (document.all) ? e.keyCode : e.which; 
	var keychar = "";

	if (keynum != 0 && keynum != 8) {
		var len = parseInt(obj.getAttribute("maxlength"));
		if (len == obj.value.length)
			return false;
	
		keychar = String.fromCharCode(keynum);

		if (!filter.test(keychar))
			return false;
			
		obj.value = fillLeft(obj.value.replace(".", "") + keychar, "0", 3);
		obj.value = colocarPuntoDecimal(obj.value, 2);
		obj.value = formatFloat(obj.value.substring(0, obj.value.length-1));
		return true;
	} else if (keynum == 8) {
		obj.value = fillLeft(obj.value.substring(0, obj.value.length-1).replace(".", ""), "0", 3);
		obj.value = colocarPuntoDecimal(obj.value, 2);
		return false;
	} else
		return false;
}

//--------------------------------------------------------------------------------
//								U T I L I D A D E S
//--------------------------------------------------------------------------------

/** * Función utilizada por la funcion montoEscrito.
*/
function letras(c,d,u) {
	var centenas,decenas,decom
	var lc=""
	var ld=""
	var lu=""
	centenas=eval(c);
	decenas=eval(d);
	decom=eval(u);
	switch(centenas) {
		case 0: lc=""; break;
		case 1: {
			if (decenas==0 && decom==0)
				lc="cien"
			else
			lc="ciento ";
		} break;
		case 2: lc="doscientos "; break;
		case 3: lc="trescientos "; break;
		case 4: lc="cuatrocientos "; break;
		case 5: lc="quinientos "; break;
		case 6: lc="seiscientos "; break;
		case 7: lc="setecientos "; break;
		case 8: lc="ochocientos "; break;
		case 9: lc="novecientos "; break; 
	} 
	
	switch(decenas) {
		case 0: ld="";break;
		case 1: { 
			switch(decom) {
				case 0:ld="diez";break;
				case 1:ld="once";break;
				case 2:ld="doce";break;
				case 3:ld="trece";break;
				case 4:ld="catorce";break;
				case 5:ld="quince";break;
				case 6:ld="dieciseis";break;
				case 7:ld="diecisiete";break;
				case 8:ld="dieciocho";break;
				case 9:ld="diecinueve";break;
			}
		} break;
		case 2:ld="veinte";break;
		case 3:ld="treinta";break;
		case 4:ld="cuarenta";break;
		case 5:ld="cincuenta";break;
		case 6:ld="sesenta";break;
		case 7:ld="setenta";break;
		case 8:ld="ochenta";break;
		case 9:ld="noventa";break; 
	}
	
	switch(decom) {
		case 0: lu="";break;
		case 1: lu="uno";break;
		case 2: lu="dos";break;
		case 3: lu="tres";break;
		case 4: lu="cuatro";break;
		case 5: lu="cinco";break;
		case 6: lu="seis";break;
		case 7: lu="siete";break;
		case 8: lu="ocho";break;
		case 9: lu="nueve";break; 
	}

	if (decenas==1) {
		return lc+ld;
	}
	if (decenas==0 || decom==0) {
		return lc+" "+ld+lu;
	} else {
		if (decenas==2) {
		ld="veinti";
		return lc + ld + lu.toLowerCase();
		} else {
			return lc+ld+" y "+lu
		}
	}
}

/** * Función que me retorna un monto escrito en español.
	  @param n: Número.
	  @return: Cadena con el monto en letras.
*/
function montoEscrito(n) { 
	var m0,cm,dm,um,cmi,dmi,umi,ce,de,un,hlp,decimal;

	if (isNaN(n)) {
		alert("La Cantidad debe ser un valor Numérico.");
		return null
	}
	m0= parseInt(n/ 1000000000000); rm0=n% 1000000000000;
	m1= parseInt(rm0/100000000000); rm1=rm0%100000000000;
	m2= parseInt(rm1/10000000000); rm2=rm1%10000000000;
	m3= parseInt(rm2/1000000000); rm3=rm2%1000000000;
	cm= parseInt(rm3/100000000); r1= rm3%100000000;
	dm= parseInt(r1/10000000); r2= r1% 10000000;
	um= parseInt(r2/1000000); r3= r2% 1000000;
	cmi=parseInt(r3/100000); r4= r3% 100000;
	dmi=parseInt(r4/10000); r5= r4% 10000;
	umi=parseInt(r5/1000); r6= r5% 1000;
	ce= parseInt(r6/100); r7= r6% 100;
	de= parseInt(r7/10); r8= r7% 10;
	un= parseInt(r8/1);
	//r9=r8%1; 
	
	if (n< 1000000000000 && n>=1000000000) {
		tmp=n.toString();
		s=tmp.length;
		tmp1=tmp.slice(0,s-9)
		tmp2=tmp.slice(s-9,s);

		tmpn1=getNumberLiteral(tmp1);
		tmpn2=getNumberLiteral(tmp2);

		if (tmpn1.indexOf("un")>=0)
			pred=" bill" + oAcute + "n "
		else
			pred=" billones "
		return tmpn1+ pred +tmpn2;
	}

	if (n<10000000000 && n>=1000000) {
		mldata=letras(cm,dm,um); 
		hlp=mldata.replace("Un","*");
		if (hlp.indexOf("*")<0 || hlp.indexOf("*")>3) {
			mldata=mldata.replace("uno","un");
			mldata+=" millones ";
		} else {
			mldata="un mill" + oAcute + "n ";
		}
		mdata=letras(cmi,dmi,umi);
		cdata=letras(ce,de,un);
		if (mdata!="   ") {
			if (n == 1000000) {
			   mdata=mdata.replace("uno","un") + "de";
			} else {
			   mdata=mdata.replace("uno","un")+" mil ";
			}
		}

		return (mldata+mdata+cdata);
	} 
	
	if (n<1000000 && n>=1000) {
		mdata=letras(cmi,dmi,umi);
		cdata=letras(ce,de,un);
		hlp=mdata.replace("Un","*");
		if (hlp.indexOf("*")<0 || hlp.indexOf("*")>3) {
			mdata=mdata.replace("uno","un");
			return (mdata +" mil "+cdata);
		} else
			return ("mil "+ cdata);
	} 
	
	if (n<1000 && n>=1) {
		return (letras(ce,de,un));
	}
	if (n==0) {
		return " cero";
	}
	return "No disponible"
}

/** * Función que me retorna una fecha escrita en es_VE.
	  @param fecha: Fecha en formato dd/MM/yyyy.
	  @param tipo: Caracter para el tipo de formato.
	  @return: Cadena con la fecha en letras:
	  		   Si tipo es "A", "##, ## de ## de ####".
	  		   Si tipo es "B", "## días del mes de ## de ####".
   	  		   Si tipo es "B2", "######## (##) días del mes de ## de ####".
	  		   Si tipo es "C", "## días del mes de ## del año ####".
	  		   Si tipo es "C2", "######## (##) días del mes de ## del año ####".
*/
function fechaEscrita(fecha, tipo) {
	if (!tipo)
		tipo == "A";

	var dd = fecha.substring(0,2);
	var mm = fecha.substring(3,5);
	var yyyy = fecha.substring(6,10);
	
	var dia = parseInt(dd, 10);
	var mes = parseInt(mm, 10);
	var anio = parseInt(yyyy, 10);
	
	var date = new Date();
	date.setDate(dia);
	date.setMonth(mes-1);
	date.setFullYear(anio);
	
	var dias = new Array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
	var meses = new Array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
	
	if (tipo == "A")
		return dias[date.getDay()] + ", " + dd + " de " + meses[mes-1] + " de " + yyyy;

	if (tipo == "B")
		return dd + " d" + iAcute + "as del mes de " + meses[mes-1] + " de " + yyyy;
		
	if (tipo == "B2")
		return montoEscrito(dd) + " (" + dd + ") d" + iAcute + "as del mes de " + meses[mes-1] + " de " + yyyy;
		
	if (tipo == "C")
		return dd + " d" + iAcute + "as del mes de " + meses[mes-1] + " del a" + nTilde + "o " + yyyy;
		
	if (tipo == "C2")
		return montoEscrito(dd) + " (" + dd + ") d" + iAcute + "as del mes de " + meses[mes-1] + " del a" + nTilde + "o " + yyyy;
}

/** * Función que selecciona todo el contenido de un Input Text
	  @param objInput: Input Text.
*/
function selectText(objInput) { 
    var valor_input = objInput.value; 
    var longitud = valor_input.length; 

    if (objInput.setSelectionRange) { 
        objInput.focus(); 
        objInput.setSelectionRange (0, longitud); 
    } 
    else if (objInput.createTextRange) { 
        var range = objInput.createTextRange() ; 
        range.collapse(true); 
        range.moveEnd('character', longitud); 
        range.moveStart('character', 0); 
        range.select(); 
    } 
}

/** * Función que quita los espacios al comienzo y al final de una cadena.
	  @param cad Cadena.
	  @return Cadena trimeada.
*/
function trim(cad) {
	/*if (cad.length > 0)
		while (cad.charAt(0) == ' ') {
			cad = cad.substring(1);
		}
	if (cad.length > 0)
		while (cad.charAt(cad.length-1) == ' ')
			cad = cad.substring(0, cad.length-1);
	return cad;*/
	return cad.replace(/^(\s|&nbsp;|\0)+|(\s|&nbsp;|\0)+$/g,"");
}

/** * Función que quita los espacios y al final de una cadena.
	  @param cad Cadena.
	  @return Cadena trimeada por la derecha.
*/
function rTrim(cad) {
	return cad.replace(/(\s|&nbsp;|\0)+$/,"");
}

/** * Función que quita los espacios al comienzo de una cadena.
	  @param cad Cadena.
	  @return Cadena trimeada por la izquierda.
*/
function lTrim(cad) {
	return cad.replace(/^(\s|&nbsp;|\0)+/,"");
}

/** * Función que quita los ceros al comienzo de una cadena.
	  @param cad Cadena.
	  @return Cadena sin ceros a la izquierda.
*/
function trimNumber(cad) {
	cad = trim(cad).replace(/^0+/,"");
	return (cad == "" ? "0" : (cad.charAt(0) == '.' ? "0"+cad : cad));
}

/** * Función que aplica un trim a todos los Inputs Text del document.
*/
function trimAll() {
	var inputs = document.getElementsByTagName("input");
	for (i=0; i < inputs.length; i++) {
		if (inputs[i].type == "text") {
			/*var type = inputs[i].getAttribute("onKeyUp");
			if (type) {
			}*/
			var name = inputs[i].getAttribute("name");
			if (name.charAt(name.length - 1) == 'N') {
				inputs[i].value = trimNumber(inputs[i].value);
			} else {
				inputs[i].value = trim(inputs[i].value);
			}
		}
	}
}

/** * Funcion que rellena una cadena con el caracter dado por la izquierda.
	  @param cad Cadena original
	  @param char Caracter con el que se llenará.
	  @param size Tamaño final de la cadena
	  @return cadena rellenada. 
*/
function fillLeft(cad, char, size) {
	while (cad.length < size)
		cad = char + cad;
	return cad;
}

/** * Funcion que rellena una cadena con el caracter dado por la derecha.
	  @param cad Cadena original
	  @param char Caracter con el que se llenará.
	  @param size Tamaño final de la cadena
	  @return cadena rellenada.
*/
function fillRight(cad, char, size) {
	while (cad.length < size)
		cad = cad + char;
	return cad;
}

/** * Funcion que formatea un numero float.
	  @param text Número a formatear.
	  @param dec Cantidad de decimales.
	  @return cadena formateada.
*/
function formatFloat(text, dec, separador) {
	if (!separador)
		separador = ".";
	text = text.replace(",",".");
	if (text.length == 0)
		return "0"+separador+"00";

	if (text.charAt(0) == '.')
		text = "0" + text;
		
	var pos = text.indexOf(".");	
	if (pos == -1)
		text = text + separador + fillRight("", "0", dec);
	else {
		var parts = text.split(".");
		if (parts[1].length <= dec)
			text = trimNumber (parts[0]) + separador + fillRight(parts[1], "0", dec);
		else {
			while (parts[1].length > dec) {
				var aux = parts[1].charAt(parts[1].length-1);
				var aux2 = parts[1].charAt(parts[1].length-2);
				aux2 = (aux < 5 ? aux2 : "" + (parseInt(aux2,10)+1));
				parts[1] = parts[1].substring(0, parts[1].length-2) + aux2;
			}
				
			text = trimNumber (parts[0]) + separador + parts[1], "0", dec;
		}
	}
	
	return text;
}

/** * Funcion que formatea un numero float.
	  @param text Número a formatear.
	  @return cadena formateada.
*/
function formatMonto(text) {
	var parts = text.split(".");
	var nuevo = "";
	var i = 0;
	for (i = 1; i <= parts[0].length; i++) {
		if ((i-1) % 3 == 0 && i != 1)
			nuevo = "." + nuevo;
		nuevo = parts[0].charAt( parts[0].length-i ) + nuevo;
	}
		
	return nuevo + "," + parts[1];
}

/** * Funcion que agrega el punto decimal dejando la cantidad de decimales dados.
	  @param text Número a formatear.
	  @param dec Cantidad de decimales.
	  @return cadena con el punto.
*/
function colocarPuntoDecimal(text, dec) {
	text = trim(text);
	if (text.length > dec)
		return text.substring(0, text.length-dec) + "." + text.substring(text.length-dec, text.length);
	else
		return text;
}

/** * Busca un value en un select.
      @param selObj Objeto Select.
      @param value  Valor a buscar.
      @param type  Tipo de busqueda (Value or Text).
      @return Posicion del value en el select. -1 si no se encuentra.
*/ 
function findOnSelect(selObj, value, type) {
	if (!type)
		type = "V";

	var options = selObj.options;
	var i = 0;
	for (i=0; i<options.length; i++) {
		if ((type == "V" && value == options[i].value.substring(0,value.length)) || (type == "T" && value == options[i].text))
			return i;
	}
	return -1;
}

/** * Funcion que selecciona un valor en un select o el primero si no lo consigue.
*/
function selectOrFirst(selObj, value, type) {
	if (!type)
		type = "V";

	var pos = findOnSelect(selObj, value, type);
	if (pos == -1)
		selObj.selectedIndex = 0;
	else
		selObj.selectedIndex = pos;
}

/** * Funcion que ordena las opciones de un objeto select dependiendo de su value.
*/
function sortSelectByValue(selObj) {
	var options = selObj.options;
	var array = new Array();
	for (i=1; i<options.length; i++)
		array.push(options[i]);
	array.sort(function(o1, o2) { 
		if (o1.value < o2.value)
			return -1;
		else if (o1.value > o2.value)
			return 1;
		else
			return 0;
	});
	while (selObj.options.length > 1) {
		selObj.remove(1);
	}
	for (i=0; i<array.length; i++) {
		var opE=document.createElement('option');
		opE.text=array[i].text;
		opE.value=array[i].value;
		try {
	  		selObj.add(opE,null); // standards compliant
	  	} catch(ex) {
	  		selObj.add(opE); // IE only
	  	}
	}		
}

/** * Funcion que ordena las opciones de un objeto select dependiendo de su text.
*/
function sortSelectByText(selObj) {
	var options = selObj.options;
	var array = new Array();
	for (i=1; i<options.length; i++)
		array.push(options[i]);
	array.sort(function(o1, o2) { 
		if (o1.text < o2.text)
			return -1;
		else if (o1.text > o2.text)
			return 1;
		else
			return 0;
	});
	while (selObj.options.length > 1) {
		selObj.remove(1);
	}
	for (i=0; i<array.length; i++) {
		var opE=document.createElement('option');
		opE.text=array[i].text;
		opE.value=array[i].value;
		try {
	  		selObj.add(opE,null); // standards compliant
	  	} catch(ex) {
	  		selObj.add(opE); // IE only
	  	}
	}		
}

/** * Funcion que lanza unevento nacar.
	  @param evento Cadena con el codigo del evento a lanzar.
*/ 
function lanzarEvento(evento) {
	document.forms[0].evento.value = evento;
	document.forms[0].submit();
}

/** * Funcion que crea un popup con un GIF loader, ejecuta un evento dado y se cierra al cargar la nueva página.
	  @param evento Codigo del evento a lanzar.
*/
function crearLoader(evento) {
	var width = 250;
	var height = 120;
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	var vent = window.open('/bbve_ve_web_pub/html/loader.html', '_blank', 'width='+width+', height='+height+', left='+left+', top='+top+', scrollbars=NO, toolbar=NO, titlebar=NO, status=NO, resizable=NO, menubar=NO, location=NO, directories=NO');
	if (evento)
		setTimeout("lanzarEvento('"+evento+"')", 3000);
}

/** * Funcion que cierra el popup creado anteriormente. Se coloca al final de la primera página cargada por el loader
*/
function cerrarLoader() {
	document.cookie = "closeLoader";
}

/** * Funcion que agrega un eventListener.
	  @param element Elemento a agregar el listener.
	  @param type Evento.
	  @param func Funcion a ejecutar cuando ocurra el evento "type".
*/
function addEventHandler(element, type, func) { //unfortunate hack to deal with Internet Explorer's horrible DOM event model <iehack>
	if(element.addEventListener) {
		element.addEventListener(type,func,false);
	}
	else if (element.attachEvent) {
		element.attachEvent('on'+type,func);
	}
}

/** * Funcion que quita un eventListener.
	  @param element Elemento a agregar el listener.
	  @param type Evento.
	  @param func Funcion a ejecutar cuando ocurra el evento "type".
*/
function removeEventHandler(element, type, func) { //unfortunate hack to deal with Internet Explorer's horrible DOM event model <iehack>
	if(element.removeEventListener) {
		element.removeEventListener(type,func,false);
	}
	else if (element.attachEvent) {
		element.detachEvent('on'+type,func);
	}
}

/** * Funcion que retorna el valor absoluto (en pixeles) del TOP de un elemento.
	  @param element Elemento.
	  @return Top absoluto en pixeles del elemento dado. 
*/
function getTop(element) {
	var oNode = element;
	var iTop = 0;

	while(oNode.tagName != 'HTML') {
		iTop += oNode.offsetTop || 0;
		if(oNode.offsetParent) { //i.e. the parent element is not hidden
			oNode = oNode.offsetParent;
		}
		else {
			break;
		}
	}
	return iTop;
}

/** * Funcion que retorna el valor absoluto (en pixeles) del LEFT de un elemento.
	  @param element Elemento.
	  @return Left absoluto en pixeles del elemento dado.
*/
function getLeft(element) { //returns the absolute Left value of element, in pixels
	var oNode = element;
	var iLeft = 0;
	while(oNode.tagName != 'HTML') {
		iLeft += oNode.offsetLeft || 0;
		if(oNode.offsetParent) { //i.e. the parent element is not hidden
			oNode = oNode.offsetParent;
		}
		else {
			break;
		}
	}
	return iLeft;
}

/** * Funcion que retorna el valor absoluto (en pixeles) de la posicion del cursor.
	  @param e Evento.
	  @return cursor o array con (X, Y).
*/
function getMousePosition(e) {
    e = e || window.event;
    var cursor = {x:0, y:0};
    if (e.pageX || e.pageY) {
        cursor.x = e.pageX;
        cursor.y = e.pageY;
    } 
    else {
        var de = document.documentElement;
        var b = document.body;
        cursor.x = e.clientX + 
            (de.scrollLeft || b.scrollLeft) - (de.clientLeft || 0);
        cursor.y = e.clientY + 
            (de.scrollTop || b.scrollTop) - (de.clientTop || 0);
    }
    return cursor;
}


/** * Funcion que retorna el ancho en pixeles de un texto.
	  @param text Texto.
	  @return ancho en pixeles.
*/
function textWidth(text, style) {
	var span = document.createElement("span");
	if (style) {
		span.setAttribute("class", style);
		span.setAttribute("className", style);
	}
	span.style.position = "absolute";
	var tt = document.createTextNode(text);
	span.appendChild(tt);
	document.body.appendChild(span);
	var width = span.offsetWidth;
	document.body.removeChild(span);
	return width;
}


/** * Funcion que guarda todos los valores de los campos de una web.
	  @param ignoredText Cantidad de Input Text a ignorar comenzando desde el primero.
  	  @param ignoredSelect Cantidad de Select a ignorar comenzando desde el primero.
	  return Array con los valores respectivos.
*/
function saveState(ignoredText, ignoredSelect) {
	if (!ignoredText)
		ignoredText = 0;
	var values = new Array();
	var inputs = document.getElementsByTagName("input");
	for (i=ignoredText; i < inputs.length; i++) {
		if (inputs[i].type == "text") {
			values.push( inputs[i].value );
		}
	}

	if (!ignoredSelect)
		ignoredSelect = 0;
	var selects = document.getElementsByTagName("select");
	for (i=ignoredSelect; i < selects.length; i++) {
		values.push( selects[i].value );
	}
	
	return values;
}


/** * Funcion que restaura todos los valores de los campos de una web previamente guardados.
	  @param values Array con los valores respectivos.
*/
function restoreState(values) {
	var i = 0;
	var j = 0;
	var inputs = document.getElementsByTagName("input");
	for (i=0; i < inputs.length; i++) {
		if (inputs[i].type == "text") {
			inputs[i].value = values[j];
			j++;
		}
	}

	var selects = document.getElementsByTagName("select");
	for (i=0; i < selects.length; i++) {
		selectOrFirst(selects[i], values[j]);
		j++;
	}
}


/** * Funcion que compara dos arreglos.
	  @param array1 Arreglo 1.
  	  @param array2 Arreglo 2.
  	  @return bool.
*/
function compareArrays(array1, array2) {
	var len1 = array1.length;
	var len2 = array2.length;
	
	if (len1 != len2)
		return false;
		
	var i = 0;
	for (i=0; i<len1; i++)
		if (array1[i] != array2[i])
			return false;
		
	return true;
}


/** Funcion que no vale la pena explicar...
*/
function byId(str) {
	return document.getElementById(str);
}


/** Igual a la anterior...
*/
function byName(str) {
	return eval("document.forms[0]."+str);
}


/** Funcion que retorna el valor del radio seleccionado
	@param nombre Nombre del radio.
*/
function radioValue(nombre) {
	var obj = byName(nombre);
	for (var i=0; i < obj.length; i++)
		if (obj[i].checked) return obj[i].value;
	return "";
}


/** Funcion que retorna true si el navegador actual es IE
*/
function isIE() {
	return navigator.appName != "Netscape";
}

/** Retorna el nombre del navegador
*/
function navigatorName() {
	var ch = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
	var ff = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
	var op = navigator.userAgent.toLowerCase().indexOf('opera') > -1;
	var sa = navigator.userAgent.toLowerCase().indexOf('safari') > -1;
	var ie = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
	
	return ( ch ? "chrome" : ( ff ? "firefox" : ( op ? "opera" : ( sa ? "safari" : "ie" ) ) ) );
}

//--------------------------------------------------------------------------------
//						F U N C I O N E S   D E   F E C H A S
//--------------------------------------------------------------------------------

/** * Funcion que indica si el año dado es bisiesto.
	  @param mes: Mes del que se quiere conocer el ultimo dia.
	  @return: Booleano que indica si el año es bisiesto.
*/
function bisiesto(ano) {
	return ((ano % 4 == 0) && ((ano % 100 != 0) || (ano % 400 == 0)));
}

/** * Funcion que retorna el ultimo dia del mes.
	  @param mes: Mes del que se quiere conocer el ultimo dia.
	  @param anio: Numero del año (para casos de años bisiestos).
	  @return: String que representa el numero del ultimo dia en el formato "dd".
*/
function finDeMes( mes, anio ) {
	var mm = parseInt(mes, 10);
	var aa = parseInt(anio, 10);
	switch (mm) {
	case 1:
	case 3:
	case 5:
	case 7:
	case 8:
	case 10:
	case 12:
		return "31";
		break;
	case 4:
	case 6:
	case 9:
	case 11:
		return "30";
		break;
	case 2:
		if (bisiesto(aa))
			return "29";
		else
			return "28";
		break;
	}
}

/** * Funcion que verifica si la primera fecha es menor que la segunda.
	  @param fecha1: Primera fecha en el formato "dd/MM/yyyy".
	  @param fecha2: Segunda fecha.
	  @return: bool.
*/
function esFechaMenor(fecha1, fecha2) {
	var dia1, dia2, mes1, mes2, anio1, anio2;
	
	dia1 = fecha1.substring(0,2);
	mes1 = fecha1.substring(3,5);
	anio1 = fecha1.substring(6,10);
	
	dia2 = fecha2.substring(0,2);
	mes2 = fecha2.substring(3,5);
	anio2 = fecha2.substring(6,10);
	
	var fec1 = anio1 + mes1 + dia1;
	var fec2 = anio2 + mes2 + dia2;
	
	return fec1 < fec2;
}

/** * Funcion que suma a una fecha, la cantidad de dias dados.
	  @param fecha: Primera fecha en el formato "dd/MM/yyyy".
	  @param cant: Cantidad a sumar.
	  @param tipo: Tipo a sumar (d: dias, m: mes, y: anio)
	  @return: Fecha sumada (dd/MM/yyyy).
*/
function sumaFecha(fecha, cant, tipo) {
	if (!tipo) tipo = 'd';

	var dia, mes, anio;
			
	dia = parseInt(fecha.substring(0,2), 10);
	mes = parseInt(fecha.substring(3,5), 10);
	anio = parseInt(fecha.substring(6,10), 10);

	if (tipo == 'd') {
	
		dia = dia + cant;
		var finMes = parseInt(finDeMes(mes, ""+anio), 10);
		while (dia > finMes) {
			mes = mes + 1;
			if (mes == 13) {
				mes = 1;
				anio = anio + 1;
			}
			dia = dia - finMes;
			var finMes = parseInt(finDeMes(mes, ""+anio), 10);
		}
		
	} else if (tipo == 'm') {
	
		mes = mes + cant;
		while (mes > 12) {
			anio = anio + 1;
			mes = mes - 12;
		}
		var finMes = parseInt(finDeMes(mes, ""+anio), 10);
		if (dia > finMes) {
			dia = finMes;
		}
		
	} else {
	
		anio = anio + cant;
		var finMes = parseInt(finDeMes(mes, ""+anio), 10);
		if (dia > finMes) {
			dia = finMes;
		}
		
	}
	
	var dd = "";
	var mm = "";
	var yyyy = "";
	
	if (dia < 10) dd = "0" + dia; else dd = "" + dia;
	if (mes < 10) mm = "0" + mes; else mm = "" + mes;
	yyyy = "" + anio;		
	
	return dd + "/" + mm + "/" + yyyy;
}

/** * Funcion que resta a una fecha, la cantidad de dias dados.
	  @param fecha: Primera fecha en el formato "dd/MM/yyyy".
	  @param dias: Cantidad de dias.
	  @return: Fecha restada (dd/MM/yyyy).
*/
function restaFecha(fecha, dias) {
	var dia, mes, anio;
			
	dia = parseInt(fecha.substring(0,2), 10);
	mes = parseInt(fecha.substring(3,5), 10);
	anio = parseInt(fecha.substring(6,10), 10);

	dia = dia - dias;
	
	while (dia < 1) {
		mes = mes - 1;
		if (mes == 0) {
			mes = 12;
			anio = anio - 1;
		}
		var finMes = parseInt(finDeMes(mes, ""+anio), 10);
		dia = dia + finMes;
	}
	
	var dd = "";
	var mm = "";
	var yyyy = "";
	
	if (dia < 10) dd = "0" + dia; else dd = "" + dia;
	if (mes < 10) mm = "0" + mes; else mm = "" + mes;
	yyyy = "" + anio;		
	
	return dd + "/" + mm + "/" + yyyy;
}

/** * Funcion que calcula los dias que hay entre dos fechas.
	  @param fecha1: Fecha inicio en el formato "dd/MM/yyyy".
	  @param fecha2: Fecha fin en el formato "dd/MM/yyyy".
	  @return: dias.
*/
function diasEntreFechas(fecha1, fecha2) {
	var a1 = parseInt(fecha1.substring(6,10),10);
	var m1 = parseInt(fecha1.substring(3,5),10);
	var d1 = parseInt(fecha1.substring(0,2),10);
	
	var a2 = parseInt(fecha2.substring(6,10),10);
	var m2 = parseInt(fecha2.substring(3,5),10);
	var d2 = parseInt(fecha2.substring(0,2),10);
	
	var miFecha1 = new Date( a1, m1-1, d1 );
	var miFecha2 = new Date( a2, m2-1, d2 );
	
	var diferencia = miFecha2.getTime() - miFecha1.getTime();
	return Math.floor(diferencia / (1000 * 60 * 60 * 24));
}

/** * Funcion que calcula la cantidad de sabados y domingos que hay entre dos fechas.
	  @param fecha1: Fecha inicio en el formato "dd/MM/yyyy".
	  @param fecha2: Fecha fin en el formato "dd/MM/yyyy".
	  @return: cantidad de sabados y domingos.
*/
function sabDomEntreFechas(fecha1, fecha2) {
	var totalDias = 0;
	var semanasCompletas = 0;
	var restoDias  = 0;
	var wdResto = 0;
	var wkd = 0;
	
    totalDias = 1 + diasEntreFechas(fecha1, fecha2);
    semanasCompletas = parseInt(totalDias / 7, 10);
    restoDias = totalDias % 7;
    //Calculamos el día de la semana del primer día del resto
    wdResto = diaSemana(restaFecha(fecha2,restoDias-1));
    wkd = semanasCompletas * 2;
    //Si el resto de días + el día de la semana (empezando en lunes) es mayor que 6, nos
    // cabe un sábado y, si además es mayor que 7, nos cabe un domingo.
    if (restoDias > 0) {
        if (restoDias + wdResto > 6)
			wkd++;
        if ( (restoDias > 1) && (restoDias + wdResto > 7) && (diaSemana(fecha1) != 0) ) {
			wkd++;
		}
    }
    return wkd;
}

/** * Funcion que retorna la fecha actual.
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
			hh		Horas
			mm		Minutos
			ss		Segundos
	  @return Fecha actual en el formato dado.
*/
function fechaActual(format) {
	if (!format)
		format = "dd/MM/yyyy";

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
	var h = fecActual.getHours();
	var m = fecActual.getMinutes();
	var s = fecActual.getSeconds();
	var hh = "";
	var mm = "";
	var ss = "";
	
	if (d < 10)
		dd = "0" + d;
	else
		dd = "" + d;

	if (M < 10)
		MM = "0" + M;
	else
		MM = "" + M;
	
	if (h < 10)
		hh = "0" + h;
	else
		hh = "" + h;
	
	if (m < 10)
		mm = "0" + m;
	else
		mm = "" + m;

	if (s < 10)
		ss = "0" + s;
	else
		ss = "" + s;
	
	return format.replace("yyyy", ""+yyyy).replace("yy", ""+yy)
				 .replace("MMMM", MMMM).replace("MMM", MMM).replace("MM", MM).replace("M", ""+M)
				 .replace("dddd", dddd).replace("ddd", ddd).replace("dd", dd).replace("d", ""+d)
				 .replace("hh", hh).replace("mm", mm).replace("ss", ss);
}

/** * Función que me retorna el día de la semana.
	  @param fecha: Fecha.
	  @return: Cadena con el día de la semana (0..6).
*/
function diaSemana(fecha){
	var dia = parseInt(fecha.substring(0,2), 10);
	var mes = parseInt(fecha.substring(3,5), 10);
	var anio = parseInt(fecha.substring(6,10), 10);

	var date = new Date();
	date.setDate(dia);
	date.setMonth(mes-1);
	date.setFullYear(anio);
	
	return date.getDay();
}

/** * Funcion que cambia las fecha de un formato a otro (formatos deben contener dd, MM, yyyy).
	  @param fecha Fecha a cambiar.
	  @param origen Formato de origen.
	  @param destino Formato de destino.
	  @return Fecha re-formateada.
*/
function cambiaFormatoFecha(fecha, origen, destino) {
	if (trim(fecha).length != origen.length)
		return fecha;
	var dd = fecha.substring( origen.indexOf("dd"), origen.indexOf("dd")+2 );
	var MM = fecha.substring( origen.indexOf("MM"), origen.indexOf("MM")+2 );
	var yyyy = fecha.substring( origen.indexOf("yyyy"), origen.indexOf("yyyy")+4 );
	
	var nueva = destino.replace("dd", dd).replace("MM", MM).replace("yyyy", yyyy);
	return nueva;
}

/** * Funcion que calcula la fecha del domingo de pascua (domingo de resurreccion) de un año.
	  @param anio Año a cacular su pascua.
	  @return Fecha de pascua.
*/
function domingoPascua(anio) {
	var M, N;
	if (anio >=1583 && anio <= 1699) {
		M = 22;
		N = 2;
	} else if (anio >=1700 && anio <= 1799) {
		M = 23;
		N = 3;
	} else if (anio >=1800 && anio <= 1899) {
		M = 23;
		N = 4;
	} else if (anio >=1900 && anio <= 2099) {
		M = 24;
		N = 5;
	} else if (anio >=2100 && anio <= 2199) {
		M = 24;
		N = 6;
	} else if (anio >=2200 && anio <= 2299) {
		M = 25;
		N = 0;
	} else 
		return "UNDEFINED";
		
	var a = anio % 19;
	var b = anio % 4;
	var c = anio % 7;
	var d = (19*a + M) % 30;
	var e = (2*b + 4*c + 6*d + N) % 7;
	
	var dia, mes;
	if (d+e < 10) {
		dia = d + e + 22;
		mes = 3;
	} else {
		dia = d + e - 9;
		mes = 4;
	}
	
	// Execciones:
	if (mes == 4) {
		if (dia == 26) 
			dia = 19;
		else if (dia == 25)
			if ( d==28 && e==6 && a>10 )
				dia = 18;
	}
	
	return (dia<10 ? "0" : "") + dia + (mes<10 ? "/0" : "/") + mes + "/" + anio;
}

/** * Funcion que calcula la fecha del miércoles de ceniza de un año.
	  @param anio Año a cacular su miércoles de ceniza.
	  @return Fecha.
*/
function miercolesCeniza(anio) {
	return restaFecha( domingoPascua(anio), 46 );
}

/** * Funcion que calcula la fecha del jueves santo de un año.
	  @param anio Año a cacular su jueves santo.
	  @return Fecha.
*/
function juevesSanto(anio) {
	return restaFecha( domingoPascua(anio), 3 );
}

/** * Funcion que calcula la fecha del viernes santo de un año.
	  @param anio Año a cacular su viernes santo.
	  @return Fecha.
*/
function viernesSanto(anio) {
	return restaFecha( domingoPascua(anio), 2 );
}

/** * Funcion que calcula la fecha del lunes de carnaval de un año.
	  @param anio Año a cacular su lunes de carnaval.
	  @return Fecha.
*/
function lunesCarnaval(anio) {
	return restaFecha( domingoPascua(anio), 48 );
}

/** * Funcion que calcula la fecha del martes de carnaval de un año.
	  @param anio Año a cacular su martes de carnaval.
	  @return Fecha.
*/
function martesCarnaval(anio) {
	return restaFecha( domingoPascua(anio), 47 );
}
