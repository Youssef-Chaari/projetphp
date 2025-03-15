<?php
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();
include 'includes/header.php';
?>

<main>
    <h1>Contactez-nous</h1>
    <link rel="stylesheet" href="assets/css/style.css">

    <div class="contact-container">
        <div class="contact-form">
            <form action="send_email.php" method="POST">
                <div class="form-group">
                    <label for="name">Nom :</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet :</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message :</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn">Envoyer le message</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h3>Coordonnées</h3>
            <p><strong>Adresse :</strong><br>123 Rue du Football, 75000 Paris</p>
            <p><strong>Téléphone :</strong><br>01 23 45 67 89</p>
            <p><strong>Email :</strong><br>contact@maillotsfoot.com</p>
            
            <div class="social-links">
                <a href="#" class="btn">Facebook</a>
                <a href="#" class="btn">Twitter</a>
                <a href="#" class="btn">Instagram</a>
            </div>
        </div>
    </div>
</main>

<?php
include 'includes/footer.php';
?>