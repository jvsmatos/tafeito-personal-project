<?php
// Variáveis de conexão
$servername =   "localhost";
$username   =   "root";
$password   =   "";
$database   =   "tafeito_db";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);


// Verificando a conexão
if($conn->connect_error){
    die("Falha na conexão: ". $conn->connect_error);
}

?>