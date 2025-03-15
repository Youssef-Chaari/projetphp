<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

include '../includes/header-admin.php';

// Fonction pour nettoyer le nom du fichier
function nettoyerNomFichier($nomFichier) {
    $nomFichier = str_replace(' ', '_', $nomFichier);
    $nomFichier = preg_replace('/[^A-Za-z0-9_.-]/', '', $nomFichier);
    return $nomFichier;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];
    $equipe = $_POST['equipe'];
    $taille = $_POST['taille'];
    $saison = $_POST['saison'];
    $ligue = $_POST['ligue'];
    $couleur = $_POST['couleur'];
    $type_maillot = $_POST['type_maillot'];

    // Gestion de l'upload de l'image
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Crée le dossier s'il n'existe pas
        }

        // Nettoyer le nom du fichier
        $nomFichier = nettoyerNomFichier(basename($_FILES['image']['name']));
        $uploadFile = $uploadDir . $nomFichier;

        // Vérifier le type de fichier (optionnel)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            // Déplacer le fichier uploadé vers le dossier de destination
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = $uploadFile; // Chemin de l'image à enregistrer en base de données
            } else {
                die("Erreur lors de l'upload de l'image.");
            }
        } else {
            die("Type de fichier non autorisé. Seuls les fichiers JPEG, PNG et GIF sont acceptés.");
        }
    } else {
        die("Erreur lors de l'upload de l'image.");
    }

    // Insérer le produit dans la base de données
    $sql = "INSERT INTO produits (nom, description, prix, image, stock, equipe, taille, saison, ligue, couleur, type_maillot, date_ajout)
            VALUES (:nom, :description, :prix, :image, :stock, :equipe, :taille, :saison, :ligue, :couleur, :type_maillot, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'description' => $description,
        'prix' => $prix,
        'image' => $image,
        'stock' => $stock,
        'equipe' => $equipe,
        'taille' => $taille,
        'saison' => $saison,
        'ligue' => $ligue,
        'couleur' => $couleur,
        'type_maillot' => $type_maillot
    ]);

    // Rediriger vers la page des produits
    header('Location: produits.php');
    exit;
}
?>
<link rel="stylesheet" href="../assets/css/style.css">

<h1>Ajouter un Produit</h1>
<form method="POST" enctype="multipart/form-data">
    <!-- Nom du produit -->
    <label for="nom">Nom du produit</label>
    <input type="text" id="nom" name="nom" required>

    <!-- Description -->
    <label for="description">Description</label>
    <textarea id="description" name="description" required></textarea>

    <!-- Prix -->
    <label for="prix">Prix</label>
    <input type="number" id="prix" name="prix" step="0.01" required>

    <!-- Image (upload) -->
    <label for="image">Image</label>
    <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" required>

    <!-- Stock -->
    <label for="stock">Stock</label>
    <input type="number" id="stock" name="stock" required>

    <!-- Équipe -->
    <label for="equipe">Équipe</label>
    <input type="text" id="equipe" name="equipe" required>

    <!-- Taille -->
    <label for="taille">Taille</label>
    <input type="text" id="taille" name="taille" required>

    <!-- Saison -->
    <label for="saison">Saison</label>
    <input type="text" id="saison" name="saison" required>

    <!-- Ligue -->
    <label for="ligue">Ligue</label>
    <input type="text" id="ligue" name="ligue" required>

    <!-- Couleur -->
    <label for="couleur">Couleur</label>
    <input type="text" id="couleur" name="couleur">

    <!-- Type de maillot -->
    <label for="type_maillot">Type de maillot</label>
    <input type="text" id="type_maillot" name="type_maillot">

    <!-- Bouton de soumission -->
    <button type="submit" class="btn">Ajouter</button>
</form>

<?php
include '../includes/footer.php';
?>