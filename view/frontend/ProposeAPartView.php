<?php $title = 'Proposer une partie'; ?>
<?php ob_start(); ?>

<div class="titreveritable">
    Proposer une partie
</div>
<br /><br />
<form method="get">
    <table>
        <input type="hidden" name="action" value="proposer partie" />
        <tr>
            <td style="padding-right:10px; text-align: right;">Pseudonyme de votre adversaire<br />(ou laisser le champs vide)</td>
            <td><input id="lenom" type="text" name="proposeur" size="20" style="text-align: left;" value="" /></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td style="text-align: right; padding-right: 10px;">Vous voulez jouer avec</td>
            <td style="text-align: right;">
                <select name="color">
                    <option value="-">couleur au hazard</option>
                    <option value="b">les blancs</option>
                    <option value="n">les noirs</option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-right: 10px;">Temps maximum par coup</td>
            <td style="text-align: right;">
                <select name="cadence">
                    <option value="1">1 journée/coup</option>
                    <option value="2">2 journée/coup</option>
                    <option value="3" selected="">3 journée/coup</option>                        
                </select>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-right: 10px;">Réserve de temps</td>
            <td style="text-align: right;">
                <select name="reserve">
                    <option value="1">1 journée</option>
                    <option value="2">2 jours</option>
                    <option value="3">3 jours</option>
                    <option value="7" selected>1 semaine</option>
                    <option value="14">2 semaines</option>
                </select>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td style="text-align: right; padding-right: 10px;">Commentaire</td><td><input type="text" name="commentaire" size="25" style="text-align: left;" value="" /></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td colspan="2"><input type="submit" class="submit" value="Proposer" /></td></tr>
    </table>
</form>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>