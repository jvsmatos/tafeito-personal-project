<?php

function setAlert($message, $class, $redirectPage) {
    $_SESSION['msg']    = $message;
    $_SESSION['alert']  = $class;
    header("Location: $redirectPage");
    exit();
}

//Páginas permitidas por cada tipo de usuário
const ALLOWED_PAGES = [
    'admin' => ['dashboard.php', 'edit-servico.php', 'novo-servico.php', 'usuarios.php', 'usuario.php', 'gerenciar-servicos.php'],
    'cliente' => ['pedidos.php', 'pedido.php', 'novo-pedido.php', 'profile.php'],
    'profissional' => ['servicos.php', 'servico.php', 'propostas.php', 'profile.php'],
    'empresa' => ['servicos.php', 'servico.php', 'propostas.php', 'profile.php']
];

function verifyAccess() {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    $userType = $_SESSION['tipo'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Verifica se o tipo de usuário tem permissão para a página atual
    if (isset(ALLOWED_PAGES[$userType]) && !in_array($currentPage, ALLOWED_PAGES[$userType])) {
        // Define o redirecionamento padrão se for admin ou outro tipo de usuário
        $defaultPage = $userType === 'admin' ? 'dashboard.php' : 'profile.php';
        header("Location: $defaultPage");
        exit();
    }
}

function areFieldsEmpty($fields) {
    foreach ($fields as $field) {
        if (empty($field)) {
            return true;
        }
    }
    return false;
}

function isEmailOrNif($conn, $email, $nif) {
    $query = "SELECT * FROM usuario WHERE email = '$email' OR nif = '$nif'";
    
    // Checa se a query foi executada com sucesso
    if ($result = mysqli_query($conn, $query)) {
        // Se retornar linhas, e-mail ou NIF já está registrado
        return mysqli_num_rows($result) > 0;
    } else {
        // Erro na consulta ao banco de dados
        setAlert("Erro ao acessar o banco de dados.", "notvalid", "cadastro.php");
    }
}

function registerUser($conn, $nome, $email, $password_hashed, $tipo, $nif, $dataCad) {
    $query = "INSERT INTO usuario (email, senha, nome, tipo, nif, dataCad) VALUES ('$email', '$password_hashed', '$nome', '$tipo', '$nif', '$dataCad')";
    
    // Executa a query de inserção
    if (mysqli_query($conn, $query)) {
        return true; // Cadastro bem-sucedido
    } else {
        return false; // Falha no cadastro
    }
}

function deleteUser($conn, $userid) {
    $query = "DELETE FROM usuario WHERE id = $userid";
    
    // Executa a query de inserção
    if (mysqli_query($conn, $query)) {
        return true; // Cadastro bem-sucedido
    } else {
        return false; // Falha no cadastro
    }
}

function loginUser($conn, $email, $password) {
    $query = "SELECT * FROM usuario WHERE email = '$email'";
    
    // Executa a query de busca
    if ($result = mysqli_query($conn, $query)) {
        $linha = mysqli_fetch_array($result);
        
        // Verifica se encontrou o usuário e se a senha é válida
        if ($linha && password_verify($password, $linha['senha'])) {
            $_SESSION['nome']   = $linha['nome'];
            $_SESSION['email']  = $linha['email'];
            $_SESSION['tipo']   = $linha['tipo'];
            $_SESSION['id']     = $linha['id'];
            return true; // Login bem-sucedido
        }else{
            setAlert("Credenciais incorretas. Tente novamente.", "notvalid", "login.php");
        }
    }

    return false; // Falha no login
}

function readuser($conn, $userid = null){
    $query = "SELECT * FROM usuario";

    if (!is_null($userid)) {
        $query .= " WHERE id = '$userid'";
    }

    $usuarios = []; // Array para armazenar os usuários

    // Executa a query e traz o resultado
    if ($result = mysqli_query($conn, $query)) {
        while ($linha = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $usuarios[] = $linha; // Adiciona cada usuário ao array
        }

        return $usuarios; // Retorna todos os usuários
    } else {
        setAlert("Não foi possível carregar os dados dos usuários.", "noaccess", "profile.php");
        return null;
    }
}

function updateUser($conn, $userid, $currentEmail, $newEmail, $password, $isAdmin = false, $otherFields = []) {
    $query = "SELECT * FROM usuario WHERE id = '$userid'";
    $result = mysqli_query($conn, $query);
    $linha = mysqli_fetch_array($result);

    // Se o acesso for de usuário comum, valida a senha
    if (!$isAdmin) {
        if (!$linha || !password_verify($password, $linha['senha'])) {
            setAlert("Senha incorreta. Tente novamente.", "notvalid", $_SERVER['HTTP_REFERER']);
            return;
        }

        // Verifica se o e-mail informado é igual ao existente
        if ($currentEmail === $newEmail) {
            setAlert("Informe um e-mail diferente do atual", "notvalid", $_SERVER['HTTP_REFERER']);
            return;
        }

        // Valida a disponibilidade do novo e-mail
        $emailQuery = "SELECT * FROM usuario WHERE email = '$newEmail' AND id != '$userid'";
        $emailresult = mysqli_query($conn, $emailQuery);
        if (mysqli_num_rows($emailresult) > 0) {
            setAlert("Este e-mail já está em uso por outro usuário.", "notvalid", $_SERVER['HTTP_REFERER']);
            return;
        }
    }

    // Monta a query de atualização
    $updateFields = "email = '$newEmail'";
    if ($isAdmin && !empty($otherFields)) {
        // Adiciona outros campos além do e-mail para o administrador
        foreach ($otherFields as $field => $value) {
            $updateFields .= ", $field = '" . mysqli_real_escape_string($conn, $value) . "'";
        }
    }

    $updateQuery = "UPDATE usuario SET $updateFields WHERE id = '$userid'";
    if (mysqli_query($conn, $updateQuery)) {
        if (!$isAdmin) {
            $_SESSION['email'] = $newEmail;
            setAlert("Dados atualizados com sucesso.", "valid", $_SERVER['HTTP_REFERER']);    
        }else{
            setAlert("Usuário atualizado com sucesso.", "valid", $_SERVER['HTTP_REFERER']);
        }
    } else {
        setAlert("Erro ao atualizar os dados.", "error", $_SERVER['HTTP_REFERER']);
    }
}


function newOrder($conn, $userid, $telemovel, $morada, $data, $servico, $tipoedif, $area, $qndocomeca, $situacao, $acabamento, $precoestimado_min, $precoestimado_max, $adicionais, $infoextra){
    $servicoQuery = "SELECT id FROM servicos WHERE tag = '$servico'";
    $result = mysqli_query($conn, $servicoQuery);
    
    // Verifica se o serviço foi encontrado
    if ($row = mysqli_fetch_assoc($result)) {
        $servicoId = $row['id'];
    } else {
        return false; // Serviço não encontrado
    }
    
    $query = "INSERT INTO pedidos (usuarioid, telemovel, morada, dataCad, servico, tipoedif, area, qndocomeca, situacao, acabamento, precoestimado_min, precoestimado_max, adicionais, infoextra, orderStatus) 
    VALUES ('$userid', '$telemovel', '$morada', '$data', $servicoId, '$tipoedif', $area, '$qndocomeca', '$situacao', '$acabamento', $precoestimado_min, $precoestimado_max, '$adicionais', '$infoextra', 'waiting')";
    
    // Executa a query de inserção
    if (mysqli_query($conn, $query)) {
        return true; // Cadastro bem-sucedido
    } else {
        return false; // Falha no cadastro
    }
}

function readOrders($conn, $userid = null, $filter = null) {
    // Inicia a consulta básica com LEFT JOIN e COUNT para contar as propostas
    $query = "
        SELECT p.*, COUNT(pr.id) as num_propostas, pr.status
        FROM pedidos p
        LEFT JOIN propostas pr ON p.id = pr.pedidoid
    ";

    // Condicionalmente adiciona um filtro (WHERE)
    if (!is_null($userid)) {
        $query .= " WHERE p.usuarioid = '$userid'";
    }

    // Se houver um filtro extra, adiciona como AND ao WHERE existente
    if (!is_null($filter)) {
        if (!is_null($userid)) {
            $query .= " AND $filter";
        } else {
            $query .= " WHERE $filter";
        }
    }

    // Agrupa pelo pedido para contar corretamente as propostas
    $query .= " GROUP BY p.id";

    $pedidos = []; // Inicializa um array para armazenar os pedidos

    if ($result = mysqli_query($conn, $query)) {
        // Loop para armazenar cada pedido encontrado no array
        while ($linha = mysqli_fetch_assoc($result)) {
            
            $servicoQuery = "SELECT nome FROM servicos WHERE id = $linha[servico]";
            $result1 = mysqli_query($conn, $servicoQuery);
            
            // Verifica se o serviço foi encontrado
            if ($row = mysqli_fetch_assoc($result1)) {
                $nomeServico = $row['nome'];
            } else {
                return false; // Serviço não encontrado
            }
            
            $pedidos[] = [
                'id'            => $linha['id'],
                'usuarioid'     => $linha['usuarioid'],
                'telemovel'     => $linha['telemovel'],
                'morada'        => $linha['morada'],
                'data'          => date('d/m/Y', strtotime($linha['dataCad'])),
                'tipo_servico'  => $nomeServico,
                'tipo_edif'     => $linha['tipoedif'],
                'area'          => $linha['area'],
                'qndocomeca'    => $linha['qndocomeca'],
                'situacao'      => $linha['situacao'],
                'acabamento'    => $linha['acabamento'],
                'pestimado_min' => $linha['precoestimado_min'],
                'pestimado_max' => $linha['precoestimado_max'],
                'adicionais'    => $linha['adicionais'],
                'infoextra'     => $linha['infoextra'],
                'status'        => $linha['orderStatus'],
                'propstatus'    => $linha['status'],
                'num_propostas' => $linha['num_propostas']  // Número de propostas associadas
            ];
            
           // var_dump($linha);
        }
    } else {
        setAlert("Não foi possível carregar os dados do usuário.", "noaccess", "profile.php");
    }

    return $pedidos; // Retorna o array de pedidos
}

function getProposalsByOrder($conn, $orderid, $userid = null) {
    $query = "SELECT p.*, u.nome, u.nif FROM propostas p LEFT JOIN usuario u ON u.id = p.usuarioid WHERE p.pedidoid = $orderid";
    
    if(!is_null($userid)) {
        $query .= " AND p.usuarioid = $userid";
    }

    $proposals = []; // Inicializa um array para armazenar os pedidos

    if ($result = mysqli_query($conn, $query)) {
        // Loop para armazenar cada pedido encontrado no array
        while ($linha = mysqli_fetch_assoc($result)) {
            $proposals[] = [
                'id'            => $linha['id'],
                'usuarioid'     => $linha['usuarioid'],
                'descricao'     => $linha['descricao'],
                'pproposto'     => $linha['precoproposto'],
                'fee'           => $linha['fee'],
                'data'          => date('d/m/Y', strtotime($linha['data'])),
                'pedidoid'      => $linha['pedidoid'],
                'status'        => $linha['status'],
                'nome'          => $linha['nome'],
                'nif'           => $linha['nif']
            ];
        }
    } else {
        setAlert("Não foi possível carregar as propostas deste pedido.", "noaccess", "pedidos.php");
    }

    return $proposals; // Retorna o array de propostas
}

function getStatusMessage($status) {
    switch ($status) {
        case 'canceled':
            return 'Cancelado';
        case 'sent':
            return 'Proposta enviada';
        case 'rejected':
            return 'Proposta recusada';
        case 'approved':
            return 'Aprovado';
        default:
            return 'Aguardando propostas';
    }
}

function newProposal($conn, $pedidoid, $userid, $descricao, $pproposto, $fee, $data){
    $query = "INSERT INTO propostas (usuarioid, descricao, precoproposto, fee, data, pedidoid, status)
    VALUES ('$userid', '$descricao', $pproposto, $fee, '$data', $pedidoid, 'sent')";
    
    //Executa a query de inserção
    if (mysqli_query($conn, $query)) {
        return true; // Cadastro bem-sucedido
    } else {
        return false; // Falha no cadastro
    }
}

function readServices($conn) {
    $query = "SELECT * FROM servicos";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $valoresServicos = [];

        while ($linha = mysqli_fetch_assoc($result)) {
            $valoresServicos[$linha['tag']] = [
                'valorMinimo' => (float) $linha['valorMinimo'],
                'valorMaximo' => (float) $linha['valorMaximo'],
                'valorMinimoServico' => (float) $linha['valorMinimoServico']
            ];
        }

        // Certifique-se de retornar o JSON corretamente
        return json_encode($valoresServicos, JSON_PRETTY_PRINT);
    } else {
        // Se a consulta falhar, retorne um erro
        return json_encode(['error' => 'Erro na consulta ao banco de dados']);
    }
}

