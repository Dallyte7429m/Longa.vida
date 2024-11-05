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
$searchQuery = "";

$recordsPerPage = 10;

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$startFrom = ($page - 1) * $recordsPerPage;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $searchQuery = $_GET['search'];
}

$queryPlanos = "SELECT Numero, Descricao, Valor FROM Plano 
                WHERE Numero LIKE '%$searchQuery%' OR Descricao LIKE '%$searchQuery%' 
                LIMIT $startFrom, $recordsPerPage";

$resultPlanos = $conn->query($queryPlanos);

$totalRecordsQuery = "SELECT COUNT(*) FROM Plano WHERE Numero LIKE '%$searchQuery%' OR Descricao LIKE '%$searchQuery%'";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_array()[0];
$totalPages = ceil($totalRecords / $recordsPerPage);

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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7e4e0; 
            margin: 0;
            padding: 0;
            color: #6a1b3d; 
        }
        h1 {
            text-align: center;
            color: #9e2a2f; 
            font-size: 2.8rem;
            margin-top: 30px;
            margin-bottom: 40px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .form-container, .message {
            background-color: #fff;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .form-container h2 {
            text-align: center;
            color: #9e2a2f; 
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        .form-container input, .form-container button {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: 1px solid #9e2a2f; 
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-container button {
            background-color: #9e2a2f; 
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #7a1f26; 
        }
        .table-container {
            margin-top: 40px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #f1e6e6; 
        }
        th {
            background-color: #f7e4e0;
            color: #9e2a2f; 
            font-size: 1rem;
        }
        td {
            color: #6a1b3d;
            font-size: 1rem;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .pagination a {
            text-decoration: none;
            padding: 10px 18px;
            margin: 0 5px;
            background-color: #9e2a2f; 
            color: white;
            border-radius: 6px;
            font-weight: bold;
        }
        .pagination a:hover {
            background-color: #7a1f26; 
        }
        .message.success {
            color: #d32f2f; 
            font-weight: bold;
        }
        .message.error {
            color: #c62828; 
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Gerenciamento de Planos Longa Vida</h1>

        <
        <div class="form-container">
            <h2>Pesquisar Planos</h2>
            <form method="GET">
                <input type="text" name="search" value="<?php echo $searchQuery; ?>" placeholder="Buscar por Número ou Descrição do Plano">
                <button type="submit">Pesquisar</button>
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

    
        <div class="pagination">
            <?php
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='?page=$i&search=$searchQuery'>$i</a>";
            }
            ?>
        </div>
    </div>

</body>
</html>
