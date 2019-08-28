$(document).ready(function(){
	$('.btn-sideBar-SubMenu').on('click', function(e){
		e.preventDefault();
		var SubMenu=$(this).next('ul');
		var iconBtn=$(this).children('.zmdi-caret-down');
		if(SubMenu.hasClass('show-sideBar-SubMenu')){
			iconBtn.removeClass('zmdi-hc-rotate-180');
			SubMenu.removeClass('show-sideBar-SubMenu');
		}else{
			iconBtn.addClass('zmdi-hc-rotate-180');
			SubMenu.addClass('show-sideBar-SubMenu');
		}
	});
	
	$('.btn-menu-dashboard').on('click', function(e){
		e.preventDefault();
		var body=$('.dashboard-contentPage');
		var sidebar=$('.dashboard-sideBar');
		if(sidebar.css('pointer-events')=='none'){
			body.removeClass('no-paddin-left');
			sidebar.removeClass('hide-sidebar').addClass('show-sidebar');
		}else{
			body.addClass('no-paddin-left');
			sidebar.addClass('hide-sidebar').removeClass('show-sidebar');
		}
	});
	
	//insert data
	$('.FormularioAjax').submit(function (e) { 
		e.preventDefault();
		var form = $(this);
		var type = form.attr('data-form');
		var action = form.attr('action');
		var method = form.attr('method');
		var response = form.children('.ResponseForm'); //enviar dados

		var msgError = "<script>swal('Ocorreu  um erro inesperado!', 'Por favor tente novamente!', 'error');</script>";

		var formData = new FormData(this);

		var textAlert;
		if(type === 'save'){
			textAlert = "Dados serão salvos!";
		} else if(type === "delete"){
			textAlert = "Dados serão delelatos!";
		} else if(type === "update") {
			textAlert = "Dados serão atualizados";
		} else {
			textAlert =  "Consulta realizada";
		}

		swal({
			title:  "Têm certeza!",
			text:  textAlert,
			type: "question",
			showCancelButton: true,
			confirmButtonText: "Confirmar",
			cancelButtonText: "Cancelar"
		}).then(function () {
			$.ajax({
				type: method,
				url: action,
				data: formData ? formData : formData.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				dataType: "dataType",
				xhr: function(){
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt){
						if(evt.lengthComputable){
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);	
							if(percentComplete < 100){
								response.append('<p class="text-center">Processando...(' + percentComplete + '%)</p><div class="progress progress-striped active"><div class="progress-bar progress-bar-info" style="width: '+ percentComplete +'%;"></div></div>');
							} else {
								response.html ('<p class="text-center"></p>')
							}
						}
					}, false)
					return xhr;
				},
				success: function (data) {
					// console.log(data);
					response.html(data);
				},
				error: function() {
					response.html(msgError);
				}
			});
			return false;
		});
		
	});
});
(function($){
    $(window).on("load",function(){
        $(".dashboard-sideBar-ct").mCustomScrollbar({
        	theme:"light-thin",
        	scrollbarPosition: "inside",
        	autoHideScrollbar: true,
        	scrollButtons: {enable: true}
        });
        $(".dashboard-contentPage, .Notifications-body").mCustomScrollbar({
        	theme:"dark-thin",
        	scrollbarPosition: "inside",
        	autoHideScrollbar: true,
        	scrollButtons: {enable: true}
        });
    });
})(jQuery);