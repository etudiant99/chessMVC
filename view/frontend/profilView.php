<?php $title = 'Votre profil'; ?>

<?php ob_start(); ?>

<div class="titreveritable">
    Votre profil
</div>

<div class="profil">
    <div class="icones">
        <img src="./public/images/icons/lock.png" alt="Mot de passe" />
        <a href="?action=usager"><img src="./public/images/icons/user_green.png" alt="Mes informations" /></a>
        <a href="?action=ma photo" rel="superbox[iframe][600x500]"><img src="./public/images/icons/photo.png" alt="Ma photo" /></a>
    </div>
    <div class="gauche">
        Mot de passe
        <br /><br />Utilisez le formulaire ci-contre pour modifier votre mot de passe.
        <br /> Après modification, vous serez déconnecté et devrez vous reconnecter avec le nouveau mot de passe.
    </div>
    <form>
        <p class="error"></p>
        <input type="hidden" name="action" value="profil" />
        <p>Nouveau mot de passe:<br />
        <input required="true" id="lenom" type="password" name="pw1" size="15" maxlength="10" style="text-align: left;" value="" />
        </p>
        <p>Retapez votre mot de passe:<br />
        <input required="true" type="password" name="pw2" size="15" maxlength="10" style="text-align: left;" value="" />
        </p>
        <input type="submit" name="envoi" class="submit" value="Modifier" />
    </form>
</div>


<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>