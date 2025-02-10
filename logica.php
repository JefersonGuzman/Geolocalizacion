<?php

$conn=Conectarse();

function listCompSinUbi($conn,$id_proy,$fechaIni,$fechaFin){

    $sql = ""; // Consulta para obtener los compradores sin coordenadas

    $result = $conn->query($sql);
    return $result->num_rows;
}

function listCompConUbi($conn,$id_proy,$fechaIni,$fechaFin){

    $sql = ""; // Consulta para obtener los compradores con coordenadas

    $result = $conn->query($sql);
    return $result->num_rows;
}



function CalcularCoord($conn,$id_proy,$fechaIni,$fechaFin){

    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }
    
    $sql = ""; // Consulta para obtener los compradores sin coordenadas
    
    $result = $conn->query($sql);
    $arrayComp_upd = [];
    $arrayComp_ok = [];
    $contSinUbi=0;
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_visita = $row['id_visita'];
            $id_comp = $row['id_comp'];
            $longitud = ; // Se obtiene la longitud de la base de datos
            $latitud = ; // Se obtiene la latitud de la base de datos
    
            if ($longitud != '' || $latitud != '') {
                $contSinUbi++;
                $arrayIdComp[] = ['id_visita' => $id_visita, 'id_comp' => $id_comp];
            } else {
                // Aquí se calculan las coordenadas usando la API de Google Maps
                $address = ObtDirComp($conn, $id_comp); 
                $coordinates = ObtCoordMaps($address); 
    
                if ($coordinates) {
                    // Actualiza la base de datos con las coordenadas obtenidas
                    $longitud = $coordinates['longitud'];
                    $latitud = $coordinates['latitud'];
                    actualizarCoordenadas($conn, $id_comp, $longitud, $latitud);
                    $arrayIdComp[] = ['id_visita' => $id_visita, 'id_comp' => $id_comp];
                } else {
                    $arrayComp_ok[] = ['id_visita' => $id_visita, 'id_comp' => $id_comp];
                }
            }
        }
    } else {
        echo "No se encontraron resultados.";
    }
    
}


function ObtErrorComp($conn,$id_proy,$fechaIni,$fechaFin){

    $sql = "";    // Consulta para obtener los compradores sin coordenadas
    
    $result = $conn->query($sql);
    return $result->num_rows;
}

// Función para obtener la dirección del comprador desde la base de datos
function ObtDirComp($conn, $id_comp) {
    $sql=" ";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row; // Retorna un array asociativo con los datos
    } else {
        return false; // En caso de error o no encontrar resultados
    }
    
}

function ObtCoordMaps($address) {

    // Prepara la dirección para incluirla en la solicitud a la API
    $api_key = ""; // Ingresar la clave de la API de Google Maps
    $address = 'COLOMBIA'.','.$address['ciudad'].','.$address['direccion'] . ', ' . $address['barrio'];
    $formattedAddress = urlencode($address);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$formattedAddress&key=$api_key";
    $response = file_get_contents($url);

    if ($response === false) {
        // Manejo de errores en caso de que la solicitud falle
        return false;
    }

    // Decodifica la respuesta JSON
    $data = json_decode($response, true);

    // Verifica si la solicitud fue exitosa y si se encontraron resultados
    if ($data && $data['status'] === 'OK' && !empty($data['results'])) {
        // Obtiene las coordenadas de la primera ubicación encontrada
        $location = $data['results'][0]['geometry']['location'];
        $longitud = $location['lng'];
        $latitud = $location['lat'];

        return ['longitud' => $longitud, 'latitud' => $latitud];
    } else {
        return false;
    }
}

// Función para actualizar las coordenadas en la base de datos
function actualizarCoordenadas($conn, $id_comp, $longitud, $latitud) {
    $sql="";
    $result=$conn->query($sql);
}
?>