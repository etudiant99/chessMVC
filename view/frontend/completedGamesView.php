<?php $title = 'Mes parties terminées'; ?>
<?php ob_start(); ?>

<div class="titreveritable">
    Mes parties terminées
</div>

<div class="decompte">
    <?php
    if (count($lesparties) == 0)
    {
        ?>
        Vous n'avez aucune partie terminée
    <?php
    }
    else
    {
        ?>
        <table width="450">
        <tr><th>moi</th><th>#partie</th><th>adversaire:</th><th>résultat:</th><th>effacer</th></tr>
        <?php
        foreach ($lesparties as $partie):
            $adversaire = $joueurs->trouveJoueur($partie->getAdversaire());
            $statistique = $joueurs->get($adversaire->uid());
            $contenuadversaire = $adversaire->pseudo().'<br />';
            $contenuadversaire .= 'Elo: '.$adversaire->elo().'<br />';
            $contenuadversaire .= 'Age: '.$adversaire->age().'<br />';
            $contenuadversaire .= 'Parties: '.$statistique->partiestotales().'<br />';
            $contenuadversaire .= 'Gains: '.$statistique->gainstotaux().'<br /><br />';
            $finalisationStylisee = '<a href="?action=rejouer&amp;gid='.$partie->gid().'">'.$partie->finalisation().'</a>';
            ?>
            <tr>
                <td><?php echo $partie->getImageMaCouleur() ?></td>
                <td><?php echo $partie->gid() ?></td>
                <td class="infobulle" data-info="<?php echo $contenuadversaire; ?><img src='<?php echo $adversaire->photo() ?>'"><?php echo $adversaire->pseudoimage() ?></td>
                <td style="text-align: left;"><?php echo $finalisationStylisee ?></td>
                <td><a href="?action=effacer partie&amp;no=<?php echo $partie->gid() ?>"><img src="./public/images/icons/effacer.png"  width="16" height="16" alt="supprimer cette partie" style="margin-left: 2px;" /></td>
            </tr>
        <?php
        endforeach;
        ?>
        </table>
<?php  
}
?>
</div>
<div class="choix">
    <a href="?action=mes parties">Mes parties en cours</a><br />
    <a href="?action=mes parties proposées">Les parties que j'ai proposées</a><br />
    <a href="?action=parties proposées">Les parties proposées par les autres joueurs</a><br /><br />
</div>


<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>