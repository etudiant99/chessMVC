<?php $title = 'Mes statistiques'; ?>

<?php ob_start(); ?>
<div class="titreveritable">
    Mes statistiques
</div>
<p><b>Vos parties:</b>
<br /><br />
Vous avez <?php echo $partiesencours ?> <a href="?action=mes parties"><?php echo $message_pour_parties  ?></a> (<b><?php echo $partiesavecblancs ?></b> avec les Blancs, <b><?php echo $partiesavecnoirs ?></b> avec les Noirs).
<br /><br />
Vous avez <b><?php echo $statistique->partiestotales() ?></b><?php echo $message_parties_jouees  ?>depuis le <?php echo $dateInscription; ?><br />
(<?php echo $statistique->partiesavecblancs() ?> avec les Blancs, <?php echo $statistique->partiesavecnoirs() ?> avec les Noirs).</p>
<p>Vos résultats: <u><strong><?php echo $statistique->pourcentagegains() ?> parties gagnées</u></strong>.
<br />

<table class="listtable" style="margin-top: 10px;">
<tr style="height: 40px; background-image:url('images/bg/table_header_bg.png'); background-repeat:repeat-x;">
<td></td>
<td>Blancs</td>
<td>Noirs</td>
</tr>
<tr>
<td>Gains:</td>
<td><?php echo $gainsblancs; ?></td>
<td><?php echo $gainsnoirs; ?></td>
</tr>
<tr>
<td>Nulles:</td>
<td><?php echo $nullesblancs; ?></td>
<td><?php echo $nullesnoires; ?></td>
</tr>
<tr>
<td>Défaites:</td>
<td><?php echo $pertesblancs; ?></td>
<td><?php echo $pertesnoires; ?></td>
</tr>
</table></p>

</div>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>