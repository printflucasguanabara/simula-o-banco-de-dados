<?php

session_start();

// verificar se o array de dados já existe na sessão
if (!isset($_SESSION['banco_de_dados'])) {
    $_SESSION['banco_de_dados'] = array();
}

// inserir dados no banco de dados
function inserirDados($dados) {
    $_SESSION['banco_de_dados'][] = $dados;
}

// pesquisar dados no banco de dados
function pesquisarDados($chave) {
    $resultados = array();

    foreach ($_SESSION['banco_de_dados'] as $registro) {
        if (strpos($registro['nome'], $chave) !== false) {
            $resultados[] = $registro;
        }
    }

    return $resultados;
}

// função para alterar dados no banco de dados
function alterarDados($indice, $novosDados) {
    if (isset($_SESSION['banco_de_dados'][$indice])) {
        $_SESSION['banco_de_dados'][$indice] = $novosDados;
        return true;
    } else {
        return false;
    }
}

// excluir dados do banco de dados
function excluirDados($indice) {
    if (isset($_SESSION['banco_de_dados'][$indice])) {
        unset($_SESSION['banco_de_dados'][$indice]);
        $_SESSION['banco_de_dados'] = array_values($_SESSION['banco_de_dados']);
        return true;
    } else {
        return false;
    }
}

// processar as ações do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['acao'] === 'inserir') {
        $novoRegistro = array(
            'nome' => $_POST['nome'],
            'email' => $_POST['email']
        );
        inserirDados($novoRegistro);
    } elseif ($_POST['acao'] === 'pesquisar') {
        $termoPesquisa = $_POST['termo_pesquisa'];
        $resultados = pesquisarDados($termoPesquisa);
    } elseif ($_POST['acao'] === 'alterar') {
        $indice = $_POST['indice'];
        $novosDados = array(
            'nome' => $_POST['novo_nome'],
            'email' => $_POST['novo_email']
        );
        alterarDados($indice, $novosDados);
    } elseif ($_POST['acao'] === 'excluir') {
        $indice = $_POST['indice'];
        excluirDados($indice);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Sessions e Cookies</title>
</head>
<body>
    <h1>Gerenciador de Dados</h1>

    <h2>Inserir Dados</h2>
    <form method="POST">
        Nome: <input type="text" name="nome"><br>
        Email: <input type="text" name="email"><br>
        <input type="hidden" name="acao" value="inserir">
        <input type="submit" value="Inserir">
    </form>

    <h2>Pesquisar Dados</h2>
    <form method="POST">
        Pesquisar por Nome: <input type="text" name="termo_pesquisa"><br>
        <input type="hidden" name="acao" value="pesquisar">
        <input type="submit" value="Pesquisar">
    </form>

    <?php if (isset($resultados)): ?>
    <h2>Resultados da Pesquisa</h2>
    <ul>
        <?php foreach ($resultados as $indice => $registro): ?>
            <li>
                Nome: <?php echo $registro['nome']; ?><br>
                Email: <?php echo $registro['email']; ?><br>
                <form method="POST">
                    Novo Nome: <input type="text" name="novo_nome"><br>
                    Novo Email: <input type="text" name="novo_email"><br>
                    <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                    <input type="hidden" name="acao" value="alterar">
                    <input type="submit" value="Alterar">
                </form>
                <form method="POST">
                    <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="submit" value="Excluir">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</body>
</html>

