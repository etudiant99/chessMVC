<?php $title = 'Inscription'; ?>
<?php ob_start(); ?>



<div id="content">
    <form id="signup_form" method="post">
        <fieldset>
            <legend>Inscription</legend>
            <label for="pseudo">Pseudo:</label>
            <span class="erreur"><?php if ($type_pseudo == 'text') echo $valeur_pseudo; ?></span>
            <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo ?>" />
            <label for="email">Courriel:</label>
            <span class="erreur"><?php if ($type_confirmation == 'text') echo $valeur_confirmation; ?></span>
            <input type="text" name="email" id="email" value="<?php echo $email ?>" />
            <input type="submit" id="submit_signup" name="envoi" class="submit" value="Envoyer" />
            <span class="success"><?php echo $success ?></span>
        </fieldset>
    </form>
</div>



<?php $content = ob_get_clean(); ?>
<?php require('starttemplate.php'); ?>