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

function nettoyerNomFichier($nomFichier) {
    $nomFichier = str_replace(' ', '_', $nomFichier);
    $nomFichier = preg_replace('/[^A-Za-z0-9_.-]/', '', $nomFichier);
    return $nomFichier;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // upload image
    $image = $produit['image']; // Conserver l'ancienne image par défaut
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';

        $nomFichier = nettoyerNomFichier(basename($_FILES['image']['name']));
        $uploadFile = $uploadDir . $nomFichier;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = $uploadFile;
            } else {
                die("Erreur lors de movement de l'image.");
            }
        } else {
            die("Erreur lors de l'upload de l'image.");
        }

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

    header('Location: produits.php');
    exit;
}

include '../includes/header-admin.php';
?>
<link rel="stylesheet"href="C:/xampp/htdocs/projetweb/assets/css/style.css">

<h1>Modifier le Produit</h1>
<form method="POST" enctype="multipart/form-data">
    <label for="nom">Nom du produit</label>
    <input type="text" id="nom" name="nom" value="<?= $produit['nom'] ?>" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" required><?= $produit['description'] ?></textarea>

    <label for="prix">Prix</label>
    <input type="number" id="prix" name="prix" step="0.01" value="<?= $produit['prix'] ?>" required>

    <label for="image">Image</label>
    <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif">
    <small>Laisser vide pour conserver l'image actuelle.</small>
    <?php if ($produit['image']) : ?>
        <div>
            <img src="<?= $produit['image'] ?>" alt="Image actuelle" style="max-width: 200px; margin-top: var(--space-sm);">
        </div>
    <?php endif; ?>

    <label for="stock">Stock</label>
    <input type="number" id="stock" name="stock" value="<?= $produit['stock'] ?>" required>

    <label for="equipe">Équipe</label>
    <input type="text" id="equipe" name="equipe" value="<?= $produit['equipe'] ?>" required>

    <label for="taille">Taille</label>
    <input type="text" id="taille" name="taille" value="<?= $produit['taille'] ?>" required>

    <label for="saison">Saison</label>
    <input type="text" id="saison" name="saison" value="<?= $produit['saison'] ?>" required>

    <label for="ligue">Ligue</label>
    <input type="text" id="ligue" name="ligue" value="<?= $produit['ligue'] ?>" required>

    <label for="couleur">Couleur</label>
    <input type="text" id="couleur" name="couleur" value="<?= $produit['couleur'] ?>">

    <label for="type_maillot">Type de maillot</label>
    <input type="text" id="type_maillot" name="type_maillot" value="<?= $produit['type_maillot'] ?>">

    <button type="submit" class="btn">Enregistrer</button>
</form>

<?php
include '../includes/footer.php';
?>