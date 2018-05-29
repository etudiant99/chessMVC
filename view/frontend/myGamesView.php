<?php $title = 'Mes parties'; ?>

<?php ob_start(); ?>

<div class="titreveritable">
    Mes parties en cours
</div>
<div class="decompte">
    <?php
    if (count($mesparties) == 0)
    {
        echo "Vous n'avez aucune partie en cours";
    }
    else
    {
        ?>
        <table>
            <tr><th>Nb</th><th>#partie</th><th>ma couleur</th><th>adversaire</th><th style="text-align: left;">temps restant</th><th>coups</th><th>tour</th></tr>
            <?php
            $compteur = 0;
            foreach ($mesparties as $partie)
            {
                $joueur = $joueurs->trouveJoueur($partie->getAdversaire());
                $statistique = $joueurs->get($joueur->uid());
                //$partie->duree_restante();
                $contenujoueur = $joueur->pseudo().'<br />';
                $contenujoueur .= 'Elo: '.$joueur->elo().'<br />';
                $contenujoueur .= 'Age: '.$joueur->age().'<br />';
                $contenujoueur .= 'Parties: '.$statistique->partiestotales().'<br />';
                $contenujoueur .= 'Gains: '.$statistique->gainstotaux().'<br /><br />';
                $TempsRestant = $partie->calculJourMinuteSeconde();                    
                $compteur++;
                ?>
                <tr>
                    <td><?php echo $compteur ?></td>
                    <td><?php echo $partie->gid() ?></td>
                    <td><?php echo $partie->getImageMaCouleur() ?></td>
                    <td class="infobulle" data-info="<?php echo $contenujoueur; ?><img src='<?php echo $joueur->photo() ?>'"><?php echo $joueur->pseudoimage() ?></td>
                    <td><?php echo $TempsRestant->d.'j '.$TempsRestant->h.'h '.$TempsRestant->i.'min '.$TempsRestant->s.'s'; ?></td>
                    <td><?php echo $partie->getNbCoups() ?></td>
                    <td><?php echo $partie->getTour() ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>
</div>

<div class="choix">
    <a href="?action=parties terminées">Mes parties terminées</a><br />
    <a href="?action=mes parties proposées">Les parties que j'ai proposées</a><br />
    <a href="?action=parties proposées">Les parties proposées par les autres joueurs</a><br /><br />
</div>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>