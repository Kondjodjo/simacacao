<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <style>
    /* Style du formulaire */
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      padding: 20px;
    }

    .registration-form {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      margin: 0 auto;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    input[type=text],input[type=email],input[type=password] {
      width: 100%;
      padding: 12px 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 14px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="registration-form">
    <h2>Inscription</h2>
    <?php
// Connexion à la base de données PostgreSQL
require_once '../connect.php';

// Traitement du formulaire d'ajout d'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        echo "Utilisateur ajouté avec succès !";
    } else {
        echo "Erreur lors de l'ajout de l'utilisateur.";
    }
}
?>


    <h1>Inscription</h1>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
      <div class="form-group">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="username">Email :</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">S'inscrire</button>
    </form>
  </div>
</body>
</html>