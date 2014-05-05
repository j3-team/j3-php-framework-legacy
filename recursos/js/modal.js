function closeModal()
{
	$("#modal").css("opacity", "0");
	$("#modal").css("top", "-50%");
	$("#modal").css("visibility", "hidden");
}

function callModal(titulo,mensaje,nameBtn1,nameBtn2,callback)
{
	$("#modal").css("opacity", "1");
	$("#modal").css("top", "50%");
	$("#modal").css("visibility", "visible");
	$("#modal .modal-content .modal-header h2").html(titulo);
	$("#modal .modal-content .modal-msj p").html(mensaje);
	if(nameBtn2==="")
	{
		$("#modal .modal-content #btn_modal_1").css("display","none");
		$("#modal .modal-content #btn_modal_2").val(nameBtn1);
		$("#modal .modal-content #btn_modal_2").click(function () {
			closeModal();
		});
	}
	else
	{
		$("#modal .modal-content #btn_modal_1").css("display","");
		$("#modal .modal-content #btn_modal_1").val(nameBtn1);	
		$("#modal .modal-content #btn_modal_2").val(nameBtn2);
		$("#modal .modal-content #btn_modal_1").click(function () {
			closeModal();
			eval(callback+"()");
		});
		$("#modal .modal-content #btn_modal_2").click(function () {
			closeModal();
		});
	}
}