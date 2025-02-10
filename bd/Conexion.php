<?php

function Conectarse(): ?mysqli
{

    $mysqli = new mysqli("localhost", "empresas", "", "crm");


    if ($mysqli->connect_errno) {
        printf("Conexión fallida: %s\n", $mysqli->connect_error);
        exit();
    }

    $mysqli->set_charset('utf8');

    if ($mysqli->ping()) {
        return $mysqli;
    } else {
        printf("Error: %s\n", $mysqli->error);
    }

    $mysqli->close();

    return null;
}

function Desconectarse($bd)
{
    mysqli_close($bd);
}
