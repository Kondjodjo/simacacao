<?php
// Connexion à la base de données MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simacacao";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lecture du fichier Excel
require_once 'PHPExcel/Classes/PHPExcel.php';
$inputFileType = 'Excel2007';
$inputFileName = 'data/data.xlsx';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);
$sheet = $objPHPExcel->getActiveSheet();

// Insertion des données dans MySQL
$sql = "INSERT INTO agriculteurs (photo_agriculteur,
nom_prenom,sexe,
date_naissance,
lieu_naissance,
lieu_résidence,
niveau_etude,
numero_id,
date_expiration,
photo_id,
statut_matrimonial,
numero_telephone,
adresse_email,
region,
departement,
arrondissement,
nombre_parcelle,
village_parcelle) VALUES (?, ?, ?,?, ?, ?,?, ?, ?,?, ?, ?,?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
foreach ($sheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    $values = [];
    foreach ($cellIterator as $cell) {
        $values[] = $cell->getValue();
    }
    $stmt->execute($values);
}

echo "Données importées avec succès !";
?>