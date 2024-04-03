<?php
$servername = "localhost";
$username = "Operatore";
$password = "12345";
$dbname = "omb-valves";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$array = explode('/',$_SERVER['REQUEST_URI']); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Content-Type:application/json");   
    $body=file_get_contents('php://input');
    $data = json_decode($body,true);
    $nome=$data["Nome"];
    $Descrizione=$data["Descizione"];
    $sql = " INSERT INTO valvole (Nome, Descrizione) VALUES ('$nome','$Descrizione')";
    $result = $conn->query($sql);
    http_response_code(200); 
}
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    header("Content-Type:application/json");   
    $body=file_get_contents('php://input');
    $data = json_decode($body,true);
    $IdMod=$data["IdMod"];
    $campo=$data["Campo"];
    $Agg=$data["Aggiornato"];
    try{
    $sql = "UPDATE valvole SET $campo= '$Agg' WHERE Id_valvola = '$IdMod'" ;
    $result = $conn->query($sql);
    http_response_code(200);
    }
    catch(Exception $ecc){
        http_response_code(405);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    header("Content-Type:application/json");   
    $body=file_get_contents('php://input');
    $data = json_decode($body,true);
    $Idmod = $array[2];
    $Id=$data["Id"];
    $Nome=$data["Nome"];
    $Descrizione=$data["Descrizione"];
    try{
    $sql = "UPDATE valvole SET id_valvola= '$Id', Nome='$Nome',Descrizione='$Descrizione' WHERE Id_valvola = '$Idmod'" ;
    $result = $conn->query($sql);
    http_response_code(200);
    }
    catch(Exception $ecc){
        http_response_code(405);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (count($array) == 3 && $array[2] != '')
    {
        $nome = $array[2];
        $sql = "DELETE FROM valvole WHERE Nome = '$nome'";
        $result = $conn->query($sql);   
            http_response_code(200); 

    }
    else if(count($array) == 3 && $array[2] == '')
    {
        $sql = "SELECT * FROM valvole";
        $result = $conn->query($sql);
       
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo "Nessun risultato trovato nella tabella.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (count($array) == 3 && $array[2] != '')
    {
        // Se è specificato un ID nella richiesta GET
        $nome = $array[2];
        $sql = "SELECT * FROM valvole WHERE Nome = '$nome'";
        $result = $conn->query($sql);   
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo "Nessun risultato trovato con ID $nome";
        }
    }
    else if(count($array) == 3 && $array[2] == '')
    {
        // Se non è specificato un ID nella richiesta GET
        $sql = "SELECT * FROM valvole";
        $result = $conn->query($sql);
       
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo "Nessun risultato trovato nella tabella.";
        }
    }
}


$conn->close();

?>