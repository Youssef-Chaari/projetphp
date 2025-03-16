<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();
include 'includes/header.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (isset($_GET['action']) && $_GET['action'] === 'ajouter' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $quantite = isset($_GET['quantite']) ? intval($_GET['quantite']) : 1;

    $sql = "SELECT stock FROM produits WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $produit = $stmt->fetch();

    if (!$produit) {
        $_SESSION['erreur'] = "Produit non trouvé.";
        header('Location: produits.php');
        exit();
    }

    if ($quantite > $produit['stock']) {
        $_SESSION['erreur'] = "La quantité demandée pour ce produit dépasse le stock disponible.";
        header('Location: produits.php');
        exit();
    }

    $index = array_search($id, array_column($_SESSION['panier'], 'id'));
    if ($index !== false) {
        $nouvelle_quantite = $_SESSION['panier'][$index]['quantite'] + $quantite;
        if ($nouvelle_quantite > $produit['stock']) {
            $_SESSION['erreur'] = "La quantité totale demandée pour ce produit dépasse le stock disponible.";
            header('Location: produits.php');
            exit();
        }
        $_SESSION['panier'][$index]['quantite'] += $quantite;
    } else {
        $_SESSION['panier'][] = ['id' => $id, 'quantite' => $quantite];
    }

    header('Location: panier.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $_SESSION['panier'] = array_filter($_SESSION['panier'], function($item) use ($id) {
        return $item['id'] !== $id;
    });

    header('Location: panier.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantite'])) {
    foreach ($_POST['quantite'] as $id => $quantite) {
        $id = intval($id);
        $quantite = intval($quantite);

        $sql = "SELECT stock FROM produits WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $produit = $stmt->fetch();

        if (!$produit) {
            $_SESSION['erreur'] = "Produit non trouvé.";
            header('Location: panier.php');
            exit();
        }

        if ($quantite > $produit['stock']) {
            $_SESSION['erreur'] = "La quantité demandée pour le produit ID $id dépasse le stock disponible.";
            header('Location: panier.php');
            exit();
        }

        $index = array_search($id, array_column($_SESSION['panier'], 'id'));
        if ($index !== false) {
            if ($quantite > 0) {
                $_SESSION['panier'][$index]['quantite'] = $quantite;
            } else {
                unset($_SESSION['panier'][$index]);
            }
        }
    }

    $_SESSION['panier'] = array_values($_SESSION['panier']);
    header('Location: panier.php');
    exit();
}

$panier = [];
$total = 0;

if (!empty($_SESSION['panier'])) {
    $ids = array_column($_SESSION['panier'], 'id');
    $ids = implode(',', $ids);
    $sql = "SELECT * FROM produits WHERE id IN ($ids)";
    $stmt = $pdo->query($sql);
    $produits = $stmt->fetchAll();

    foreach ($_SESSION['panier'] as $item) {
        foreach ($produits as $produit) {
            if ($produit['id'] === $item['id']) {
                $panier[] = [
                    'id' => $produit['id'],
                    'nom' => $produit['nom'],
                    'prix' => $produit['prix'],
                    'image' => $produit['image'],
                    'quantite' => $item['quantite'],
                    'stock' => $produit['stock'] 
                ];
                $total += $produit['prix'] * $item['quantite'];
                break;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adresse_livraison'])) {
    $adresse_livraison = htmlspecialchars($_POST['adresse_livraison']);

    foreach ($_SESSION['panier'] as $item) {
        $id_produit = $item['id'];
        $quantite = $item['quantite'];

        $sql = "SELECT stock FROM produits WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id_produit]);
        $produit = $stmt->fetch();

        if (!$produit) {
            $_SESSION['erreur'] = "Produit ID $id_produit non trouvé.";
            header('Location: panier.php');
            exit();
        }

        if ($quantite > $produit['stock']) {
            $_SESSION['erreur'] = "La quantité demandée pour le produit ID $id_produit dépasse le stock disponible.";
            header('Location: panier.php');
            exit();
        }
    }

    $sql = "INSERT INTO commandes (id_utilisateur, date_commande, statut, total, adresse_livraison) 
            VALUES (:id_utilisateur, NOW(), 'en cours de traitement', :total, :adresse_livraison)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_utilisateur' => $_SESSION['utilisateur_id'],
        'total' => $total,
        'adresse_livraison' => $adresse_livraison
    ]);

    $commande_id = $pdo->lastInsertId();

    foreach ($_SESSION['panier'] as $item) {
        $id_produit = $item['id'];
        $quantite = $item['quantite'];

        $sql = "INSERT INTO details_commande (id_commande, id_produit, quantite) 
                VALUES (:id_commande, :id_produit, :quantite)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_commande' => $commande_id,
            'id_produit' => $id_produit,
            'quantite' => $quantite
        ]);

        $sql = "UPDATE produits SET stock = stock - :quantite WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'quantite' => $quantite,
            'id' => $id_produit
        ]);
    }

    $_SESSION['panier'] = [];

    header('Location: commande.php');
    exit();
}
?>

<main>
    <link rel="stylesheet" href="assets/css/style.css">

    <h1>Panier</h1>
    <?php if (isset($_SESSION['erreur'])) : ?>
        <div class="alert alert-danger">
            <?= $_SESSION['erreur'] ?>
        </div>
        <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>

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
                                <p>Stock disponible : <?= $item['stock'] ?></p>
                                <label for="quantite_<?= $item['id'] ?>">Quantité :</label>
                                <input type="number" name="quantite[<?= $item['id'] ?>]" value="<?= $item['quantite'] ?>" min="1" max="<?= $item['stock'] ?>">
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