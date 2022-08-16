<!DOCTYPE html>
<html lang="es">
<head>
	<title>Camaleon Sistem</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
	<div class="container">
		<div class="row">
            <div class="col-12 mt-3">
                <a href="index.php"><button type="button" class="btn btn-primary">Volver</button></a>
            </div>
			<div class="col-12 text-center" style="font-weight:bold; font-size:22px; margin-top: 2rem;">Cargar Multiple Archivos</div>
			<div class="col-12 text-center mt-3">
				<form id="formulario1" method="post" action="#" enctype="multipart/form-data">
					<input type="hidden" id="condicion" name="condicion" value="subir1">
					<div class="row">
						<div class="col-10">
							<input type="file" class="form-control" id="images[]" name="images[]" multiple="">
						</div>
						<div class="col-2 text-center">
							<button type="submit" class="btn btn-success">Cargar Imagen</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="col-12 text-center mt-3" id="cargando1"></div>

		<div class="row text-center" style="margin-top: 3rem;" id="biblioteca1"></div>		

	</div>
</body>
</html>

<script src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">
	$(document).ready(function(){
		biblioteca1();
    });
	
	$("#formulario1").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'script/crud_imagenes.php',
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,

            beforeSend: function(){
                $('#cargando1').html('<img src="img/cargando1.gif" style="width:400px;" class="img-fluid">');
            },
            
            success: function(respuesta){
            	biblioteca1();
            	$('#cargando1').html("");
                if(respuesta["estatus"]=="ok"){
                    Swal.fire({
                        title: 'Success',
                        text: respuesta["msg"],
                        icon: 'success',
                        showConfirmButton: true,
                    })
                }else if(respuesta["estatus"]=="error"){
                	Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
                        showConfirmButton: true,
                    })
                }
            },

            error: function(respuesta){
            	biblioteca1();
            	$('#cargando1').html("");
                console.log(respuesta['responseText']);
            }
        });
    });

    function biblioteca1(){
    	$.ajax({
            type: 'POST',
            url: 'script/crud_imagenes.php',
            dataType: "JSON",
            data: {
            	'condicion': 'biblioteca1',
            },
            cache: false,

            beforeSend: function(){
            	//
            },
            
            success: function(respuesta){
            	$('#biblioteca1').html(respuesta["html"]);
            },

            error: function(respuesta){
                console.log(respuesta['responseText']);
            }
        });
    }

    function eliminar1(id){
    	$.ajax({
            type: 'POST',
            url: 'script/crud_imagenes.php',
            dataType: "JSON",
            data: {
            	'id': id,
            	'condicion': 'eliminar1',
            },

            beforeSend: function(){
            	//
            },
            
            success: function(respuesta){
            	biblioteca1();
            	Swal.fire({
					title: 'Success',
					text: respuesta["msg"],
					icon: 'success',
					showConfirmButton: true,
				})
            },

            error: function(respuesta){
                console.log(respuesta['responseText']);
            }
        });
    }

</script>