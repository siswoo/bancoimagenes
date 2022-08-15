<?php
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');

if($condicion=='table1'){
    $pagina = $_POST["pagina"];
    $consultasporpagina = $_POST["consultasporpagina"];
    $filtrado = $_POST["filtrado"];
    $sede = $_POST["sede"];

    if($pagina==0 or $pagina==''){
        $pagina = 1;
    }

    if($consultasporpagina==0 or $consultasporpagina==''){
        $consultasporpagina = 10;
    }

    if($filtrado!=''){
        $filtrado = ' and (model.documento_numero LIKE "%'.$filtrado.'%" or model.nombre1 LIKE "%'.$filtrado.'%" or model.nombre2 LIKE "%'.$filtrado.'%" or model.apellido1 LIKE "%'.$filtrado.'%" or model.apellido2 LIKE "%'.$filtrado.'%")';
    }

    if($sede!=''){
        $sede = ' and model.sede = "'.$sede.'"';
    }

    $limit = $consultasporpagina;
    $offset = ($pagina - 1) * $consultasporpagina;

    $sql1 = "SELECT * FROM contenido_modelos WHERE id != 0 ".$filtrado." ".$sede." ";

    $sql2 = "SELECT * FROM contenido_modelos WHERE id != 0 ".$filtrado." ".$sede." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset."";

    $proceso1 = mysqli_query($conexion,$sql1);
    $proceso2 = mysqli_query($conexion,$sql2);
    $conteo1 = mysqli_num_rows($proceso1);
    $paginas = ceil($conteo1 / $consultasporpagina);

    $html = '';

    $html .= '
        <div class="col-12">
            <input type="hidden" name="contador1" id="contador1" value="'.$conteo1.'">
            <form action="#" method="POST" id="formulario1">
            <input type="hidden" name="condicion" id="condicion" value="guardar2">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center">Documento Número</th>
                    <th class="text-center">Modelo</th>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Condicion</th>
                    <th class="text-center">Mes</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Acción</th>
                </tr>
                </thead>
                <tbody>
    ';
    if($conteo1>=1){
        while($row2 = mysqli_fetch_array($proceso2)) {
            $id = $row2["id"];
            $nombre1 = $row2["nombre1"];
            $nombre2 = $row2["nombre2"];
            $apellido1 = $row2["apellido1"];
            $apellido2 = $row2["apellido2"];
            $documento_numero = $row2["documento_numero"];
            $estatus = $row2["estatus"];

            $html .= '
                        <tr id="tr_'.$id.'">
                            <td style="text-align:center;">'.$documento_numero.'</td>
                            <td style="text-align:center;">'.$nombre1." ".$nombre2." ".$apellido1." ".$apellido2.'</td>
                            <td style="text-align:center;">'.$estatus.'</td>
                            <td style="text-align:center;" nowrap="nowrap">
                                <select class="form-control" name="pagina_'.$id.'" id="pagina_'.$id.'">
                                    <option value="">Seleccione</option>
            ';

            $sql3 = "SELECT * FROM contenido_paginas";
            $proceso3 = mysqli_query($conexion,$sql3);
            while($row3=mysqli_fetch_array($proceso3)){
                $contenido_paginas_id = $row3["id"];
                $contenido_paginas_nombre = $row3["nombre"];
                $html .= '<option value="'.$contenido_paginas_id.'">'.$contenido_paginas_nombre.'</option>';
            }

            $html .= '
                                    <option value="descuento">Descuentos</option>
                                    <option value="avances">Avances</option>
                                    <option value="multas">Multas</option>
                                    <option value="sexshop">Sexshop</option>
                                    <option value="tecnologia">Tecnologia</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" id="mes_'.$id.'" name="mes_'.$id.'">
                                    <option value="">Seleccione</option>
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="valor_'.$id.'" id="valor_'.$id.'" class="form-control">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary" onclick="agregar1('.$id.');">Agregar</button>
                            </td>
                        </tr>
            ';
        }
    }else{
        $html .= '<tr><td colspan="7" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
    }

    $html .= '
                </tbody>
            </table>
        <form>
            <nav>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 text-center">
                        <p>Mostrando '.$conteo1.' de '.$consultasporpagina.' datos por pagina</p>
                    </div>
                    <div class="col-xs-12 col-sm-4 text-center">
                        <p>Página '.$pagina.' de '.$paginas.' </p>
                    </div> 
                    <div class="col-xs-12 col-sm-4">
                        <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
                            <ul class="pagination" style="font-size: 30px;">
    ';
    
    if ($pagina > 1) {
        $html .= '
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
                                        <span aria-hidden="true">Anterior</span>
                                    </a>
                                </li>
        ';
    }

    $diferenciapagina = 3;
    
    /*********MENOS********/
    if($pagina==2){
        $html .= '
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
                                        '.($pagina-1).'
                                    </a>
                                </li>
        ';
    }else if($pagina==3){
        $html .= '
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
                                        '.($pagina-2).'
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
                                        '.($pagina-1).'
                                    </a>
                                </li>
    ';
    }else if($pagina>=4){
        $html .= '
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
                                        '.($pagina-3).'
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
                                        '.($pagina-2).'
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
                                        '.($pagina-1).'
                                    </a>
                                </li>
        ';
    } 

    /*********MAS********/
    $opcionmas = $pagina+3;
    if($paginas==0){
        $opcionmas = $paginas;
    }else if($paginas>=1 and $paginas<=4){
        $opcionmas = $paginas;
    }
    
    for ($x=$pagina;$x<=$opcionmas;$x++) {
        $html .= '
                                <li class="page-item 
        ';

        if ($x == $pagina){ 
            $html .= '"active"';
        }

        $html .= '">';

        $html .= '
                                    <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
                                </li>
        ';
    }

    if ($pagina < $paginas) {
        $html .= '
                                <li class="page-item">
                                    <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
                                        <span aria-hidden="true">Siguiente</span>
                                    </a>
                                </li>
        ';
    }

    $html .= '

                        </ul>
                    </nav>
                </div>
            </nav>
        </div>
    ';

    $datos = [
        "estatus"   => "ok",
        "html"  => $html,
        "sql2"  => $sql2,
    ];
    echo json_encode($datos);
}

