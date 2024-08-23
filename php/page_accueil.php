<?php
session_start();
 // Connexion à la base de données
require_once 'connect.php';

$sql = "SELECT 
            p.ID_PRODUCTEUR,
            p.NOMS_ET_PRENOMS, 
            p.DATE_DE_NAISSANCE, 
            COUNT(ap.CODE_PARCELLE) as nombre_parcelles
        FROM 
            PRODUCTEUR p
        LEFT JOIN 
            AFFECTATION_PARCELLES ap ON p.ID_PRODUCTEUR = ap.ID_PRODUCTEUR
        GROUP BY 
            p.ID_PRODUCTEUR, p.NOMS_ET_PRENOMS, p.DATE_DE_NAISSANCE
        ORDER BY 
            p.NOMS_ET_PRENOMS";

try {
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
?>  
    <!DOCTYPE html>
<html>
<head>
    <title>Recherche d'agriculteurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 40px;
        }

        .search-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .search-box form {
            display: flex;
            justify-content: center;
        }

        .search-box input[type=text] {
            width: 300px;
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-box button {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .result-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .result-box .header {
            display: flex;
            justify-content: space-between;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .result-box .item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .result-box .item:nth-child(even) {
            background-color: #f1f1f1;
        }
        .noms{
            width: 55%;
        }
        .naissance{
            width: 15%;
        }
        .nombre_parcelle{
            width: 13%;
        }
        .fiche_individuelle{
            width: 13%;
        }
        .plan_individuelle{
            width: 13%;
        }
        .nom{
            width: 50%;
        }
        .date_n{
            width: 15%;
            text-align: center;
            color: #4CAF50;
        }
        .nombre_p{
            width: 15%;
            text-align: center;
            border: solid px;
            color:#4CAF50 ;
        }
        .affiche{
            width: 13%;
            text-align: center;
        }
        .telecharger{
            width: 13%;
        }

        .result-box .item a {
            color: #4CAF50;
            text-decoration: none;
            text-align: center;
        }

        .result-box .item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recherche d'agriculteurs</h1>
        <div class="search-box">
            <form action="page_info_parcelle.php" method="get">
                <input type="text" id="noms_et_prenoms" name="noms_et_prenoms" placeholder="Nom et prénom" required>
                <button type="submit">Rechercher</button>
            </form>
        </div>
        <div class="result-box">
            <div class="header">
                <div class="noms">Noms et Prénoms</div>
                <div class="naissance">Date de Naissance</div>
                <div class="nombre_parcelle">Nombre de Parcelles</div>
                <div class="fiche_individuelle">Fiche Individuelle</div>
            </div>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='item'>
  <div class='nom'><a href='page_info_parcelle.php?id=" . urlencode($row['id_producteur']) . "'>" . 
    htmlspecialchars($row['noms_et_prenoms']) . "</a></div>
                <div class='date_n'>" . $row["date_de_naissance"] . "</div>
                <div class='date_n'>" . $row["nombre_parcelles"] . "</div>
                <div class='affiche'><a href='page_info_parcelle.php?id=" . $row["id_producteur"] . "'>AFFICHER</a></div>
            </div>";

        /*
        echo "<tr>
                <td>" . htmlspecialchars($row['NOMS_ET_PRENOMS']) . "</td>
                <td>" . $row['DATE_DE_NAISSANCE'] . "</td>
                <td>" . $row['nombre_parcelles'] . "</td>
              </tr>";*/
    }

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>