<?php
// Inclua o arquivo com as funções e a conexão
include 'conexao.php';
include 'functions.php';

// Configura o cabeçalho para JSON
header('Content-Type: application/json');


echo readServices($conn); 
?>