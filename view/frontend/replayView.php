<?php $title = 'Revoir partie'; ?>
<?php ob_start(); ?>


        <div class="titre">
            <?php
            echo $titre;
            if ($totalcoups > 0)
            {
            ?>
            <div class="coupsdroite">
                <?php
                if ($totalcoups > 0)
                    echo '<input type="button" value="Imprimer les coups" onClick="impressioncoups()"<br /><br /><br >';
                if ($ign != '')
                {
                    $nombrefou = -15;
                    if (isset($_GET['t']))
                        $nombrefou =  $_GET['t'];
                    $coups = explode(" ",$ign);  
                                                                    
                    $parties->positionactuelle($ign);
                    $_SESSION['lescoups'] = $coups;
                    $i = 0;
                    $yvan = $totalcoups;
                                    
                    foreach($coups as $item)
                    {
                        if($i == $yvan-1)
                            $bg = 'color: rgb(0, 0, 255)';
                        else
                            $bg = '';

                        $i++;
                        if ($i%2 == 0)
                        {
                            $truc = '<span style="width: 70px; text-align: left; '.$bg.'">'.$item.'</span>';
                            echo $truc.'<br />';
                        }
                        else
                        {
                            $truc = '<span style="width: 70px; text-align: left; '.$bg.'">'.$item.'</span>';
                            $n = $parties->parseInt($i/2)+1;
                            echo $n.'. '.$truc.'&nbsp;&nbsp;';
                        } 
                    }
                }
                ?>
            </div>
            <?php } ?>
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
                    
            // Pour le lastmove
            if ($i == $start || $i == $end)
                $bgcolor = "#baffbf";
                        
            if (isset($positions))
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

                $lapiece = $parties->trouve($position[$i]);
                $imagepiece = $lapiece->image();
                $couleurcase = $lapiece->Couleur($position[$i]);
                    
                $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase">'.$imagepiece.'</div>';
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
<div>
    <a href="?action=debut&amp;id=<?php echo $partie->gid() ?>&amp;f=<?php echo $flip ?>"><img src="./public/images/icons/set_first.png" border="0"/></a>
    <a href="?action=precedent&amp;id=<?php echo $partie->gid() ?>&amp;t=<?php echo $totalcoups ?>&amp;f=<?php echo $flip ?>"><img src="./public/images/icons/set_previous.png" border="0"/></a>
    <a href="?action=suivant&amp;id=<?php echo $partie->gid() ?>&amp;t=<?php echo $totalcoups ?>&amp;f=<?php echo $flip ?>"><img src="./public/images/icons/set_next.png" border="0"/></a>
    <a href="?action=dernier&amp;id=<?php echo $partie->gid() ?>&amp;f=<?php echo $flip ?>"><img src="./public/images/icons/set_last.png" border="0"/></a>&nbsp;
    <a href="?action=tourner&amp;id=<?php echo $partie->gid() ?>&amp;t=<?php echo $totalcoups ?>&amp;f=<?php echo $flip ?>"><img src="./public/images/icons/flip.png" border="0"/></a>
</div>


<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>