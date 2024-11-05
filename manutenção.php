<?php

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "longa_vida";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$numero = $descricao = $valor = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["incluir"])) {
    $numero = $_POST['numero'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO Plano (Numero, Descricao, Valor) VALUES ('$numero', '$descricao', '$valor')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='message success'>Plano adicionado com sucesso!</div>";
    } else {
        echo "<div class='message error'>Erro ao adicionar plano: " . $conn->error . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["alterar"])) {
    $numero = $_POST['numero'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    $sql = "UPDATE Plano SET Descricao='$descricao', Valor='$valor' WHERE Numero='$numero'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='message success'>Plano alterado com sucesso!</div>";
    } else {
        echo "<div class='message error'>Erro ao alterar plano: " . $conn->error . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["excluir"])) {
    $numero = $_POST['numero'];

    $sql = "DELETE FROM Plano WHERE Numero='$numero'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='message success'>Plano excluído com sucesso!</div>";
    } else {
        echo "<div class='message error'>Erro ao excluir plano: " . $conn->error . "</div>";
    }
}

$queryPlanos = "SELECT Numero, Descricao, Valor FROM Plano";
$resultPlanos = $conn->query($queryPlanos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Planos Longa Vida</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7e4e0; 
            margin: 0;
            padding: 20px;
            color: #6a1b3d; 
        }
        h1 {
            text-align: center;
            color: #9e2a2f; 
            font-size: 2.5em;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-container, .message {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2, .message {
            text-align: center;
            color: #9e2a2f; 
            margin-bottom: 15px;
        }
        .form-container input, .form-container button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #9e2a2f; 
            border-radius: 6px;
            font-size: 1rem;
        }
        .form-container button {
            background-color: #9e2a2f; 
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #7a1f26; 
        
        .table-container {
            margin-top: 40px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f4c9d1; 
        }
        th {
            background-color: #f7e4e0; 
            color: #9e2a2f;
        }
        td {
            color: #6a1b3d; 
        }
        .message.success {
            color: #388E3C; 
            font-weight: bold;
        }
        .message.error {
            color: #E53935; * Verme
        
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Gerenciamento de Planos Longa Vida</h1>

        <div class="form-container">
            <h2>Incluir Novo Plano</h2>
            <form method="POST">
                <input type="text" name="numero" placeholder="Número do Plano" required>
                <input type="text" name="descricao" placeholder="Descrição do Plano" required>
                <input type="number" step="0.01" name="valor" placeholder="Valor do Plano" required>
                <button type="submit" name="incluir">Incluir Plano</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Alterar Plano</h2>
            <form method="POST">
                <input type="text" name="numero" placeholder="Número do Plano" required>
                <input type="text" name="descricao" placeholder="Nova Descrição do Plano" required>
                <input type="number" step="0.01" name="valor" placeholder="Novo Valor do Plano" required>
                <button type="submit" name="alterar">Alterar Plano</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Excluir Plano</h2>
            <form method="POST">
                <input type="text" name="numero" placeholder="Número do Plano" required>
                <button type="submit" name="excluir">Excluir Plano</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Planos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultPlanos->num_rows > 0) {
                        while($row = $resultPlanos->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["Numero"] . "</td>
                                    <td>" . $row["Descricao"] . "</td>
                                    <td>R$ " . number_format($row["Valor"], 2, ',', '.') . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhum plano encontrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
