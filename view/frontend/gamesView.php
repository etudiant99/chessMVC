<?php $title = 'Les parties'; ?>
<?php ob_start(); ?>

<div class="titreveritable">
    Les parties en cours
</div>
<?php
if (count($lesparties) == 0)
{
?>
    <div class="decompte">
        Il n'y a aucune partie en cours
    </div>
<?php
}
else
{
    ?>
    <div class="decompte">
        <table>
            <tr><th>Nb</th><th>adversaires</th><th>#partie</th><th>démarrée le</th><th>coups</th><th>tour</th></tr>
            <?php
            $compteur = 0;
            foreach ($lesparties as $partie)
            {
                $lesBlancs = $joueurs->trouveJoueur($partie->uidb());
                $lesNoirs = $joueurs->trouveJoueur($partie->uidn());
                $statistiqueb = $joueurs->get($lesBlancs->uid());
                $statistiquen = $joueurs->get($lesNoirs->uid());
                $contenulesBlancs = $lesBlancs->pseudo().'<br />';
                $contenulesBlancs .= 'Elo: '.$lesBlancs->elo().'<br />';
                $contenulesBlancs .= 'Age: '.$lesBlancs->age().'<br />';
                $contenulesBlancs .= 'Parties: '.$statistiqueb->partiestotales().'<br />';
                $contenulesBlancs .= 'Gains: '.$statistiqueb->gainstotaux().'<br /><br />';
                $contenulesNoirs = $lesNoirs->pseudo().'<br />';
                $contenulesNoirs .= 'Elo: '.$lesNoirs->elo().'<br />';
                $contenulesNoirs .= 'Age: '.$lesNoirs->age().'<br />';
                $contenulesNoirs .= 'Parties: '.$statistiquen->partiestotales().'<br />';
                $contenulesNoirs .= 'Gains: '.$statistiquen->gainstotaux().'<br /><br />';

                $compteur++;
                ?>
                <tr>
                    <td><?php echo $compteur ?></td>
                    <td><span class="infobulle" data-info="<?php echo $contenulesBlancs; ?><img src='<?php echo $lesBlancs->photo() ?>'"><?php echo $lesBlancs->pseudoimage() ?></span>
                     - <span class="infobulle" data-info="<?php echo $contenulesNoirs; ?><img src='<?php echo $lesNoirs->photo() ?>'"><?php echo $lesNoirs->pseudoimage() ?></span></td>
                    <td><?php echo $partie->gid() ?></td>
                    <td><?php echo $partie->datedebut() ?></td>
                    <td><?php echo $partie->getNbCoups() ?></td>
                    <td><a href="?action=rejouer&amp;gid=<?php echo $partie->gid() ?>"><?php echo $partie->getSituation() ?></a></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
<?php
}
?>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>