function deleteService($conn, $serviceid) {
    $query = "DELETE FROM servicos WHERE id = $serviceid";
    
    // Executa a query de inserção
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function getOrderStats($conn) {
    $query = "SELECT 
                  SUM(orderStatus = 'approved') AS aprovados,
                  SUM(orderStatus = 'waiting') AS aguardando_propostas,
                  SUM(orderStatus = 'canceled') AS cancelados
              FROM pedidos";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getUserStats($conn) {
    $query = "SELECT 
                  SUM(tipo = 'cliente') AS clientes,
                  SUM(tipo = 'empresa') AS empresas,
                  SUM(tipo = 'profissional') AS profissionais,
                  SUM(tipo = 'admin') AS admins
              FROM usuario";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getProposalStats($conn) {
    $query = "SELECT 
                  SUM(status = 'sent') AS enviadas,
                  SUM(status = 'approved') AS aprovadas,
                  SUM(status = 'rejected') AS rejeitadas
              FROM propostas";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function countServiceStats($conn) {
    $query = "SELECT COUNT(*) AS total_servicos FROM servicos";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getServices($conn, $serviceId = null) {
    $query = "SELECT * FROM servicos";
    
    if(!is_null($serviceId)) {
        $query .= " WHERE id = $serviceId";
    }
    $servicos = [];

    // Executa a query e traz o resultado
    if ($result = mysqli_query($conn, $query)) {
        while ($linha = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $servicos[] = $linha;
        }

        return $servicos;
    }
}

function newService($conn, $tag, $nome, $pmin, $pmax, $pminserv){
    $query = "INSERT INTO servicos (tag, nome, valorMinimo, valorMaximo, valorMinimoServico)
    VALUES ('$tag', '$nome', $pmin, $pmax, $pminserv)";
    
    //Executa a query de inserção
    if (mysqli_query($conn, $query)) {
        return true; // Cadastro bem-sucedido
    } else {
        return false; // Falha no cadastro
    }
}



?>