if($condicion=='guardar1'){
    $nombre1 = $_POST['nombre1'];
    $nombre2 = $_POST['nombre2'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $documento_tipo = $_POST['documento_tipo'];
    $documento_numero = $_POST['documento_numero'];
    $genero = $_POST['genero'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $clave = md5($_POST['clave']);
    $telefono1 = $_POST['telefono1'];

    $sql1 = "SELECT * FROM contenido_modelos WHERE documento_numero = '$documento_numero' or correo = '$correo'";
    $proceso1 = mysqli_query($conexion,$sql1);
    $contador1 = mysqli_num_rows($proceso1);
    if($contador1>=1){
        $datos = [
            "estatus"   => "error",
            "msg"   => "Ya existe el numero de documento y/o correo",
        ];
        echo json_encode($datos);
        exit;
    }

    $sql4 = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $proceso4 = mysqli_query($conexion,$sql4);
    $contador4 = mysqli_num_rows($proceso4);
    if($contador4>=1){
        $datos = [
            "estatus"   => "error",
            "msg"   => "Ya existe el correo!",
        ];
        echo json_encode($datos);
        exit;
    }

    $sql2 = "INSERT INTO contenido_modelos (nombre1,nombre2,apellido1,apellido2,documento_tipo,documento_numero,genero,correo,usuario,clave,telefono1,estatus,fecha_inicio) VALUES ('$nombre1','$nombre2','$apellido1','$apellido2','$documento_tipo','$documento_numero','$genero','$correo','$usuario','$clave','$telefono1','Activa','$fecha_inicio')";
    $proceso2 = mysqli_query($conexion,$sql2);
    $modelo_id=mysql_insert_id();

    @!file_exists(mkdir("../resuorces/contenidos", 0777, true));
    @!file_exists(mkdir("../resuorces/contenidos/modelos/", 0777, true));
    @!file_exists(mkdir("../resuorces/contenidos/modelos/".$modelo_id, 0777, true));

    $sql3 = "INSERT INTO usuarios (nombre,apellido,documento_tipo,documento_numero,correo,usuario,clave,telefono1,rol,sede,fecha_inicio) VALUES ('$nombre1','$apellido1','$documento_tipo','$documento_numero','$correo','$usuario','$clave','$telefono1',5,12,'$fecha_inicio')";
    //$proceso3 = mysqli_query($conexion,$sql3);

    $datos = [
        "estatus"   => "ok",
    ];
    echo json_encode($datos);
}

if($condicion=='subir1'){
    $errores = 0;
    $errores_html = '|';
    $permitidos = array('jpg','png','jpeg','webp');
    $images_arr = array();

    foreach($_FILES['images']['name'] as $key=>$val){
        $image_name = $_FILES['images']['name'][$key];
        $tmp_name   = $_FILES['images']['tmp_name'][$key];
        $size       = $_FILES['images']['size'][$key];
        $type       = $_FILES['images']['type'][$key];
        $error      = $_FILES['images']['error'][$key];
        
        $fileType = pathinfo($image_name,PATHINFO_EXTENSION);

        $imagen_general = explode('.',$image_name);
        $imagen_nombre = $imagen_general[0];
        $imagen_formato = $imagen_general[1];
        $imagen_nombre_nuevo = $imagen_nombre.'.webp';
        $ruta = "uploads/".$imagen_nombre_nuevo;

        if($fileType=='png'){
            imagewebp(imagecreatefrompng($tmp_name),"../".$ruta, 50);
        }else if($fileType=='jpg'){
            imagewebp(imagecreatefromjpeg($tmp_name),"../".$ruta, 50);
        }else if($fileType=='jpeg'){
            imagewebp(imagecreatefromjpeg($tmp_name),"../".$ruta, 50);
        }else if($fileType=='webp'){
            move_uploaded_file($tmp_name,"../".$ruta);
        }

        if(in_array($fileType, $permitidos)){
            $sql1 = "INSERT INTO imagenes (nombre,formato,ruta,fecha_inicio) VALUES ('$image_name','$type','$ruta','$fecha_inicio')";
            $proceso1 = mysqli_query($conexion,$sql1);
            //move_uploaded_file($tmp_name,"../".$ruta);
        }else{
            $errores = $errores+1;
            $errores_html .= $image_name.' | ';
        }
    }

    if($errores>=1){
        $datos = [
            "estatus"   => "error",
            "msg"   => "Algunos archivos no cumplen con los requisitos permitidos = ".$errores_html,
        ];
        echo json_encode($datos);
    }else{
        $datos = [
            "estatus"   => "ok",
            "msg"   => "Todo se ha subido correctamente",
        ];
        echo json_encode($datos);
    }
}

if($condicion=='biblioteca1'){
    $html = '';

    $sql1 = "SELECT * FROM imagenes";
    $proceso1 = mysqli_query($conexion,$sql1);
    $contador1 = mysqli_num_rows($proceso1);
    if($contador1==0){
        $datos = [
            "estatus"   => "error",
            "html"   => "<div class='col-12 text-center' style='font-size: 19px; font-weight: bold;'>No hay imagenes aún guardadas en la biblioteca<div>",
        ];
        echo json_encode($datos);
        exit;
    }else{
        while($row1=mysqli_fetch_array($proceso1)){
            $id = $row1["id"];
            $nombre = $row1["nombre"];
            $formato = $row1["formato"];
            $ruta = $row1["ruta"];
            $fecha_inicio = $row1["fecha_inicio"];
            $html .= '
                <div class="col-4 text-center">
                    <img class="img-fluid" style="width:300px;" src="../'.$ruta.'">
                    <a href="../'.$ruta.'" target="_blank" style="text-decoration:none;">
                        <button type="button" class="btn btn-primary">Ruta</button>
                    </a>
                    <button type="button" class="btn btn-danger" onclick="eliminar1('.$id.');">Eliminar</button>
                </div>
            ';
        }
    }

     $datos = [
        "estatus"   => "ok",
        "html"   => $html,
    ];
    echo json_encode($datos);
}

if($condicion=='eliminar1'){
    $id = $_POST['id'];

    $sql1 = "SELECT * FROM imagenes WHERE id = ".$id;
    $proceso1 = mysqli_query($conexion,$sql1);
    while($row1=mysqli_fetch_array($proceso1)){
        $ruta = $row1["ruta"];
    }

    $sql2 = "DELETE FROM imagenes WHERE id = ".$id;
    $proceso2 = mysqli_query($conexion,$sql2);

    unlink("../".$ruta);

    $datos = [
        "estatus"   => "ok",
        "msg"   => "Se ha eliminado el archivo exitosamente",
    ];
    echo json_encode($datos);
}

?>