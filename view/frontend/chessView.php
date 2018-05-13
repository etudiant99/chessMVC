<?php $title = 'Connection'; ?>

<?php ob_start(); ?>

<div id="infos">
    Jouer aux échecs, est un site de base pour jouer aux echecs:
</div>
<div id="details">
    Il s'améliorera avec le temps, laissez la chance au coureur
</div>

<?php $content = ob_get_clean(); ?>
<?php require('starttemplate.php'); ?>