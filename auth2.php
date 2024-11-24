<?php
session_start();
include 'conexao.php';  // Inclui o arquivo de conexão
include 'functions.php'; // Inclui o arquivo com as funções utilizadas

// Identifica qual ação o formulário está solicitando (login ou cadastro)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];
    
    if ($action == 'pedido') {
        //Recebe o range de preço através do input hidden, remove caracteres e separa os valores
        $totalValue     = $_POST['totalValue'];
        $totalValue     = str_replace(['€', ' ', ''], '', $totalValue);
        $values         = explode('-', $totalValue);
        $minValue       = isset($values[0]) ? floatval(trim($values[0])) : 0.00;
        $maxValue       = isset($values[1]) ? floatval(trim($values[1])) : 0.00;

        $morada         = htmlspecialchars($_POST['morada']);
        $cep            = htmlspecialchars($_POST['cep']);
        $morada         = $morada .' - '. $cep;
        $telemovel      = htmlspecialchars($_POST['telemovel']);
        $servico        = htmlspecialchars($_POST['servico']);
        $tipoedif       = htmlspecialchars($_POST['tipo']);
        $area           = floatval($_POST['area']);
        $inicio         = str_replace('-', ' ', htmlspecialchars($_POST['inicio']));
        $estado         = str_replace('-', ' ', htmlspecialchars($_POST['estado']));
        $acabamento     = htmlspecialchars($_POST['acabamento']);
        $adicionais     = isset($_POST['adicionais']) ? implode(", ",$_POST['adicionais']) : ''; //Transformar o array em uma lista separada por virgulas
        $obs            = htmlspecialchars($_POST['obs']);
        $dataCad        = date('Y-m-d H:i:s');

        if(newOrder($conn, $_SESSION['id'], $telemovel, $morada, $dataCad, $servico, $tipoedif, $area, $inicio, $estado, $acabamento, $minValue, $maxValue, $adicionais, $obs)){
            setAlert("Pedido criado com sucesso.", "valid", "novo-pedido.php");
        }else{
            setAlert("Não foi possível criar o seu pedido, tente novamente", "notvalid", "novo-pedido.php");
        }
    }elseif($action == 'accept' && $_POST['proposal_id'] != null && $_POST['order_id'] != null){
        $proposalid = $_POST['proposal_id'];
        $orderid    = $_POST['order_id'];

        $query  = "UPDATE propostas SET status = 'approved' WHERE id = '$proposalid' AND pedidoid = '$orderid'";
        $query2 = "UPDATE propostas SET status = 'rejected' WHERE id != '$proposalid' AND pedidoid = '$orderid'";
        $query3 = "UPDATE pedidos SET orderStatus = 'approved' WHERE id = '$orderid'";
        mysqli_query($conn, $query);
        mysqli_query($conn, $query2);
        mysqli_query($conn, $query3);

    }elseif($action == 'reject' && $_POST['proposal_id'] != null){
        $proposalid = $_POST['proposal_id'];
        $query = "UPDATE propostas SET status = 'rejected' WHERE id = '$proposalid'";
        mysqli_query($conn, $query);
    
    }elseif($action == 'proposta'){
        $descricao      = htmlspecialchars($_POST['proposta']);
        $valor          = floatval($_POST['valor']);
        $data           = date('Y-m-d H:i:s');
        $orderid        = $_POST['orderid'];
        $fee            = $valor*0.1;

        if(newProposal($conn, $orderid, $_SESSION['id'], $descricao, $valor, $fee, $data)){
            setAlert("Pedido criado com sucesso.", "valid", "servico.php?id=$orderid");
        }else{
            setAlert("Não foi possível criar o seu pedido, tente novamente", "notvalid", "servico.php?id=$orderid");
        }

    }elseif($action == 'delete'){
        if(isset($_POST['user_id'])){
            $userid = $_POST['user_id'];

            if(deleteUser($conn, $userid)){
                setAlert("Usuário removido com sucesso.", "valid", "clientes.php");
            }else{
                setAlert("Não foi possível remover o usuário", "notvalid", "clientes.php");
            }
        }elseif(isset($_POST['servico_id'])){
            $servicoid = $_POST['servico_id'];

            if(deleteService($conn, $servicoid)){
                setAlert("Serviço removido com sucesso.", "valid", "gerenciar-servicos.php");
            }else{
                setAlert("Não foi possível remover o serviço", "notvalid", "gerenciar-servicos.php");
            }
        }
    }elseif($action == 'cancel'){
        $orderid = $_POST['order_id'];
        $query  = "UPDATE pedidos SET orderStatus = 'canceled' WHERE id = '$orderid'";
        $query2 = "UPDATE propostas SET status = 'rejected' WHERE pedidoid = '$orderid'";
        mysqli_query($conn, $query);
        mysqli_query($conn, $query2);
    }

    // Encerra a conexão com o banco de dados manualmente
    $conn->close();
}

?>