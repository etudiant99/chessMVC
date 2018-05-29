<?php $title = 'Mes parties proposées'; ?>
<?php ob_start(); ?>

<div class="titreveritable">
    Mes parties proposées
</div>

<?php
if (count($mespartiesproposees) == 0)
{
    ?>
    <div class="decompte">
        Vous n'avez proposé aucune partie
    </div>
<?php
}
else
{
    ?>
    <div class="decompte">
        <table>
            <tr><th>proposée à</th><th>son élo</th><th>ma couleur</th><th>cadence<br />(jours)</th><th>réserve<br />(jours)</th><th>commentaire</th><th>Effacer</th></tr>
            <?php
            foreach ($mespartiesproposees as $partie):
                $detail = $partie->detailMapartieproposee($partie->gidp());
                if ($detail['prospect'] != 0)
                {
                    $adversaire = $joueurs->trouveJoueur($detail['prospect']);
                    $statistique = $joueurs->get($adversaire->uid());
                    $contenuadversaire = $adversaire->pseudo().'<br />';
                    $contenuadversaire .= 'Elo: '.$adversaire->elo().'<br />';
                    $contenuadversaire .= 'Age: '.$adversaire->age().'<br />';
                    $contenuadversaire .= 'Parties: '.$statistique->partiestotales().'<br />';
                    $contenuadversaire .= 'Gains: '.$statistique->gainstotaux().'<br /><br />';
                }
                ?>
                <tr>
                    <?php
                    if (isset($adversaire))
                    {
                       ?> 
                        <td class="infobulle" data-info="<?php echo $contenuadversaire; ?><img src='<?php echo $adversaire->photo() ?>'"><?php echo $adversaire->pseudoimage() ?></td>
                        <?php
                    }
                    else
                    {
                        ?>
                        <td><?php echo $detail['pseudostylise'] ?></td>
                        <?php
                    }
                    ?>
                    <td><?php echo $detail['elo'] ?></td>
                    <td><?php echo $detail['macouleur'] ?></td>
                    <td><?php echo $detail['cadence'] ?></td>
                    <td><?php echo $detail['reserve'] ?></td>
                    <td><?php echo $detail['commentaire'] ?></td>
                    <td><a href="?action=traiter partie&amp;but=effacer&id=<?php echo $partie->gidp() ?>"><img src="public/images/icons/effacer.png"  width="20" height="15" alt="supprimer cette partie" border="0" style="margin-left: 2px;" /></a></td>
                </tr>
            <?php
            endforeach;
            ?>    
        </table>
    </div>
<?php
}
?>

<div class="choix">
    <a href="?action=mes parties">Mes parties en cours</a><br />
    <a href="?action=parties proposées">Les parties proposées par les autres joueurs</a><br /><br />
    <a href="?action=proposer partie">Proposer une partie</a>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>