<?php
session_start();
include 'conexao.php';
include 'functions.php';

verifyAccess();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TáFeito - Nunca foi tão fácil conseguir bons profissionais!</title>

    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/orders.css">
    <script src="https://kit.fontawesome.com/3c8c59d717.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="shortcut icon" href="images/TF.png" type="image/x-icon">
    
</head>
<body>
    
    <div class="container">
    <!-- HEADER -->
        <nav id="header">
            <div class="header-container">
                <div class="nav-logo">
                    <a href="index.php">
                        <p class="nav-name">Tá Feito<span>.</span></p>
                    </a>
                    <?php if($_SESSION['tipo'] == 'admin'){ ?>
                    <span class="small-info">admin</span>
                    <?php } ?>
                </div>
                <div class="nav-menu" id="myMenu">
                    <ul class="nav_menu_list">   

                    <?php
                        // Menu dinâmico com base no tipo de usuário
                        $menus = [
                            'cliente' => [
                                'pedidos.php' => 'Meus Pedidos',
                                'novo-pedido.php' => 'Novo Pedido',
                                'profile.php' => 'Perfil Usuário',
                            ],
                            'profissional' => [
                                'servicos.php' => 'Serviços',
                                'propostas.php' => 'Minhas Propostas',
                                'profile.php' => 'Perfil Usuário',
                            ],
                            'empresa' => [
                                'servicos.php' => 'Serviços',
                                'propostas.php' => 'Minhas Propostas',
                                'profile.php' => 'Perfil Usuário',
                            ],
                            'admin' => [
                                'usuarios.php' => 'Usuarios',
                                'gerenciar-servicos.php' => 'Serviços',
                                'dashboard.php' => 'Dashboard',
                            ]
                        ];

                        foreach ($menus[$_SESSION['tipo']] as $link => $label) {
                            echo "<li class='nav_list'><a href='$link' class='nav-link'>$label</a></li>";
                        }
                        ?>

                        <li class="nav_list">
                            <a href="sair.php" class="nav-link">Sair</a>
                        </li>
                    </ul>
                </div>
                <div class="nav-menu-btn">
                    <i class="fa-solid fa-bars" id="mobile-menu" onclick="menuFunction()"></i>
                </div>
            </div>
        </nav>