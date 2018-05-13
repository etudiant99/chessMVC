<?php $title = 'Votre profil'; ?>
<?php ob_start();
require_once "ajouts.php"; ?>

<div class="titreveritable">
    Votre profil
</div>
<div class="profil">
    <div class="icones">
        <a href="?action=profil"><img src="./public/images/icons/lock.png" alt="Mot de passe" /></a>
        <img src="./public/images/icons/user_green.png" alt="Mes informations" />
        <a href="?action=ma photo" rel="superbox[iframe][600x500]"><img src="./public/images/icons/photo.png" alt="Ma photo" /></a>
    </div>
    <div class="gauche">
        <br />
        Complétez ou corrigez les informations ci-contre
        <br /><br />
        <form>
            <input type="hidden" name="action" value="usager" />
            Vous êtes un(e): <select name="sexe">
            <?php
            var_dump($les_sexes);
            foreach ($les_sexes as $cle=>$valeur) 
            {
                if ($usager->sexe() == $cle)
                    echo '<option value="'.$cle.'" selected>'.$valeur.'</option>';
                else
                    echo '<option value="'.$cle.'">'.$valeur.'</option>';
            }
            ?>
            </select>
            <br />Votre pays: <select name="pays">
                <?php
                foreach ($les_pays as $cle=>$valeur) 
                {
                    if ($usager->pays() == $cle)
                        echo '<option value="'.$cle.'" selected>'.$valeur.'</option>';
                    else
                        echo '<option value="'.$cle.'">'.$valeur.'</option>';
                }
                ?>
            </select>
            <br /></b>Votre date de naissance: <select name="jour">
                <?php
                foreach ($les_jours as $cle=>$valeur) 
                {
                    if ($jour == $cle)
                        echo '<option value="'.$cle.'" selected>'.$valeur.'</option>';
                    else
                        echo '<option value="'.$cle.'">'.$valeur.'</option>';
                }
                ?>
            </select>
            <select name="mois">
                <?php
                foreach ($le_mois as $cle=>$valeur) 
                {
                    if ($mois == $cle)
                        echo '<option value="'.$cle.'" selected>'.$valeur.'</option>';
                    else
                        echo '<option value="'.$cle.'">'.$valeur.'</option>';
                }
                ?>
            </select>
            <select name="annee">
                <option>-</option>
                <?php
                
                for($i=date('Y'); $i>=date('Y') - 100; $i--)
                {
                    if ($annee == $i)
                        echo '<option value="'.$i.'" selected>'.$i.'</option>';
                    else
                        echo '<option value="'.$i.'">'.$i.'</option>';
                }
                ?>
            </select>
            <br /><br />
            <p><input type="submit" class="submit" value="Modifier" />
        </form>
    </div>
</div>


<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>