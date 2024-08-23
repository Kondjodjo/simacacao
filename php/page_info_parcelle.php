<?php 
session_start();
// Connexion à la base de données
require_once 'connect.php';
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

        .result-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .header, .item {
            display: flex;
            justify-content: space-between;
        }

        .header {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .item:nth-child(even) {
            background-color: #f1f1f1;
        }

        .item a {
            color: #4CAF50;
            text-decoration: none;
        }

        .item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recherche d'agriculteurs</h1>
        <div class="result-box">
            <div class="header">
                <div class="noms">NOM ET PRENOM</div>
                <div class="code_parcelle">CODE PARCELLE</div>
                <div class="superficie">SUPERFICIE</div>
                <div class="carte">AFFICHER</div>
                <div class="geojson">GEOJSON</div>
                <div class="KMZ">KMZ</div>
            </div>
            <?php
            $user_id = $_SESSION['user_id'];
            $username = $_SESSION['username'];
            $id_producteur = isset($_GET['id']) ? $_GET['id'] : '';
            $noms_et_prenoms = isset($_GET['noms_et_prenoms']) ? $_GET['noms_et_prenoms'] : '';

            if (!$id_producteur && !$noms_et_prenoms) {
                die("Aucun ID et NOM du producteur fourni.");
            }

            if ($noms_et_prenoms) {
                $sql = "SELECT 
                            p.NOMS_ET_PRENOMS,
                            p.ID_PRODUCTEUR, 
                            pc.CODE_PARCELLE, 
                            pc.SUPERFICIE_PARCELLE,
                            pc.URL_GEOJSON_PARCELLE,
                            pc.URL_KMZ_PARCELLE
                        FROM 
                            PRODUCTEUR p
                        JOIN 
                            AFFECTATION_PARCELLES ap ON p.ID_PRODUCTEUR = ap.ID_PRODUCTEUR
                        JOIN 
                            PARCELLES_CACAOYERES pc ON ap.CODE_PARCELLE = pc.CODE_PARCELLE
                        WHERE 
                            p.NOMS_ET_PRENOMS = :noms_et_prenoms";

                try {
                    $stmt = $connexion->prepare($sql);
                    $stmt->bindParam(':noms_et_prenoms', $noms_et_prenoms, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='item'>";
                        echo "<div class='noms'>" . htmlspecialchars($row['noms_et_prenoms']) . "</div>";
                        echo "<div class='code_parcelle'>" . htmlspecialchars($row["code_parcelle"]) . "</div>";
                        echo "<div class='superficie'><a href='traitement_formulaire.php?id=" . htmlspecialchars($row["code_parcelle"]) . "'>" . htmlspecialchars($row["superficie_parcelle"]) . " m²</a></div>";
                        echo "<div class='carte'><a href='traitement_formulaire_recherche.php?id_producteur=" . urlencode($row['id_producteur']) . "&code_parcelle=" . htmlspecialchars($row['code_parcelle']) . "'>AFFICHER</a></div>";
                        echo "<div class='geojson'><a href='affichage_geojson.php?code_parcelle=" . htmlspecialchars($row['code_parcelle']) . "' target='_blank'>GEOJSON</a></div>";
                        echo "<div class='KMZ'><a href='affiche_kmz.php?code_parcelle=" . htmlspecialchars($row['code_parcelle']) . "' target='_blank'>KMZ</a></div>";
                        echo "</div>";
                    }
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }

            if ($id_producteur) {
                $sql = "SELECT 
                            p.NOMS_ET_PRENOMS,
                            p.ID_PRODUCTEUR, 
                            pc.CODE_PARCELLE, 
                            pc.SUPERFICIE_PARCELLE,
                            pc.URL_GEOJSON_PARCELLE,
                            pc.URL_KMZ_PARCELLE
                        FROM 
                            PRODUCTEUR p
                        JOIN 
                            AFFECTATION_PARCELLES ap ON p.ID_PRODUCTEUR = ap.ID_PRODUCTEUR
                        JOIN 
                            PARCELLES_CACAOYERES pc ON ap.CODE_PARCELLE = pc.CODE_PARCELLE
                        WHERE 
                            p.ID_PRODUCTEUR = :id_producteur";
                
                try {
                    $stmt = $connexion->prepare($sql);
                    $stmt->bindParam(':id_producteur', $id_producteur, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='item'>";
                        echo "<div class='noms'>" . htmlspecialchars($row['noms_et_prenoms']) . "</div>";
                        echo "<div class='code_parcelle'>" . htmlspecialchars($row["code_parcelle"]) . "</div>";
                        echo "<div class='superficie'><a href='traitement_formulaire.php?id=" . htmlspecialchars($row["code_parcelle"]) . "'>" . htmlspecialchars($row["superficie_parcelle"]) . " m²</a></div>";
                        echo "<div class='carte'><a href='traitement_formulaire_recherche.php?id_producteur=" . urlencode($row['id_producteur']) . "&code_parcelle=" . htmlspecialchars($row['code_parcelle']) . "'>AFFICHER</a></div>";
                        echo "<div class='geojson'><a href='affichage_geojson.php?code_parcelle=" . htmlspecialchars($row['code_parcelle']) . "' target='_blank'>GEOJSON</a></div>";
                        echo "<div class='KMZ'><a href='affiche_kmz.php?code_parcelle=" . htmlspecialchars($row['code_parcelle']) . "' target='_blank'>KMZ</a></div>";
                        echo "</div>";
                    }
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
            ?>
        </div>
    </div>
</body>
</html>