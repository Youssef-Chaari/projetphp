<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();
include 'includes/header.php';

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un produit au panier
if (isset($_GET['action']) && $_GET['action'] === 'ajouter' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $quantite = isset($_GET['quantite']) ? intval($_GET['quantite']) : 1;

    // Vérifier si le produit est déjà dans le panier
    $index = array_search($id, array_column($_SESSION['panier'], 'id'));
    if ($index !== false) {
        // Mettre à jour la quantité
        $_SESSION['panier'][$index]['quantite'] += $quantite;
    } else {
        // Ajouter le produit au panier
        $_SESSION['panier'][] = ['id' => $id, 'quantite' => $quantite];
    }

    header('Location: panier.php');
    exit();
}

// Supprimer un produit du panier
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $_SESSION['panier'] = array_filter($_SESSION['panier'], function($item) use ($id) {
        return $item['id'] !== $id;
    });

    header('Location: panier.php');
    exit();
}

// Mettre à jour les quantités
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantite'])) {
    foreach ($_POST['quantite'] as $id => $quantite) {
        $id = intval($id);
        $quantite = intval($quantite);

        // Trouver l'index du produit dans le panier
        $index = array_search($id, array_column($_SESSION['panier'], 'id'));
        if ($index !== false) {
            if ($quantite > 0) {
                // Mettre à jour la quantité
                $_SESSION['panier'][$index]['quantite'] = $quantite;
            } else {
                // Supprimer le produit si la quantité est 0
                unset($_SESSION['panier'][$index]);
            }
        }
    }

    // Réindexer le tableau après suppression
    $_SESSION['panier'] = array_values($_SESSION['panier']);
    header('Location: panier.php');
    exit();
}

// Récupérer les produits du panier
$panier = [];
$total = 0;

if (!empty($_SESSION['panier'])) {
    $ids = array_column($_SESSION['panier'], 'id');
    $ids = implode(',', $ids);
    $sql = "SELECT * FROM produits WHERE id IN ($ids)";
    $stmt = $pdo->query($sql);
    $produits = $stmt->fetchAll();

    // Associer les produits avec leurs quantités
    foreach ($_SESSION['panier'] as $item) {
        foreach ($produits as $produit) {
            if ($produit['id'] === $item['id']) {
                $panier[] = [
                    'id' => $produit['id'],
                    'nom' => $produit['nom'],
                    'prix' => $produit['prix'],
                    'image' => $produit['image'],
                    'quantite' => $item['quantite']
                ];
                $total += $produit['prix'] * $item['quantite'];
                break;
            }
        }
    }
}

// Finaliser l'achat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adresse_livraison'])) {
    $adresse_livraison = htmlspecialchars($_POST['adresse_livraison']);

    // Enregistrer la commande
    $sql = "INSERT INTO commandes (id_utilisateur, date_commande, statut, total, adresse_livraison) VALUES (:id_utilisateur, NOW(), 'En attente', :total, :adresse_livraison)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_utilisateur' => $_SESSION['utilisateur_id'],
        'total' => $total,
        'adresse_livraison' => $adresse_livraison
    ]);

    // Vider le panier
    $_SESSION['panier'] = [];

    header('Location: commande.php');
    exit();
}
?>

<main>
    <link rel="stylesheet" href="assets/css/style.css">

    <h1>Panier</h1>
    <?php if (empty($panier)) : ?>
        <p>Votre panier est vide.</p>
    <?php else : ?>
        <div class="panier">
            <form method="POST" action="">
                <ul>
                    <?php foreach ($panier as $item) : ?>
                        <li class="produit-panier">
                            <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nom']) ?>" class="produit-image">
                            <div>
                                <h2><?= $item['nom'] ?></h2>
                                <p><?= $item['prix'] ?> DT</p>
                                <label for="quantite_<?= $item['id'] ?>">Quantité :</label>
                                <input type="number" name="quantite[<?= $item['id'] ?>]" value="<?= $item['quantite'] ?>" min="1">
                                <a href="panier.php?action=supprimer&id=<?= $item['id'] ?>" class="btn-supprimer">Supprimer</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="total">
                    <p>Total : <?= $total ?> DT</p>
                </div>
                <button type="submit" name="mettre_a_jour" class="btn">Mettre à jour le panier</button>
            </form>

            <form method="POST" action="">
                <label for="adresse_livraison">Adresse de livraison :</label>
                <textarea name="adresse_livraison" required></textarea>
                <button type="submit" name="finaliser_achat" class="btn">Finaliser l'achat</button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php
include 'includes/footer.php';
?>