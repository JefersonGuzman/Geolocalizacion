<?php

include("./bd/Conexion.php");
include("./bd/Tramites.php");
$conn = Conectarse();



if (isset($_POST['id_proy'])) {
    $id_proy = $_POST['id_proy'];
    $nom_proy = '';
    $nom_proy = Cons_campo($conn, 'proyecto', 'nombre', $id_proy);
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/estilo/estilo.css">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <title>Geolocalizacion | Clientes</title>


</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <section class="col-12 logo-empresa">
                    <img src="./src/img/logo-empresa.png" alt="">
                </section>
                <form action="index.php" method="POST">
                    <section class="col-12 form">
                        <div class="borde-form"></div>
                        <div class="form-principal">
                            <div class="mb-3">
                                <label for="id_proy" class="form-label">SELECCIONE LA SUCURSAL</label>
                                <select class='form-control id_proy' id='elemnt_13' name='id_proy' required>
                                    <option value=""></option>
                                    <?php
                                    $query2 = "SELECT * FROM proyecto";
                                    $result2 = $conn->query($query2);
                                    if ($row2 = mysqli_fetch_array($result2)) {
                                        do { ?>
                                            <option value='<?= $row2["id"] ?>'><?= $row2["nombre"] ?></option>
                                    <?php } while ($row2 = mysqli_fetch_array($result2));
                                        mysqli_free_result($result2);
                                    } ?>
                                </select>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>FECHA INICIO</p>
                                    <input type="date" name="fechaIni" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <p>FECHA FIN</p>
                                    <input type="date" name="fechaFin" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <input type="submit" class="btn btn-primary">
                            </div>
                        </div>
                        <div class="borde-form"></div>
                    </section>
                </form>

                <?php
                    if (isset($_POST['fechaIni']) && isset($_POST['fechaFin'])) {
                        $fechaIni = $_POST['fechaIni'];
                        $fechaFin = $_POST['fechaFin'];
                        include('logica.php');

                        if(isset($_POST['tipoForm']) && $_POST['tipoForm']  == 1){
                            CalcularCoord($conn,$id_proy,$fechaIni,$fechaFin);
                        }

                        echo "<section class='col-sm-12 col-md-6 table-inf'>";
                            echo "<table class='table table-bordered'>";
                                echo "<thead class='text-left'>";
                                    echo "<tr class='text-center'>";
                                        echo "<th colspan='3'>COMPRADORES</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                    echo "<tr>";
                                        echo "<td class='  text-wrap'>SIN LOCALIZAR</td>";
                                        echo "<td>".listCompSinUbi($conn,$id_proy,$fechaIni,$fechaFin)."</td>";
                                        echo "<form action='index.php' method='POST'>";
                                            echo "<input type='hidden' name='id_proy' value='$id_proy'/>";
                                            echo "<input type='hidden' name='fechaIni' value='$fechaIni'/>";
                                            echo "<input type='hidden' name='fechaFin' value='$fechaFin'/>";
                                            echo "<input type='hidden' name='tipoForm' value='1'/>";
                                            echo "<td class='text-wrap'><input type='submit' class='btn btn-secondary' value='LOCALIZAR'/></td>";
                                        echo "</form>";
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<form action='index.php' method='POST'>";
                                                echo "<td class='  text-wrap'>LOCALIZADOS</td>";
                                                echo "<td>".listCompConUbi($conn,$id_proy,$fechaIni,$fechaFin)."</td>";
                                                echo "<input type='hidden' name='id_proy' value='$id_proy'/>";
                                                echo "<input type='hidden' name='fechaIni' value='$fechaIni'/>";
                                                echo "<input type='hidden' name='fechaFin' value='$fechaFin'/>";
                                                echo "<input type='hidden' name='tipoForm' value='2'/>";
                                                echo "<td class='text-wrap'><input type='submit' class='btn btn-success' value='GRAFICAR'/></td>";
                                        echo "</form>";
                                    echo "</tr>";
                                    echo "<tr>";
                                                echo "<td class='  text-wrap'>DIRECCION CON ERROR</td>";
                                                echo "<td>".ObtErrorComp($conn,$id_proy,$fechaIni,$fechaFin)."</td>";
                                                echo "<input type='hidden' name='id_proy' value='$id_proy'/>";
                                                echo "<input type='hidden' name='fechaIni' value='$fechaIni'/>";
                                                echo "<input type='hidden' name='fechaFin' value='$fechaFin'/>";
                                                echo "<td class='text-wrap'>
                                                        <a  href='list-comp-error.php?id_proy=$id_proy&fechaIni=$fechaIni&fechaFin=$fechaFin' target='_blank' class='btn btn-info'>VER LISTADO</a>
                                                    </td>";
                                    echo "</tr>";
                                    
                                echo "</tbody>";
                            echo "</table>";
                            
                        echo "</section>";
                        echo "<div class='borde-form'></div>";

                    }
                ?>
            </div>
            <div class="col-sm-12 col-md-6 cont-map">
                <?php 
                    if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == 2){
                        date_default_timezone_set('America/Bogota');
                        echo "Inicio : ".date('H:i:s');
                        require('mapa.php');
                        ContMapa($conn,$id_proy,$fechaIni,$fechaFin);
                    }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>