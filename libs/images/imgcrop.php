<?php
	function neccesaryScript($width, $height) {
		echo "<script type='text/javascript' src='recursos/js/jquery.imgareaselect.min.js'></script> \n";		
		echo "<script type='text/javascript'> \n";
		anchoVentana();
		reloadImgs();
		echo "\n";
		echo "	function previewCrop(img, selection) { \n";
		echo "		var scaleX = $width / (selection.width || 1); \n";
		echo "		var scaleY = $height / (selection.height || 1); \n";
		echo "		 \n";
		echo "		$('#imgCropThumbnail + div > img').css({ \n";
		echo "			width: Math.round(scaleX * document.getElementById('imgCropThumbnail').offsetWidth) + 'px', \n";
		echo "			height: Math.round(scaleY * document.getElementById('imgCropThumbnail').offsetHeight) + 'px', \n";
		echo "			marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',  \n";
		echo "			marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'  \n";
		echo "		}); \n";
		echo "		$('#x1').val(selection.x1); \n";
		echo "		$('#y1').val(selection.y1); \n";
		echo "		$('#x2').val(selection.x2); \n";
		echo "		$('#y2').val(selection.y2); \n";
		echo "		$('#w').val(selection.width); \n";
		echo "		$('#h').val(selection.height); \n";
		echo "	}  \n";
		echo "	  \n";
		echo "	$(document).ready(function () { \n";
		echo "		$('#imgCropSaveButton').click(function() { \n";
		echo "			var x1 = $('#x1').val(); \n";
		echo "			var y1 = $('#y1').val(); \n";
		echo "			var x2 = $('#x2').val(); \n";
		echo "			var y2 = $('#y2').val(); \n";
		echo "			var w = $('#w').val(); \n";
		echo "			var h = $('#h').val(); \n";
		echo "			if(x1=='' || y1=='' || x2=='' || y2=='' || w=='' || h==''){ \n";
		echo "				alert('Debes seleccionar una zona de la foto primero'); \n";
		echo "				return false; \n";
		echo "			}else{ \n";
		echo "				return true; \n";
		echo "			} \n";
		echo "		}); \n";
		/*echo "		$(\"#imgCropThumbnailCropped\") \n";
        echo "		.css({ \n";
        echo "	    	float: 'left', \n";
        echo "	    	position: 'relative', \n";
        echo "	    	overflow: 'hidden', \n";
        echo "	    	width: '".$width."px', \n";
        echo "	    	height: '".$height."px' \n";
        echo "		}) \n";
        echo "		.insertAfter($('#divImgCropGenerated')); \n";*/
		echo "		$('#imgCropThumbnail').imgAreaSelect({ aspectRatio: '$width:$height', onSelectChange: previewCrop }); \n";
		echo "	}); \n";
		echo "	  \n";
		echo "</script> \n"; 
	}
	
	
	/** Funcion que crea un formulario de seleccion y recorte de image
	 * @param actionForm Atributo ACTION del form.
	 * @param fotoPath Ruta de la imagena a recortar.
	 * @param width Ancho del recorte.
	 * @param height Alto del recorte.
	 * @param hide True si queremos mostrar un boton Canlcelar que oculte el div.
	 * */
	function createCropForm($class, $actionForm, $fotoPath, $width, $height, $hide = false, $method = "") {
		$div = "document.getElementById(\"divImgCropGenerated\")";
		echo "<div class='$class' align='center' id='divImgCropGenerated'> \n";
		echo "	<img src='$fotoPath' style='float: left; border: 1px outset #CCCCCC; margin-right: 5px;' id='imgCropThumbnail' alt='Cortar foto' /> \n";
		/*echo "	<div style='float:left; position:relative; overflow:hidden; width:".$width."px; height:".$height."px;' class='fotomarket'> \n";
		echo "		<img src='$fotoPath' id='imgCropThumbnailCropped' style='position: relative;' alt='Vista previa' /> \n";
		echo "	</div> \n";*/
		echo "	<br style='clear:both;'/> \n";
		echo "	<form name='thumbnail' action='$actionForm' method='post'> \n";
		echo "		<input type='hidden' name='x1' value='' id='x1' /> \n";
		echo "		<input type='hidden' name='y1' value='' id='y1' /> \n";
		echo "		<input type='hidden' name='x2' value='' id='x2' /> \n";
		echo "		<input type='hidden' name='y2' value='' id='y2' /> \n";
		echo "		<input type='hidden' name='w' value='' id='w' /> \n";
		echo "		<input type='hidden' name='h' value='' id='h' /> \n";
		echo "		<br /> ";
		echo "		<input type='submit' class='button' name='btnSaveCrop' value='Guardar Foto' id='imgCropSaveButton' /> \n";
		if ($hide) {
			echo "		&nbsp;&nbsp; \n";
			echo "		<input type='button' class='button' name='btnCancelCrop' value='Cancelar' onclick='$method; $div.style.display=\"none\";' /> \n";
		}
		echo "	</form> \n";
		echo "</div> \n";
	}
	
	function createCropFormAjax($class, $function, $fotoPath, $width, $height, $hide = false, $method = "") {
		$div = "document.getElementById(\"divImgCropGenerated\")";
		echo "<div class='$class' align='center' id='divImgCropGenerated'> \n";
		echo "	<img src='$fotoPath' style='float: left; border: 1px outset #CCCCCC; margin-right: 10px;' id='imgCropThumbnail' alt='Cortar foto' /> \n";
		/*echo "	<div style='float:left; position:relative; overflow:hidden; width:".$width."px; height:".$height."px;' class='fotomarket'> \n";
		echo "		<img src='$fotoPath' id='imgCropThumbnailCropped' style='position: relative;' alt='Vista previa' /> \n";
		echo "	</div> \n";*/
		echo "	<br style='clear:both;'/> \n";
		echo "	<form name='thumbnailForm' action='$actionForm' method='post'> \n";
		echo "		<input type='hidden' name='x1' value='' id='x1' /> \n";
		echo "		<input type='hidden' name='y1' value='' id='y1' /> \n";
		echo "		<input type='hidden' name='x2' value='' id='x2' /> \n";
		echo "		<input type='hidden' name='y2' value='' id='y2' /> \n";
		echo "		<input type='hidden' name='w' value='' id='w' /> \n";
		echo "		<input type='hidden' name='h' value='' id='h' /> \n";
		echo "		<br /> ";
		echo "		<input type='button' class='button' onclick='".$function."(document.thumbnailForm)' name='btnSaveCrop' value='Guardar Foto' id='imgCropSaveButton' /> \n";
		if ($hide) {
			echo "		&nbsp;&nbsp; \n";
			echo "		<input type='button' class='button' name='btnCancelCrop' value='Cancelar TODO' onclick='$method; $div.style.display=\"none\";' /> \n";
		}
		echo "	</form> \n";
		echo "</div> \n";
	} 
	
	function hideCropForm() {
		echo "document.getElementById('divImgCropGenerated').style.display='none';\n";
	}
	
	function showCropForm() {
		updateCropForm();
		echo "document.getElementById('divImgCropGenerated').style.display='block';\n";
		echo "var wh = widthAndHeight(); \n";
		echo "var dw = document.getElementById('divImgCropGenerated').offsetWidth;\n";
		echo "var dh = document.getElementById('divImgCropGenerated').offsetHeight;\n";
		echo "var dh = 450;\n";
		echo "var dw = 400;\n";
		echo "var l = (wh[0]-dw) / 2;\n";
		echo "var t = (wh[1]-dh) / 2;\n";
		echo "if (t < 206) t = 206; \n";
		echo "document.getElementById('divImgCropGenerated').style.left=l+'px';\n";
		echo "document.getElementById('divImgCropGenerated').style.top=t+'px';\n";
	}
	
	function updateCropForm() {
		/*echo "  var a = document.getElementById('imgCropThumbnail').src;";
		echo "  document.getElementById('imgCropThumbnail').src = '#';";
		echo "  document.getElementById('imgCropThumbnailCropped').src = '#';";
		echo "  document.getElementById('imgCropThumbnail').src = a;";
		echo "  document.getElementById('imgCropThumbnailCropped').src = a;";
		echo "  reloadImg('imgCropThumbnailCropped'); \n";*/
		echo "  reloadImg('imgCropThumbnail'); \n";
		//echo "  reloadImg('imgCropThumbnailCropped'); \n";
	}
	
	function anchoVentana() {
		echo "  function widthAndHeight() { \n";
		echo "  	var wh = new Array(); \n";
		echo "  	if( typeof( window.innerWidth ) == 'number' ) { \n";
		echo "  		//Non-IE \n";
		echo "  		wh[0] = window.innerWidth; \n";
		echo "  		wh[1] = window.innerHeight; \n";
		echo "  	} else if( document.documentElement && document.documentElement.clientWidth ) { \n";
		echo "  		//IE 6+ in 'standards compliant mode' \n";
		echo "  		wh[0] = document.documentElement.clientWidth; \n";
		echo "  		wh[1] = document.documentElement.clientHeight; \n";
		echo "  	} else if( document.body && document.body.clientWidth ) { \n";
		echo "  		//IE 4 compatible \n";
		echo "  		wh[0] = document.body.clientWidth; \n";
		echo "  		wh[1] = document.body.clientHeight; \n";
		echo "  	} \n";
		echo "    return wh; \n";
		echo "  } \n";
	}
	
	function reloadImgs() {
		echo "  function reloadImg(id) { \n";
		echo "     var obj = document.getElementById(id); \n";
		echo "     var src = obj.src; \n";
		echo "     var pos = src.indexOf('?'); \n";
		echo "     if (pos >= 0) { \n";
		echo "        src = src.substr(0, pos); \n";
		echo "     } \n";
		echo "     var date = new Date(); \n";
		echo "     obj.src = src + '?v=' + date.getTime(); \n";
		echo "     return false; \n";
		echo "  } \n";	
	}
?>