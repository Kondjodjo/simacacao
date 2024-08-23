<?php
// Connexion à la base de données
session_start();
require_once 'connect.php';

$nom = $_GET['id_producteur'];
$code = $_GET['code_parcelle'];
// Récupérer les informations à partir du nom/prénom
try {
    // Requête pour obtenir les informations du producteur    
    $sql = "SELECT * FROM producteur WHERE id_producteur = :nom";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();


    $sql1 = "SELECT * FROM parcelles_cacaoyeres WHERE code_parcelle = :code";
    $stmt1 = $connexion->prepare($sql1);
    $stmt1->bindParam(':code', $code);
    $stmt1->execute();

    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $resultats1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    $count = count($resultats);
    $count1 = count($resultats1);

    if ($count > 1) {
        // Vérifier si les dates de naissance sont identiques
        $dates_naissance = array_unique(array_column($resultats, 'date_naissance'));
        
        if (count($dates_naissance) == 1) {
            // Toutes les dates de naissance sont identiques
            echo "<h2>Plusieurs producteurs portent ce nom avec la même date de naissance :</h2>";
            echo "<p>Voici les parcelles associées :</p>";
            echo "<ul>";
            foreach ($resultats as $row) {
                // Récupérer les parcelles pour ce producteur
                $sql_parcelles = "SELECT * FROM parcelles WHERE id_producteur = :id";
                $stmt_parcelles = $connexion->prepare($sql_parcelles);
                $stmt_parcelles->bindParam(':id', $row['id']);
                $stmt_parcelles->execute();
                $parcelles = $stmt_parcelles->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<li>Producteur : " . $row['noms_et_prenoms'] . " (ID: " . $row['id'] . ")";
                if (count($parcelles) > 0) {
                    echo "<ul>";
                    foreach ($parcelles as $parcelle) {
                        echo "<li>Parcelle : " . $parcelle['nom_parcelle'] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo " - Aucune parcelle associée";
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            // Les dates de naissance sont différentes
            echo "<h2>Plusieurs producteurs portent ce nom :</h2>";
            echo "<ul>";
            foreach ($resultats as $row) {
                echo "<li><a href='fiche_producteur.php?id=" . $row["id"] . "'>" . $row["noms_et_prenoms"] . "</a> - Date de naissance : " . $row["date_naissance"] . "</li>";
            }
            echo "</ul>";
        }
    }
elseif ($count == 1) {
        // S'il y a un seul résultat, afficher les informations du producteur
        $row = $resultats[0];
        $row1= $resultats1[0];?> 

<!DOCTYPE html>
<html>
<head>
    <title id="titre"></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12pt;
            }
            #imprimer {
                display: none;
            }
            @page {
                size: auto;
                margin: 10mm 20mm 20mm 20mm;
            }
        }
    </style>
</head>
<body>
    <div style="width: 75%;height: 1250px;padding:15px;background-color: white; margin: 0 auto;">
        <header style="background-color: ;width: 100%;margin: 0 auto;">
            <img src="../images/entete.png" style="width:100%;height: 100px;margin: 0 auto;">
        </header>
        <div style="width:100%;margin: 0 auto;display:flex;flex-wrap: wrap;background-image:url('../images/cacao1.png');background-size: 500px;background-repeat: ;opacity: 0.8;">
            <div style="width:69%;height:auto;font-size:20px;padding: 2px;">
                <?php 
                    echo "<h2 style='text-align:;color:red;font-size:20px'>IDENTIFIANT DE L'EXPLOITANT</h2>";
                    echo "Sexe : " . "<strong>" . $row['sexe_producteur'] . "</strong>" . " " . "<br><br>";
                ?>
                <div style="display: flex;flex-wrap: wrap;width: 100%;">
                    <div style="width: 60%;">
                        <?php echo "Né(e) le : " . "<strong>" . $row['date_de_naissance'] . "</strong>" . "</strong>"; ?>
                    </div>
                    <div style="width: 5%;text-align: ;">
                        <?php echo "<strong>" . "à" . "</strong>"; ?>
                    </div>
                    <div style="width: 34%;text-align: center;">
                        <?php echo "<strong>" . $row['lieu_de_naissance'] . "</strong>"; ?>
                    </div>
                </div>

                <?php echo "<br>";
                    echo "Résidence : " . "<strong>" . $row['lieu_de_residence'] . "</strong>" . "<br>";
                    echo "<br>";
                    echo "Niveau Etude : " . "<strong>" . $row['niveau_etudes'] . "</strong>" . "<br>";
                    echo "<br>";
                    echo "Statut Matrimonial : " . "<strong>" . $row['statut_matrimonial'] . "</strong>" . "<br>";
                } ?>
            </div>
            <div style="width:30%;height:auto;border: ;display: flex;flex-wrap: wrap;">
                <div style="width:100%;height:auto;">
                    <?php echo "<img src='" . $row['url_photo_producteur'] . "'style='width:200px;background-color:pink;position:right;margin-top:10px;'><br>"; ?>
                </div>
                <div style="width:50%;height:20px;color:red;text-align:center;font-size: 18px;">
                    <p><?php echo "ID : " . "<strong>" . $row['id_producteur'] . "</strong>" . "<br><br>"; ?></p>
                </div>
            </div>
        </div>
        <div style="width:100%;margin:0 auto;display:flex;flex-wrap:wrap;">
            <?php   echo "<h2 style='text-align:;color:red;font-size:20px'>LOCALISATION DE LA PARCELLE</h2>";?>
            <div style="width:100%;display:flex;flex-wrap:wrap;font-size: 20px; margin-bottom: 10px;">
                <div style="width:100%;text-align: ;display:flex ;flex-wrap:wrap ;">
                    <div style="width:80%;">
                        <div style="width:100%;text-align: ;margin-bottom: 20px;">
                        <?php 
                         echo "CODE: " . "<strong>" . $row1['code_parcelle'] . "</strong>" . "<br>";
                     ?>
                      </div>
                      <div style="width:100%;text-align: ;margin-bottom: 20px;">
                        <?php 
                        echo "VILLAGE : " . "<strong>" . $row1['village_parcelle'] . "</strong>" . "<br>"; 
                     ?>
                      </div>
                      <div style="width:100%;text-align: ;">
                        <?php 
                        echo "SUPERFICIE : " . "<strong>" . $row1['superficie_parcelle'] . "</strong>" . "<br>";  
                     ?>
                      </div>
                        <?php 
                            
                        
                     ?>
                    </div>
                    <div style="width:20%;">
                        <?php
                    // Génération du code QR
                    $qr_data = array(
                        "NOM : " . $row['noms_et_prenoms'],
                        "DATE DE NAISSANCE : " . $row['date_de_naissance'],
                        "CONTACT : " . $row['numero_telephone'],
                        "N° CNI : " . $row['numero_cni'],
                        "EMAIL : " . $row['e_mail']
                    );

                    echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode(implode(',', $qr_data)) . "&size=200x200' alt='Code QR'>";
                    ?>
                    </div>
                </div>
                <div style="width:30%;text-align: center;">
                </div>
                <div style="width:30%;text-align:center ;">
                </div>
            </div>
            <div style="width:100%;display:flex;flex-wrap:wrap;margin-bottom: 4px;">
                <div style="width:40%;font-size:20px;"><br>
                </div>
                <div style="width:10%;">
                </div>
                <div style="width:30%;margin-left:100px">
                    
                </div>
            </div>
            <div style="width:100%;display:flex;flex-wrap:wrap;color:red;">
                <p style="margin: 0;"></p>
                <?php echo "<img id='plan' src='" . $row1['url_carte_parcelle'] . "' style='width:100%;height:400;background-color:pink;position:right'><br>"; ?>
            </div>
        </div>
        <div>
           <?php
            echo "<p id='imprimer'><a href='#' onclick='window.print();redirectToPage();return false;'>Imprimer</a></p>";

            echo "<script>
            function redirectToPage() {
                window.location.href = 'accueil.php';
            }
            </script>";
            ?>
        </div>
    </div>
    <?php        //header("Location: fiche_producteur.php?id=" . $row["id"]);
        exit;
        echo "Aucun producteur trouvé avec ce nom.";
} catch(PDOException $e) {
    echo "Erreur lors de la recherche dans la base de données : " . $e->getMessage();
    exit;
}

$conn = null; // Fermer la connexion à la base de données
?>
</body>
</html>  




