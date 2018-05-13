<?php $title = 'Jouer'; ?>

<?php ob_start(); ?>

        <?php ob_start(); ?>
        <div class="titre">
            <?php echo $titre; ?>
            <div class="infosdroite">
                Date du dernier coup:<?php echo $parties->dateenlettres($partie->datederniercoup()) ?>
                <br /><br />
                <?php
                if ($cliquable == true)
                {
                    if ($trait == 1)
                        $reservepersonnelle = '(+ réserve blanche de '.number_format($partie->getreserveBlanche(),2);
                    else
                        $reservepersonnelle = '(+ réserve noire de '.number_format($partie->getreserveNoire(),2); //ici
                    ?>
                    <u>Le temps restant:</u>&nbsp;&nbsp;<b><?php echo $partie->tempsRestant() ?></b><br /><?php echo $reservepersonnelle ?> jours)<br /><br />
                    Réserve des Blancs: <?php echo $partie->pourcentage_a_blancs() ?>%
                    <?php
                }
                else
                {
                    if ($trait == 1)
                        $reservepersonnelle = '(+ réserve blanche de '.number_format($partie->getreserveBlanche(),2);
                    else
                        $reservepersonnelle = '(+ réserve noire de '.number_format($partie->getreserveNoire(),2); //la
                    ?>
                    <u>Le temps restant:</u>&nbsp;&nbsp;<b><?php echo $partie->tempsRestant() ?></b><br /><?php echo $reservepersonnelle ?> jours)<br /><br />
                    Réserve des Blancs: <?php echo $partie->pourcentage_a_blancs() ?>%
                    <?php
                }
                ?>
                <br />
                <table style="width: 100%;">
                    <tr>
                        <div style="background-color:#f00;">
                        <div style="width:<?php echo $partie->pourcentage_a_blancs() ?>%;background-color:#ff0;">&nbsp;</div>
                        </div>
                    </tr>
                </table>
                <br />
                Réserve des Noirs: <?php echo $partie->pourcentage_a_noirs() ?>%
                <br />
                <table style="width: 100%;">
                    <tr>
                        <div style="background-color:#f00;">
                            <div style="width:<?php echo $partie->pourcentage_a_noirs() ?>%;background-color:#ff0;">&nbsp;</div>
                        </div>
                    </tr>
                </table>
                <br />
                <?php
                echo '<b>'.$parties->nbCoupsPossibles($partie->gid(),$derniercoup).'</b>';
                if ($cliquable == true)
                {   
                    ?>
                    <a href="?action=montrer partie&amp;gid=<?php echo $partie->gid() ?>&amp;but=abandonner" 
                    onclick="if(!confirm('Voulez-vous vraiment abandonner ?')) return false;">Abandonner</a>
                    <br />
                <?php
                echo $affichagePromotion;
                }
                ?>            
                <br /><br />
                <?php
                $lecoup = '';
                $resultat = false;
        
                if ($partie->getMat())
                    $parties->mat($partie->actif(),$partie->gid());
            
                if ($partie->getPartieNulle())
                    $parties->nulle($partie->gid());
                
                if ($cell1 != -1 and $cell2 != -1)
                {
                    $positiontemp = $position;
                    $position[$cell2] = $parties->getPromotion();
                    
                    if ($derniercoup != -1 && $derniercoup+8 == $cell2 && $position[$cell2] == '')
                    {
                        $cell2 = $derniercoup+8;
                        $positiontemp = $position;
                    }
                    
                    if ($derniercoup != -1 && $derniercoup-8 == $cell2 && $position[$cell2] == '')
                    {
                        $cell2 = $derniercoup-8;
                        $positiontemp = $position;
                    }

                        
                    $p = $position[$cell1];
                    $lecoup = $parties->moveToText($cell1).$parties->moveToText($cell2).$parties->getPromotion();
                    $lapiece = $parties->trouve($p);
                    
                    // lecoup est les coordonnées du coup, sous la forme a7a5
                    // la position est le contenu de chacune des 64 cases
                    // cell1 est le numéro de la case de départ pour le coup
                    // cell2 est le numéro de la case d'arrivée pour le coup
                    // derniercoup est le numéro de la case d'arrivée pour le dernier coup de l'adversaire
                    // lapiece est le nom/couleur de la pièce se trouvant sur la case de départ de la pièce à bouger
                    
                    $coupvalide = $lapiece->legal($positiontemp,$cell1,$cell2,$derniercoup);
                    
                    if ($coupvalide)
                    {
                        ?>
                        <div class="acceptercoup">
                        <form>
                            <input type="hidden"   name="action"  value="mes parties" />
                            <input type="hidden"   name="lecoup"  value="<?php echo $lecoup ?>" />
                            <input type="hidden"   name="gid"  value="<?php echo $partie->gid() ?>" />
                            <input type="hidden"   name="chb"  value="<?php echo $partie->changementB() ?>" />
                            <input type="hidden"   name="chn"  value="<?php echo $partie->changementN() ?>" />
                            <input type="submit" value="Jouer" /><?php echo ' '.$lecoup ?>
                        </form>
                        </div>
                        <?php
                    }
                    else
                        echo 'Coup invalide';
                }
                ?>
            </div>
        </div>
        <div class="leschiffres">
            <div>
            <?php
            if ($flip == false)
                for ($j=8;$j>0;$j--)
                {
                    ?>
                    <div class="chiffres"><?php echo $j; ?></div>
                    <?php
                }
            else
                for ($j=1;$j<9;$j++)
                {
                    ?>
                    <div class="chiffres"><?php echo $j; ?></div>
                    <?php
                }
            ?>
            </div>
        </div>
        <div class="echiquier">
            <?php
            for($ligne=0; $ligne<8; $ligne++)
            {
                for ($colonne=0;$colonne<8;$colonne++)
                {
                    if ($flip == false)
                        $i = (7-$ligne)*8 + $colonne;
                    else
                        $i = $ligne*8+(7-$colonne);
                    
                    if ($couleur == 1)
                        $bgcolor = "white";
                    else
                        $bgcolor = "lightblue";
                    
                    // Pour les cases attaquees
                    if (isset($lescasesattaquees) && $cell1 == -1)
                        for ($t=0;$t<count($lescasesattaquees);$t++)
                        {
                            if ($i == $lescasesattaquees[$t])
                                $bgcolor = "#00effc";
                        }
                    
                    // Pour le lastmove
                    if ($i == $start || $i == $end)
                        $bgcolor = "#baffbf";
                    
                    // Pour les endroits ou l'on peut jouer la piece
                    if (isset($positions) && $cell2 == -1)
                        for ($po=0;$po<count($positions);$po++)
                        {
                            if ($i == $positions[$po])
                                $bgcolor = "#99fff3";   
                        }
                    
                    if (isset($piecesattaquees))
                        for ($pi=0;$pi<count($piecesattaquees);$pi++)
                        {
                            if ($i == $piecesattaquees[$pi])
                                $bgcolor = "#eba2a2";
                        }

                    // Pour les pieces que je defends avec la piece que je veux jouer
                    if ($cell1 != -1)
                    {
                        if (isset($piecesdefendues) && $cell2 == -1)
                            for ($pi=0;$pi<count($piecesdefendues);$pi++)
                            {
                                if ($i == $piecesdefendues[$pi])
                                    $bgcolor = "#fbe479";
                            }
                    }           

                    // Pour indiquer ou l'on veut déplacer une pièce
                    if ($i == $cell1 or $i == $cell2)
                        $bgcolor = "#ffff35";

                    $lapiece = $parties->trouve($position[$i]);
                    $imagepiece = $lapiece->image();
                    $couleurcase = $lapiece->Couleur($position[$i]);
                    
                    $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase">'.$imagepiece.'</div>';
                    if ($cliquable)
                    {
                        if ($position[$i] != '' and $couleurcase == $trait)
                            if ($cell1 == -1)
                            {
                                $lapiece = $parties->trouve($position[$i]);
                                $quantite = $lapiece->nbEndroitsPossibles($position,$i,$partie->getTrait(),$derniercoup);
                                if ($quantite > 0)
                                    $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase"><a href="index.php?action=montrer partie&amp;depart='.$i.'&amp;gid='.$partie->gid().'">'.$imagepiece.'</a></div>';
                                else
                                    $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase">'.$imagepiece.'</div>';
                            }
                        
                        if ($cell1 != -1)
                            for ($po=0;$po<count($positions);$po++)
                            {
                                if ($positions[$po]  == $i)
                                    $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase"><a href="index.php?action=montrer partie&amp;depart='.$cell1.'&amp;arrivee='.$i.'&amp;gid='.$partie->gid().'">'.$imagepiece.'</a></div>';
                            }
                    }
                    echo $contenucase;
                    $couleur = -$couleur;
                }
                $couleur = -$couleur;
            }
            ?>
        </div>
        <div class="leslettres">
            <?php
            if ($flip == false)
                for ($j=0;$j<8;$j++)
                {
                    ?>
                    <div class="lettres"><?php echo $lettres[$j]; ?></div>
                    <?php
                }
            else
                for ($j=7;$j>=0;$j--)
                {
                    ?>
                    <div class="lettres"><?php echo $lettres[$j]; ?></div>
                    <?php
                }
            ?>
        </div>
        <div class="piecesblanchessmangees">
        <?php
        if (isset($mangeaille))
        {
            foreach($mangeaille['blancs'] as $item)
                echo $item;
            if (count($mangeaille['blancs']) == 0)
                echo '<img src="./public/images/vide_21.gif">';
        }
        else
            echo '<img src="./public/images/vide_21.gif">';
        ?>
        </div>
        <div class="piecesnoiressmangees">
        <?php
        if (isset($mangeaille))
        {
            foreach($mangeaille['noirs'] as $item)
                echo $item;
            if (count($mangeaille['noirs']) == 0)
                echo '<img src="./public/images/vide_21.gif">';
        }
        else
            echo '<img src="./public/images/vide_21.gif">';
        ?>
        </div>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>