<script type="text/javascript" src="recursos/js/funcionesFecha.js"></script>
<script language="javascript">	
	function cargarComboCiudad(estado_id)
	{
		$.ajax({
				type: "POST",
				url: "persona/xFiltrarCiudadByEstado",
				data: "id_estado="+estado_id,
				success: function(msg){
					$("#ciudad").html(msg);
				}
		});
	}
	function cargarComboMunicipios(estado_id)
	{
		$.ajax({
				type: "POST",
				url: "persona/xFiltrarMunicipioByEstado",
				data: "id_estado="+estado_id,
				success: function(msg){
					$("#municipio").html(msg);
				}
		});
	}
	function validar()
	{
		var validate=true;
		var msj="Estimado Usuario, verifique los siguientes campos:\n";
		var regexp;
		
		//validando formulario
		//campo alfabetico
		regexp = /\w+/;
		if(document.getElementById('nombres').value.search(regexp))
		{
			msj=msj+"- Nombres\n ";
			validate=false;
		}
		//campo alfabetico
		regexp = /\w+/;
		if(document.getElementById('apellidos').value.search(regexp))
		{
			msj=msj+"- Apellidos\n ";
			validate=false;
		}
		//combo
		if(document.forms[0].genero.value=="")
		{
			msj=msj+"- G\u00e9nero\n ";
			validate=false;
		}
		//campo numerico
		regexp = /[0-9]+/; 
		if(document.getElementById('cedula').value.search(regexp))
		{
			msj=msj+"- C\u00e9dula\n ";
			validate=false;
		}
		//campo numerico
		regexp = /[0-9]+/;
		if(document.getElementById('numImpreAbogado').value.search(regexp))
		{
			msj=msj+"- N\u00ba Impreabogado\n ";
			validate=false;
		}
		//combo
		if(document.forms[0].edoCivil.value=="")
		{
			msj=msj+"- Estado Civil\n ";
			validate=false;
		}
		//campo numerico
		regexp = /[0-9]{2}-[0-9]{2}-[0-9]{4}/;
		if(document.getElementById('fechaNacimiento').value.search(regexp))
		{
			msj=msj+"- Fecha de Nacimiento \n ";
			validate=false;
		}
		//correo electronico
		regexp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+[a-zA-Z0-9]+[a-zA-Z0-9\.]+$/;
		if(document.getElementById('email').value.search(regexp))
		{
			msj=msj+"- Correo Electr\u00f3nico. Ej. usuario@dominio.com\n ";
			validate=false;
		}
		//campo numerico
		regexp = /[0-9]{11}/;
		if(document.getElementById('telefonoHab').value.search(regexp))
		{
			msj=msj+"- Tel\u00e9fono Habitaci\u00f3n. Ej. 02125345555\n ";
			validate=false;
		}
		//campo numerico
		regexp = /[0-9]{11}/;
		if(document.getElementById('telefonoCel').value.search(regexp))
		{
			msj=msj+"- Tel\u00e9fono Celular. Ej. 04145344444\n ";
			validate=false;
		}
		//combo
		if(document.forms[0].estado.value=="")
		{
			msj=msj+"- Estado de Residencia\n ";
			validate=false;
		}
		//combo
		if(document.forms[0].ciudad.value=="")
		{
			msj=msj+"- Ciudad de Residencia\n ";
			validate=false;
		}
		//combo
		if(document.forms[0].municipio.value=="")
		{
			msj=msj+"- Municipio de Residencia\n ";
			validate=false;
		}
		anios=calcularEdad(document.getElementById('fechaNacimiento').value);
		if(anios<24)
		{
			alert("Estimado usuario, debe ser mayor de 24 a\u00f1os de edad.");
			return false;
		}
		
		if (!validate)
		{
			alert(msj);
			return false;		
		}
		else
		{
			document.formulario.submit() 
		}
	}
	function siguiente()
	{	
		document.getElementById("continuar").value="1";
		validar();
	}
