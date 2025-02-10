

<?php



function ContMapa($conn, $id_proy, $fechaIni, $fechaFin)
{
    // Consulta SQL para obtener los datos de la tabla "compradores"
    $sql = "";  // Ingresar la consulta SQL para obtener los datos de los compradores


    $result = $conn->query($sql);
    $compradores = array();



    if ($result->num_rows > 0) {
        $api_key = "";  // Ingresar la clave de la API de Google Maps
        $i=0;
        while ($row = $result->fetch_assoc()) {
            $id=$i;
            $id_visita = $row['id_visita'];
            $id_comp = $row['id_comp'];
            $dir = ; // Ingresar la dirección del comprador
            $ciu = ; // Ingresar la ciudad del comprador
            $pais = ; // Ingresar el país del comprador
            $compNomb = ; // Ingresar el nombre del comprador
            $tipo_viv = ""; // Ingresar el tipo de vivienda del comprador
            $latitud = ; // Ingresar la latitud del comprador
            $longitud = ; // Ingresar la longitud del comprador
            $ced=; // Ingresar la cédula del comprador
            $i++; // Incrementar el contador

            $compradores[] = [
                '$id' => $id,
                'id_visita' => $id_visita,
                'id_comp' => $id_comp,
                'ced' => $ced,
                'dir' => $dir,
                'ciu' => $ciu,
                'pais' => $pais,
                'compNomb' => $compNomb,
                'tipo_viv' => $tipo_viv,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'id_proy' => $id_proy
            ];
        }
    }
    


?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Mapa de Clientes</title>
        <style>
            #map {
                height: 720px;
                width: 100%;
            }
        </style>
    </head>

    <body>
        <div id="map"></div>
        <div id="cronometro">Cargando...</div>

        <script>
            // Registra el tiempo de inicio de la carga de la página
            const startTime = new Date().getTime();

            function updateCronometro() {
                const currentTime = new Date().getTime();
                const tiempoTranscurrido = (currentTime - startTime) / 1000; // en segundos
                document.getElementById("cronometro").innerText = `Tiempo transcurrido: ${tiempoTranscurrido} segundos`;
            }

            function initMap() {
                // Inicializar el mapa
                const map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat: 4.570868,
                        lng: -74.297333
                    },
                    zoom: 5
                });

                const compradores = <?php echo json_encode($compradores); ?>;

                // Crear un infowindow
                const infowindow = new google.maps.InfoWindow();

                // Agregar marcadores al mapa
                compradores.forEach(comprador => {
                    const latitud = parseFloat(comprador.latitud);
                    const longitud = parseFloat(comprador.longitud);

                    // Verificar si las coordenadas son números válidos
                    if (!isNaN(latitud) && !isNaN(longitud)) {

                        switch (comprador.tipo_viv) {
                            case 'VIP':
                                iconoMarcador = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
                                break;
                            case 'VIS':
                                iconoMarcador = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                                break;
                            default:
                                iconoMarcador = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
                                break;
                        }
                        const link =`https://sette.gruponormandia.co/sette/visita/sette.php?proys=${comprador.id_proy}ced=${comprador.ced}&id_comp=${comprador.id_comp}`;
                        const marker = new google.maps.Marker({
                            position: {
                                lat: latitud,
                                lng: longitud
                            },
                            icon: iconoMarcador,
                            map,
                            title: `<B>[ ${comprador.$id} ]</B><br>
                                <B>ID VISITA :</B> <a href='${link}' target='_blank'>${comprador.id_visita}</a><br>
                                <B>CIUDAD :</B> ${comprador.ciu}<br>
                                <B>DIRECCION :</B> ${comprador.dir}<br>
                                <B>NOMBRE :</B> ${comprador.compNomb}<br>`,
                        });

                        // Agregar un evento de clic al marcador para mostrar la información
                        marker.addListener("click", () => {
                            infowindow.setContent(marker.title);
                            infowindow.open(map, marker);
                        });
                    }else{
                        console.error(`Coordenadas inválidas para el comprador ${comprador.id_comp}`);
                    }
                });

                // Una vez que el mapa se ha cargado completamente, detén el cronómetro
                google.maps.event.addListenerOnce(map, 'tilesloaded', function () {
                    updateCronometro();
                    document.getElementById("cronometro").innerText += " - Carga completa";
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?= $api_key ?>&callback=initMap" async defer></script>
    </body>

    </html>
<?php
    echo "FIN : ".date('H:i:s');

    }
 ?>

