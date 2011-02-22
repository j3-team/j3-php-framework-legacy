var server='http://'+location.host+'/';

$(document).ready(function(){
	$("#enlace1").click(function () {
		$("#enlace1").addClass("selected");
		$("#enlace2").removeClass("selected");
		$("#enlace3").removeClass("selected");
		$("#more-list").show();
		$("#comment-list").hide();
		$("#recent-list").hide();
    });
	$("#enlace2").click(function () {
		$("#enlace2").addClass("selected");
		$("#enlace1").removeClass("selected");
		$("#enlace3").removeClass("selected");
		$("#comment-list").show();
		$("#more-list").hide();
		$("#recent-list").hide();
    });
	$("#enlace3").click(function () {
		$("#enlace3").addClass("selected");
		$("#enlace1").removeClass("selected");
		$("#enlace2").removeClass("selected");
		$("#recent-list").show();
		$("#comment-list").hide();
		$("#more-list").hide();
    });
	
	$("a.jQueryBookmark").click(function(e){
		e.preventDefault(); // this will prevent the anchor tag from going the user off to the link
		var bookmarkUrl = this.href;
		var bookmarkTitle = this.title;
	 
		if (window.sidebar) { // For Mozilla Firefox Bookmark
			window.sidebar.addPanel(bookmarkTitle, bookmarkUrl,"");
		} else if( window.external || document.all) { // For IE Favorite
			window.external.AddFavorite( bookmarkUrl, bookmarkTitle);
		} else if(window.opera) { // For Opera Browsers
			$("a.jQueryBookmark").attr("href",bookmarkUrl);
			$("a.jQueryBookmark").attr("title",bookmarkTitle);
			$("a.jQueryBookmark").attr("rel","sidebar");
		} else { // for other browsers which does not support
			 alert('Your browser does not support this bookmark action');
			 return false;
		}
	});

});

function PaginaComentario(nropagina, div_listado, articulo_id, orden){
	$.ajax({
		url: server +"temas/ribosoma5/comments.php",
		type: "GET",
		data: "p="+nropagina+"&articulo_id="+articulo_id+"&orden="+orden,
		success: function(datos){
			$("#lista_comentarios").html(datos);		
       	}
	});
}