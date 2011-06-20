<html>
    <head>
    	<?php echo APP_BASE; ?>
		<title><?php echo APP_TITLE; ?> - <?php echo $this->pageTitle; ?></title>
        <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="utf-8"/>-->
        <link rel="stylesheet" type='text/css' href="recursos/css/plantilla.css"/>
        <link rel="stylesheet" type='text/css' href="recursos/css/j3-styles.css"/>
		<!--[if lt IE 9]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js">IE7_PNG_SUFFIX=".png";</script>
		<![endif]-->
        <script type="text/javascript">
            window.onload = function(){
                var margen = 200;
                var widthAplicacion = 800;
                var widthPantalla = document.body.clientWidth - margen - widthAplicacion;
                document.getElementById('divContenedor').style.marginLeft = 100 + (widthPantalla/2);
                document.getElementById('divBanner').style.marginLeft = 100 + (widthPantalla/2);
            }
        </script>
    </head>
    <body>
        <div id="divCabecera">
            <div id="divBanner"></div>
        </div>
        <div id="divContenedor">
            <div id="divCuerpo">
                <div id="divAplicacion">
                    <div id="divMenu"><?php echo $this->titleShowed; ?></div>
                    <div id="divComponentes">
                        <?php $this->showContent(); ?>
                    </div>
                </div>
                <div id="divPie"></div>
            </div>
        </div><br/>
    </body>
</html>
