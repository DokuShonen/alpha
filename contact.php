<?php require_once 'includes/connexion_db.php';
require_once 'includes/header.php'; ?>

<section class="contact-section">
    <h2>Contactez-nous</h2>
    <p>Une question, une suggestion ou une proposition de projet ? N'hésitez pas à nous laisser un message.</p>

    <form id="contact-form" method="post" action="actions/contact_traitement.php">
        <label for="nom">Votre Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Votre Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="sujet">Sujet :</label>
        <input type="text" id="sujet" name="sujet" required>

        <label for="message">Votre Message :</label>
        <textarea id="message" name="message" rows="6" required></textarea>

        <button type="submit">Envoyer le Message</button>
    </form>
</section>

<?php require_once 'includes/footer.php'; ?>