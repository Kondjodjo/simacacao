<?php
// Connexion à la base de données
session_start();
require_once 'connect.php';

// Récupérer les informations à partir du nom/prénom
$nom_id1 = $_GET['id1'];
$nom_id2 = $_GET['id2'];
/*
$sql = "SELECT * FROM agriculteurs WHERE id = '$nom_id1'";
$result = $conn->query($sql);*/
$sql = "SELECT * FROM producteur WHERE id_producteur";
$stmt = $connexion->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) > 0) {
    // Vérifier si le champ date_naissance est le même
    $same_birthdate = true;
    $first_row = $results->fetch_assoc();
    $reference_birthdate = $first_row['date_naissance'];

    /*while ($row = $result->fetch_assoc()) {
        if ($row['date_naissance'] != $reference_birthdate) {
            $same_birthdate = false;
            break;
        }
    }*/
    foreach ($results as $row) {
        if ($row['date_naissance'] != $reference_birthdate) {
            $same_birthdate = false;
            break;
        }
    }
}
?>
<?php 
/*
$sql_parcelle = "SELECT * FROM parcelle WHERE id ='$nom_id2' ";
$result_parcelle = $conn->query($sql_parcelle);*/
$sql = "SELECT * FROM parcelle WHERE id2";
$stmt = $connexion->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*if ($result_parcelle->num_rows > 0) {
    // Vérifier si le champ date_naissance est le même
    $first_row_parcelle = $result_parcelle->fetch_assoc();
}*/
foreach ($results as $row_parcelle) {
        if ($row['date_naissance'] != $reference_birthdate) {
            $same_birthdate = false;
            break;
        }
    }
 ?>

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
                <?php if ($same_birthdate) {
                    echo "<h2 style='text-align:;color:red;font-size:20px'>IDENTIFIANT DE L'EXPLOITANT</h2>";
                    echo "Sexe : " . "<strong>" . $row['sexe'] . "</strong>" . " " . "<br><br>";
                ?>
                <div style="display: flex;flex-wrap: wrap;width: 100%;">
                    <div style="width: 60%;">
                        <?php echo "Né(e) le : " . "<strong>" . $first_row['date_naissance'] . "</strong>" . "</strong>"; ?>
                    </div>
                    <div style="width: 5%;text-align: ;">
                        <?php echo "<strong>" . "à" . "</strong>"; ?>
                    </div>
                    <div style="width: 34%;text-align: center;">
                        <?php echo "<strong>" . $first_row['lieu_naissance'] . "</strong>"; ?>
                    </div>
                </div>

                <?php echo "<br><br>";
                    echo "Résidence : " . "<strong>" . $first_row['lieu_résidence'] . "</strong>" . "<br><br>";
                    echo "Coopérative : " . "<strong>" . $first_row_parcelle['cooperative'] . "</strong>" . "<br>";
                } ?>
            </div>
            <div style="width:30%;height:auto;border: ;display: flex;flex-wrap: wrap;">
                <div style="width:100%;height:auto;">
                    <?php echo "<img src='../images/profil/" . $first_row['photo_agriculteur'] . ".jpg' style='width:200px;background-color:pink;position:right;margin-top:10px;'><br>"; ?>
                </div>
                <div style="width:50%;height:20px;color:red;text-align:center;font-size: 18px;">
                    <p>MA.266.31</p>
                </div>
            </div>
        </div>
        <div style="width:100%;margin:0 auto;display:flex;flex-wrap:wrap;">
            <?php echo "<h2 style='text-align:;color:red;font-size:20px'>LOCALISATION DE LA PARCELLE</h2>"; ?>
            <div style="width:100%;display:flex;flex-wrap:wrap;font-size: 20px; margin-bottom: 10px;">
                <div style="width:30%;text-align: ;">
                    <?php echo "Région : " . "<strong>" . $first_row_parcelle['region'] . "</strong>" . "<br>"; ?>
                </div>
                <div style="width:30%;text-align: center;">
                    <?php echo "Département : " . "<strong>" . $first_row_parcelle['departement'] . "</strong>" . "<br>"; ?>
                </div>
                <div style="width:30%;text-align:center ;">
                    <?php echo "Arrondissement : " . "<strong>" . $first_row_parcelle['arrondissement'] . "</strong>" . "<br>"; ?>
                </div>
            </div>
            <div style="width:100%;display:flex;flex-wrap:wrap;margin-bottom: 4px;">
                <div style="width:40%;font-size:20px;"><br>
                    <?php
                    echo "Village : " . "<strong>" . $first_row_parcelle['village'] . "</strong>" . "<br><br>";
                    echo "Lieu-dit : " . "<strong>" . $first_row_parcelle['lieu_dit'] . "</strong>" . "<br><br>";
                    echo "Statut juridique : " . "<strong>" . $first_row_parcelle['statut_juridique'] . "</strong>" . "<br><br>";
                    echo "Superficie : " . "<strong>" . $first_row_parcelle['superficie'] . "</strong>" . "m²" . "<br>";
                    ?>
                </div>
                <div style="width:10%;">
                </div>
                <div style="width:30%;margin-left:100px">
                    <?php
                    // Génération du code QR
                    $qr_data = array(
                        "NOM : " . $first_row['nom_prenom'],
                        "SEXE : " . $first_row['sexe'],
                        "DATE DE NAISSANCE : " . $first_row['date_naissance'],
                        "CONTACT : " . $first_row['numero_telephone'],
                        "N° CNI : " . $first_row['numero_id'],
                        "URL : " . $first_row['adresse_email']
                    );

                    echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode(implode(',', $qr_data)) . "&size=200x200' alt='Code QR'>";
                    ?>
                </div>
            </div>
            <div style="width:100%;display:flex;flex-wrap:wrap;color:red;">
                <p style="margin: 0;"><?php echo"N° :".$first_row_parcelle['code_parcelle'] ?></p>
                <?php echo "<img id='plan' src='../images/" . $first_row_parcelle['carte'] . ".png' style='width:100%;height:400;background-color:pink;position:right'><br>"; ?>
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
</body>
</html>
<?php
$conn->close();
?>