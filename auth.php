<?php
session_start();
include 'conexao.php';  // Inclui o arquivo de conexão
include 'functions.php'; // Inclui o arquivo com as funções utilizadas

// Identifica qual ação o formulário está solicitando (login ou cadastro)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];
    
    if ($action == 'login') {
        $email      = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password   = $_POST['password'];

        // PROCESSAR LOGIN
        if(loginUser($conn, $email, $password)){
            setAlert("Login realizado com sucesso.", "valid", "login.php");
        }else{
            session_unset();
            setAlert("Erro ao acessar o banco de dados.", "notvalid", "login.php");
        }
    
    } elseif ($action == 'cadastro') {
        $nome       = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
        $email      = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha      = $_POST['pass'];
        $senha2     = $_POST['pass2']; 
        $tipo       = htmlspecialchars($_POST['tipo'], ENT_QUOTES, 'UTF-8');
        $nif        = filter_input(INPUT_POST, 'nif', FILTER_VALIDATE_INT);

        if($senha !== $senha2){
            setAlert("As senhas não coincidem.", "notvalid", "cadastro.php");
        }

        if(areFieldsEmpty($nome, $email, $senha, $tipo, $nif)){
            setAlert("É obrigatório preencher todos os campos.", "notvalid", "cadastro.php");
        }

        if(isEmailOrNif($conn, $email, $nif)){
            setAlert("E-mail ou NIF já está cadastrado", "notvalid", "cadastro.php");
        }

        $password_hashed        = password_hash($senha, PASSWORD_DEFAULT);
        $dataCad                = date('Y-m-d H:i:s'); 

        if(registerUser($conn, $nome, $email, $password_hashed, $tipo, $nif, $dataCad)){
            setAlert("Cadastro realizado com sucesso.", "valid", "cadastro.php");
        }else{
            setAlert("Não foi possível realizar o cadastro.", "notvalid", "cadastro.php");
        }

    }elseif ($action == 'atualizar') {
        // Verifica se é administrador
        $isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
        
        if(!$isAdmin){
            $currentEmail = $_SESSION['email'];
            $password = $_POST['pass'];
        }

        $newEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (areFieldsEmpty($newEmail)) {
            setAlert("É obrigatório informar um e-mail.", "notvalid", $_SERVER['HTTP_REFERER']);
            return;
        }
    
        // Inicializa o array de campos adicionais
        $otherFields = [];
    
        if ($isAdmin) {
            // Obtém e sanitiza os campos adicionais
            $nif = htmlspecialchars($_POST['nif']);
            $tipo = htmlspecialchars($_POST['tipo']);
    
            // Verifica se os campos obrigatórios do administrador estão preenchidos
            if (areFieldsEmpty($nif, $tipo)) {
                setAlert("Todos os campos são obrigatórios.", "notvalid", $_SERVER['HTTP_REFERER']);
                return;
            }

            $userid = $_POST['clienteid'];
    
            // Adiciona os campos adicionais ao array
            $otherFields = [
                'nif' => $nif,
                'tipo' => $tipo,
            ];

            // Chama a função updateUser com os parâmetros adicionais para o administrador
            updateUser($conn, $userid, $currentEmail, $newEmail, "", true, $otherFields);
        } else {
            // Caso seja o próprio usuário, mantém o comportamento original
            updateUser($conn, $_SESSION['id'], $currentEmail, $newEmail, $password);
        }
    }elseif ($action == 'atualizarServico') {
        $nomeServico = mysqli_real_escape_string($conn, trim($_POST['nome']));
        $pmin        = floatval($_POST['pmin']);
        $pmax        = floatval($_POST['pmax']);
        $pminServ    = floatval($_POST['pminserv']);
        $servicoId   = mysqli_real_escape_string($conn, $_POST['servicoid']);

        // Gera a tag a partir do nome do serviço
        $tag = trim($nomeServico);
        $tag = strtolower($tag);
        $tag = str_replace(' ', '-', $tag);
        $tag = preg_replace('/[^a-z0-9\-]/', '', $tag);
        $tag = mysqli_real_escape_string($conn, $tag);

        if (areFieldsEmpty([$nomeServico, $pmin, $pmax, $pminServ])) {
            setAlert("Todos os campos são obrigatórios.", "notvalid", $_SERVER['HTTP_REFERER']);
            return;
        }

        $updateQuery = "UPDATE servicos 
                        SET tag = '$tag', 
                            nome = '$nomeServico', 
                            valorMinimo = $pmin, 
                            valorMaximo = $pmax, 
                            valorMinimoServico = $pminServ 
                        WHERE id = '$servicoId'";
        if(mysqli_query($conn, $updateQuery)) {
            setAlert("Serviço atualizado com sucesso.", "valid", $_SERVER['HTTP_REFERER']);    
        } else {
            setAlert("Erro ao atualizar o serviço.", "error", $_SERVER['HTTP_REFERER']);
        }
    }elseif ($action == 'addServico') {
        $nomeServico = mysqli_real_escape_string($conn, trim($_POST['nome']));
        $pmin        = floatval($_POST['pmin']);
        $pmax        = floatval($_POST['pmax']);
        $pminServ    = floatval($_POST['pminserv']);

        // Gera a tag a partir do nome do serviço
        $tag = trim($nomeServico);
        $tag = strtolower($tag);
        $tag = str_replace(' ', '-', $tag);
        $tag = preg_replace('/[^a-z0-9\-]/', '', $tag);
        $tag = mysqli_real_escape_string($conn, $tag);

        if (areFieldsEmpty([$nomeServico, $pmin, $pmax, $pminServ])) {
            setAlert("Todos os campos são obrigatórios.", "notvalid", $_SERVER['HTTP_REFERER']);
            return;
        }

        if(newService($conn, $tag, $nomeServico, $pmin, $pmax, $pminServ)){
            setAlert("Serviço cadastrado com sucesso.", "valid", "novo-servico.php");
        }else{
            setAlert("Não foi possível cadastrar o serviço.", "notvalid", "cadastro.php");
        } 
    }

    // Encerra a conexão com o banco de dados manualmente
    $conn->close();
}
?>
