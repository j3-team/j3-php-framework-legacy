<p align="center" style="width:100%;">
	<span class="notifTitle">
		<?php echo $this->titulo; ?>
	</span>
	<br/><br/>
	<span class="notifMessage">
		<?php echo $this->mensaje; ?>
	</span>
	<br/><br/>
</p>
<p align="center" style="width:100%;">
	<?php if(strlen($this->boton)!=0){?>
		<input type="button" class="boton" value="<?php echo $this->boton; ?>" onclick="location.href='<?php echo $this->enlace; ?>';" />			
	<?php }?>
</p>