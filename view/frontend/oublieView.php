<?php $title = 'Mot de passe oublié'; ?>
<?php ob_start(); ?>



<div id="content">
    <form method="post">
        <fieldset>
            <legend>Mot de passe oublié</legend>
            <input type="hidden" name="action" value="accueil" />
            <label for="email">Courriel:</label>
            <span class="erreur"><?php if ($type_courriel == 'text') echo $valeur_courriel; ?></span>
            <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
            <input type="submit" id="submit_signup" name="envoi" class="submit" value="Envoyer" />
            <span class="success"><?php echo $success ?></span>
        </fieldset>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('starttemplate.php'); ?>