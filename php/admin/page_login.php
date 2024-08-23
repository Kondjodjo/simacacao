<?php
session_start();
// Connexion à la base de données PostgreSQL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/connect.php';

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Requête SQL pour vérifier les informations de connexion
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe lors de la connexion
    if ($row && password_verify($password, $row['password'])) {
        // Connexion réussie, afficher le résultat
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $success_message = "Connexion réussie ! Bienvenue, " . $row["username"] . ".";
        header("Location: php/page_accueil.php");
        exit;
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html> 
<html> 
    <head> 
        <title>Connexion</title>
         <style type="text/css"> 
             form { 
                max-width: 400px; 
                margin: 0 auto; 
                padding: 20px; 
                background-color: #f2f2f2;
                border-radius: 5px; 
            }

            form div {
                margin-bottom: 15px;
            }

label {
display: block;
font-weight: bold;
margin-bottom: 5px;
}

input[type=text], input[type=password] {
width: 100%;
padding: 10px;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}

button[type=submit] {
background-color: #4CAF50;
color: white;
padding: 10px 20px;
border: none;
border-radius: 4px;
cursor: pointer;
width: 100%;
}

button[type=submit]:hover {
background-color: #45a049;
}
</style>
</head> 
<body> 
    <div style="width: 50%;margin: 0 auto;">
        <header style="width:100%;"> 
            <img src="images/ENTETE_LOGIN.png"width="100%">
         </header> 
         <div style="text-align: center;font-size: 30px;color: blue;"> 
         </div> 
         <div style="width:50%;margin: 0 auto;text-align: center;color: blue;"> 
            <h1>Veuillez vous identifier</h1>

    <?php 
    if (isset($error_message)) { 
    ?>
    <p style="color: red;">
        <?php echo $error_message; 
        ?>
    </p>
    <?php 
    } elseif (isset($success_message)) {
    ?>
    <p style="color: green;">
        <?php echo $success_message; 
    ?>
    </p>
    <?php 
    } ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div>
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
</div>
<div>
    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
</div>
<button type="submit" name="submit">Se connecter</button>

</form> 
</div>
</div>
</body> </html>