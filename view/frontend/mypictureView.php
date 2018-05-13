<?php $title = 'Votre profil'; ?>
<?php ob_start(); ?>

<div class="titreveritable">
    Votre profil
</div>
<div class="profil">
    <div class="icones">
        <a href="?action=profil"><img src="./public/images/icons/lock.png" alt="Mot de passe" /></a>
        <a href="?action=usager"><img src="./public/images/icons/user_green.png" alt="Mes informations" /></a>
        <img src="./public/images/icons/photo.png" alt="Ma photo" />
    </div>
    <div class="gauche">
        Ma photo actuelle:<br />
        <img id="photo" src="<?php echo $usager->photo() ?>" />
        <br />Ajoutez ou modifiez votre photo
        <br /><br />Attention:
        <br />- seuls les fichiers au format <i>jpeg</i> (type .jpg ou .jpeg) sont acceptés.
        <br />- taille maximum du fichier = 200 ko<br />
        <form  enctype="multipart/form-data" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
            <input required="true" name="nom_du_fichier" type="file"  maxlength="80" size="60" />
            <input type="submit" name="ok" class="submit" value="Modifier" />
        </form>
    </div>
</div>


<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>