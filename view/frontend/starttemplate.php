<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link href="public/css/style.css" rel="stylesheet" />
    </head>

    <body>
        <?php
        $prive = array('connexion' => 'Connection',
               'inscription' => 'Inscription',
               'oublie' => 'Oublié');
        ?>
        <header>
            <div class="petitecran">
                <img src="monlogo.gif" />
            </div>
        </header>
        <br />
        <div class="petitecran">
            <nav>
                <div class="espace">
                    Espace membre
                </div>
                <ul id="espacepublic">
                    <?php
                    foreach ($prive as $key => $value):
                    ?>
                        <li><a href="?action=<?php echo $key ?>"><?php echo $value ?></a></li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            </nav>
            <section>
                <?= $content ?>
            </section>
        </div>
    </body>
</html