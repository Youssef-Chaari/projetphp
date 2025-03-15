<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

if (!isset($_GET['id'])) {
    header('Location: produits.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM produits WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$produit = $stmt->fetch();

if (!$produit) {
    header('Location: produits.php');
    exit;
}

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

    // Gestion de l'upload de l'image (si une nouvelle image est fournie)
    $image = $produit['image']; // Conserver l'ancienne image par défaut
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
                $image = $uploadFile; // Chemin de la nouvelle image
            } else {
                die("Erreur lors de l'upload de l'image.");
            }
        } else {
            die("Type de fichier non autorisé. Seuls les fichiers JPEG, PNG et GIF sont acceptés.");
        }
    }

    // Mettre à jour le produit dans la base de données
    $sql = "UPDATE produits 
            SET nom = :nom, description = :description, prix = :prix, image = :image, stock = :stock, 
                equipe = :equipe, taille = :taille, saison = :saison, ligue = :ligue, couleur = :couleur, 
                type_maillot = :type_maillot 
            WHERE id = :id";
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
        'type_maillot' => $type_maillot,
        'id' => $id
    ]);

    // Rediriger vers la page des produits
    header('Location: produits.php');
    exit;
}

include '../includes/header-admin.php';
?>
<link rel="stylesheet"href="C:/xampp/htdocs/projetweb/assets/css/style.css">

<h1>Modifier le Produit</h1>
<form method="POST" enctype="multipart/form-data">
    <!-- Nom du produit -->
    <label for="nom">Nom du produit</label>
    <input type="text" id="nom" name="nom" value="<?= $produit['nom'] ?>" required>

    <!-- Description -->
    <label for="description">Description</label>
    <textarea id="description" name="description" required><?= $produit['description'] ?></textarea>

    <!-- Prix -->
    <label for="prix">Prix</label>
    <input type="number" id="prix" name="prix" step="0.01" value="<?= $produit['prix'] ?>" required>

    <!-- Image (upload) -->
    <label for="image">Image</label>
    <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif">
    <small>Laisser vide pour conserver l'image actuelle.</small>
    <?php if ($produit['image']) : ?>
        <div>
            <img src="<?= $produit['image'] ?>" alt="Image actuelle" style="max-width: 200px; margin-top: var(--space-sm);">
        </div>
    <?php endif; ?>

    <!-- Stock -->
    <label for="stock">Stock</label>
    <input type="number" id="stock" name="stock" value="<?= $produit['stock'] ?>" required>

    <!-- Équipe -->
    <label for="equipe">Équipe</label>
    <input type="text" id="equipe" name="equipe" value="<?= $produit['equipe'] ?>" required>

    <!-- Taille -->
    <label for="taille">Taille</label>
    <input type="text" id="taille" name="taille" value="<?= $produit['taille'] ?>" required>

    <!-- Saison -->
    <label for="saison">Saison</label>
    <input type="text" id="saison" name="saison" value="<?= $produit['saison'] ?>" required>

    <!-- Ligue -->
    <label for="ligue">Ligue</label>
    <input type="text" id="ligue" name="ligue" value="<?= $produit['ligue'] ?>" required>

    <!-- Couleur -->
    <label for="couleur">Couleur</label>
    <input type="text" id="couleur" name="couleur" value="<?= $produit['couleur'] ?>">

    <!-- Type de maillot -->
    <label for="type_maillot">Type de maillot</label>
    <input type="text" id="type_maillot" name="type_maillot" value="<?= $produit['type_maillot'] ?>">

    <!-- Bouton de soumission -->
    <button type="submit" class="btn">Enregistrer</button>
</form>

<?php
include '../includes/footer.php';
?>