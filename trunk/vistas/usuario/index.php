<script language="javascript">
	function registrar()
	{	
		location.href="usuario/registro";
	}
	
	function olvido()
	{	
		location.href="usuario/olvidoclave";
	}
	
	function validar()
	{
		var validate=true;
		var msj="Estimado Usuario, verifique los siguientes campos:\n";
		var regexp;
		
		//validando formulario
		//campo alfanumerico
		regexp = /\w+[0-9]*/;
		if(document.getElementById('alias').value.search(regexp))
		{
			msj=msj+"- Usuario (alias)\n ";
			validate=false;
		}
		regexp = /\w+[0-9]*/;
		if(document.getElementById('clave').value.search(regexp))
		{
			msj=msj+"- Contrase\u00f1a\n ";
			validate=false;
		}
		if (!validate)
		{
			alert(msj);
			return false;		
		}
		else
		{
			document.getElementById('alias').value=trim(document.getElementById('alias').value.toLowerCase());
			document.getElementById('clave').value=trim(document.getElementById('clave').value);
			return true;
		}
	}
</script>
<form name="formulario" method="post" action="usuario/login" onSubmit="return validar();">
	<table align="center" border="0" cellpadding="0" cellspacing="15" width="99%">
		<tr>
			<td class="celBlanca" colspan="2">
				<p align="justify">&nbsp;Bienvenidos al M&oacute;dulo de
				Preinscripci&oacute;n en L&iacute;nea de la <b>XXXXX</b>&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td class="separator" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="center">
				<div class="frame">
				<table align="center" border="0" cellpadding="2" cellspacing="2" width="85%">
					<tr>
						<td align="center" colspan="2">&nbsp;<b class="tituloLogin">INICIAR SESI&Oacute;N</b>&nbsp;<br/><br/></td>
					</tr>
					<tr>
						<td class="celBlanca" width="40%">
						<div align="right">&nbsp;Usuario (alias): <span style="color: red;">*</span></div>
						</td>
						<td class="celBlancaFin" width="60%">&nbsp;<input type="text" name="alias" id="alias" value="" size="20" maxlength="100" /></td>
					</tr>
					<tr>
						<td class="celBlanca">
						<div align="right">&nbsp;Contrase&ntilde;a: <span style="color: red;">*</span></div>
						</td>
						<td class="celBlancaFin">&nbsp;<input type="password" name="clave" id="clave" value="" size="20" maxlength="10" /></td>
					</tr>
					<tr>
						<td colspan="2" class="msjError" align="center">
							&nbsp;<?php if(isset($_SESSION["msjError"])){ echo $_SESSION["msjError"];unset($_SESSION["msjError"]);}?>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
						<div align="center"><input type="submit" name="btnLogin" value="Login" class="boton">&nbsp;&nbsp; </div>
						</td>
					</tr>
				</table><br/>
				</div>
			</td>
			<td width="50%" align="center">
				<div class="frame">
				<table align="center" border="0" cellpadding="2" cellspacing="2" width="80%">
					<tr>
						<td align="center" colspan="2">&nbsp;<b class="tituloLogin">&iquest;NO EST&Aacute; REGISTRADO&#63;</b>&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
							-> Haga Click&nbsp; <input type="button" name="btnAqui" value="Aqu&iacute;" class="boton" onClick="registrar();">
						</td>
					</tr>
				</table>
				</div>
				<br/>
				<div class="frame">
				<table align="center" border="0" cellpadding="2" cellspacing="2" width="80%">
					<tr>
						<td align="center" colspan="2">&nbsp;<b class="tituloLogin">&iquest;OLVID&Oacute; SU CONTRASE&Ntilde;A&#63;</b>&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
							-> Haga Click&nbsp; <input type="button" name="btnAqui2" value="Aqu&iacute;" class="boton" onClick="olvido();">
						</td>
					</tr>
				</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="separator" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="separator" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="celBlanca" colspan="2" style="text-align: center;">
				<p align="justify">
					&nbsp;Para cualquier pregunta o duda, por favor escriba a 
					<a href="mailto:mail@dominio.com" class="link1">mail@dominio.com</a>&nbsp;
				</p>
			</td>
		</tr>
	</table>
</form>