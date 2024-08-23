<?php
// Paramètres de connexion
$host = "postgresql://simacacao_user:sYoGvLMcCWo6HOdU9wW650MPsKwdMBUx@dpg-cr4a0qbtq21c73e1ags0-a.oregon-postgres.render.com/simacacao";
$dbname = "simacacao";
$user = "simacacao_user";
$password = "sYoGvLMcCWo6HOdU9wW650MPsKwdMBUx";

try {
    // Chaîne de connexion
    $source_bd = "pgsql:host=$host;dbname=$dbname";

    // Connexion à la base de données
    $connexion = new PDO($source_bd, $user, $password);

    // Vérifier la connexion
    if ($connexion) {
    }
}catch (PDOException $e) {
    // Gestion des erreurs
    echo "Erreur de connexion : " . $e->getMessage();
}

?>