</script>
<form name="formulario" method="post" action="persona/fichapersonal">
	<input name="continuar" id="continuar" type="hidden" value=""/>
	<table align="center" border="0" cellpadding="2" cellspacing="5" width="98%">
		<tr>
			<td width="22%" align="right">&nbsp;Nombres&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td width="28%">
				<input type="text" name="nombres" id="nombres" value="<?php print $this->persona->getValue("nombres");?>" size="31" maxlength="100" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
			<td width="22%" align="right">&nbsp;Apellidos&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td width="28%">
				<input type="text" name="apellidos" id="apellidos" value="<?php print $this->persona->getValue("apellidos");?>" size="31" maxlength="100" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;G&eacute;nero&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<select name="genero" class="combo" style="width:210px;">
					<option value="">Seleccione G&eacute;nero</option>
					<?php
						$sel="";
						foreach($this->listaGeneros as $indice => $valor) 
						{ 
							if (!strcmp($this->persona->getValue("genero"),$indice)){$sel="selected";}
							print "<option value='".$indice."' ".$sel.">".$valor."</option>\n";
							$sel="";
						} 
					?>
				</select>
			</td>
			<td align="right">&nbsp;Cedula&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<input type="text" name="cedula" id="cedula" value="<?php print $this->persona->getValue("cedula");?>" size="31" maxlength="8" onKeyPress="return valText(this.value, event, 'int');" class="campoTexto"/>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;N&ordm; Impreabogado&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<input type="text" name="numImpreAbogado" id="numImpreAbogado" value="<?php print $this->persona->getValue("num_imp_abogado");?>" size="31" maxlength="10" onKeyPress="return valText(this.value, event, 'int');"/>
			</td>
			<td align="right">&nbsp;Estado Civil&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<select name="edoCivil" class="combo" style="width:210px;">
					<option value="">Seleccione Estado Civil</option>
					<?php
						$sel="";
						foreach($this->listaEdoCivil as $indice => $valor)
						{
							if (!strcmp($this->persona->getValue("estado_civil"),$indice)){$sel="selected";}
							print "<option value='".$indice."' ".$sel.">".$valor."</option>\n";
							$sel="";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;Fecha de Nacimiento&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<input type="text" name="fechaNacimiento" id="fechaNacimiento" value="" readonly="readonly" class="campoTexto"/>
				<img  id='cal1' src='recursos/imgs/calendario.jpg' height='16' alt='calendario' border='0' onmouseover="Tip('Haga click aqu\u00ed para abrir un selector de fechas.')" onmouseout="UnTip()"> 
				<script language="javascript">
					var fN=cambiaFormatoFecha('<?php print $this->persona->getValue("fecha_nacimiento");?>',"yyyy/MM/dd","dd-MM-yyyy");
					document.getElementById('fechaNacimiento').value=fN;
					var fechaMin="01-01-1920";
					var fechaMax=fechaActual("dd-MM-yyyy");
					var elTarget = document.getElementById('fechaNacimiento');
					var img = document.getElementById('cal1');
					var cal = new Epoch('cal1','popup',elTarget,img,false,false,true,fechaMin,fechaMax,'d-m-Y');
				</script>
			</td>
			<td align="right">&nbsp;Correo Electr&oacute;nico <span style="color:red;">*</span>&nbsp;</td>
			<td>
				<input type="text" name="email" id="email" value="<?php print $this->persona->getValue("email");?>" size="31" maxlength="100" class="campoTexto"/>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;Telefono Habitaci&oacute;n&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<input type="text" name="telefonoHab" id="telefonoHab" value="<?php print $this->persona->getValue("telefono_hab");?>" size="31" maxlength="11" onKeyPress="return valText(this.value, event, 'int');"  class="campoTexto"/>
			</td>
			<td align="right">&nbsp;Telefono Celular&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<input type="text" name="telefonoCel" id="telefonoCel" value="<?php print $this->persona->getValue("telefono_cel");?>" size="31" maxlength="11" onKeyPress="return valText(this.value, event, 'int');"  class="campoTexto"/>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;Estado de Residencia&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<select name="estado" class="combo" onChange="cargarComboCiudad(this.value);cargarComboMunicipios(this.value);" style="width:210px;">
					<option value="">Seleccione Estado</option>
					<?php
						$sel="";
						while($this->estados->next()) 
						{
							if (!strcmp($this->persona->getValue("id_estado"),$this->estados->getValueByPos(0))){$sel="selected";}
							print "<option value='".$this->estados->getValueByPos(0)."' ".$sel.">".$this->estados->getValueByPos(1)."</option>\n";
							$sel="";
						}
					?>
				</select>
			</td>
			<td align="right">&nbsp;Ciudad de Residencia&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<select name="ciudad" id="ciudad" class="combo" style="width:210px;">
					<option value="">Seleccione Ciudad</option>
					<?php
						$sel="";
						while($this->ciudades->next()) 
						{
							if (!strcmp($this->persona->getValue("id_ciudad"),$this->ciudades->getValueByPos(0))){$sel="selected";}
							print "<option value='".$this->ciudades->getValueByPos(0)."' ".$sel.">".$this->ciudades->getValueByPos(1)."</option>\n";
							$sel="";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;Municipio de Residencia&nbsp;<span style="color:red;">*</span>&nbsp;</td>
			<td>
				<select name="municipio" id="municipio" class="combo" style="width:210px;">
					<option value="">Seleccione Municipio</option>
					<?php
						$sel="";
						while($this->municipios->next()) 
						{
							if (!strcmp($this->persona->getValue("id_municipio"),$this->municipios->getValueByPos(0))){$sel="selected";}
							print "<option value='".$this->municipios->getValueByPos(0)."' ".$sel.">".$this->municipios->getValueByPos(1)."</option>\n";
							$sel="";
						}
					?>
				</select>
			</td>
			<td align="right">&nbsp;Calle/Avenida&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>
				<input type="text" name="calleAv" id="calleAv" value="<?php print $this->persona->getValue("calle_av");?>" size="31" maxlength="100" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;Urbanizaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>
				<input type="text" name="urbanizacion" id="urbanizacion" value="<?php print $this->persona->getValue("urbanizacion");?>" size="31" maxlength="100" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
			<td align="right">&nbsp;Nombre Casa o Edificio&nbsp;&nbsp;&nbsp;</td>
			<td>
				<input type="text" name="nombreCasaApto" id="nombreCasaApto" value="<?php print $this->persona->getValue("nombre_casa_apto");?>" size="31" maxlength="100" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;Piso&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>
				<input type="text" name="piso" id="piso" value="<?php print $this->persona->getValue("piso");?>" size="31" maxlength="5" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
			<td align="right">&nbsp;N&ordm; Casa o Apartamento&nbsp;&nbsp;&nbsp;</td>
			<td>
				<input type="text" name="numeroCasaApto" id="numeroCasaApto" value="<?php print $this->persona->getValue("num_casa_apto");?>" size="31" maxlength="10" class="campoTexto" onKeyUp="this.value = this.value.toUpperCase();"/>
			</td>
		</tr>
		<tr><td class="separator" colspan="4">&nbsp;</td></tr>
		<tr>
			<td colspan="4" align="center">
				<div align="center">
					<input type="button" name="btnGrabar" value="Grabar" class="boton" onClick="validar();">&nbsp;
					<input type="button" name="btnSeguiente" value="Siguiente" class="boton" onClick="siguiente();">
				</div>
			</td>
		</tr>
		<tr><td class="separator" colspan="4">&nbsp;</td></tr>
	</table>			
</form>
