<?php


include("./bd/Conexion.php");
include("./bd/Tramites.php");
$conn = Conectarse();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/estilo/estilo.css">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <title>Geolocalizacion | ERROR </title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="cont-table-comp col-7">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID COMP</th>
                            <th>NOMBRE</th>
                            <th>CIUDAD</th>
                            <th>DIRECCION</th>
                            <th>MEDIO</th>
                            <th>VER</th>

                        </tr>
                    </thead>
                    <tbody>
                            <?php
                                $cantidad = 0;

                                $id_proy  = $_GET['id_proy'];
                                $fechaIni = $_GET['fechaIni'];
                                $fechaFin = $_GET['fechaFin'];
    
                                $sql = ""; // Consulta para obtener los compradores sin coordenadas
    
                                $result = $conn->query($sql);
                                if ($row = mysqli_fetch_array($result)) {
                                    do {
                                        $cantidad++;
                                        $medio = ; // Se obtiene el medio de la base de datos
                                        echo "<tr>"; 
                                            echo "<td>" . $cantidad . "</td>";
                                            echo "<td>" . $row['id_comp'] . "</td>";
                                            echo "<td>" . $row['nombres'] . "</td>";
                                            echo "<td>" . $row['ciudad'] . "</td>";
                                            echo "<td>" . $row['direccion'] . "</td>";
                                            echo "<td>" . $medio . "</td>";                                      
                                            echo "<td><a href='index.php' target='_black'>Ver</a></td>";
                                        echo "</tr>";
    
                                    } while ($row = mysqli_fetch_array($result));
                                }
                            ?>
                    </tbody>
                </table>
            </div>
            <div class="cont-table-comp col-5">
                <div class="cont-card-num-comp">
                    <p>
                        COMPRADORES : 
                        <span class="cont-valor">
                            <?= number_format($cantidad) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>