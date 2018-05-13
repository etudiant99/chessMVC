<?php $title = 'Connection'; ?>

<?php ob_start(); ?>

<div id="content">
    <form method="post">
        <fieldset>
            <legend>Connection</legend>
            <input type="hidden" name="action" value="accueil" />
            <label>Pseudo:</label>
            <span class="erreur"><?php if ($type_pseudo == 'text') echo $valeur_pseudo; ?></span>
            <input id="lenom" type="text" name="pseudo" value="<?php echo $pseudo ?>" />
            <label>mot de passe:</label>
            <span class="erreur"><?php if ($type_confirmation == 'text') echo $valeur_confirmation; ?></span>
            <input id="lepassword" type="password" name="password" value="<?php echo $password ?>" />
            <input type="submit" name="envoi" class="submit" value="Envoyer" />
        </fieldset>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('starttemplate.php'); ?>