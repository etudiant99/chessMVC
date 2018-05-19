<?php
require_once "./include/config.php";
function chargerClasse($classname)
{
    require './model/'.$classname.'.class.php';
}
spl_autoload_register('chargerClasse');

require('controller/frontend.php');

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'connexion') {
        connection();
    }
    elseif ($_GET['action'] == 'deconnection') {
        deconnection();
    }
    elseif ($_GET['action'] == 'inscription') {
        inscription();
    }
    elseif ($_GET['action'] == 'oublie') {
        oublie();
    }    
    elseif ($_GET['action'] == 'accueil') {
        accueil();
    }
    elseif ($_GET['action'] == 'mes parties') {
        $changementB = 0;
        $changementN = 0;
                        
        if (isset($_GET['gid']))
            $nopartie = $_GET['gid'];
        if (isset($_GET['chb']))
            $changementB = $_GET['chb'];
        if (isset($_GET['chn']))
            $changementN = $_GET['chn'];
        if (isset($_GET['nulle']))
            $nulle = $_GET['nulle'];

        $managerParties = new PartieManager;
        if (isset($_GET['lecoup']))
        {
            if (isset($nulle))
                $managerParties->indiquernulle($nopartie);
            jouerlecoup($nopartie,$_GET['lecoup'],$changementB,$changementN);
        }  
        mesparties();
    }
    elseif ($_GET['action'] == 'parties proposées') {
        partiesproposees();
    }
    elseif ($_GET['action'] == 'mes statistiques') {
        statistiques();
    }
    elseif ($_GET['action'] == 'profil') {
        profil();
    }
    elseif ($_GET['action'] == 'usager') {
        usager();
    }
    elseif ($_GET['action'] == 'ma photo') {
        maphoto();
    }
    elseif ($_GET['action'] == 'deconnection') {
        deconnection();
    }
    elseif ($_GET['action'] == 'les parties') {
        parties();
    }
    elseif ($_GET['action'] == 'les joueurs') {
        joueurs();
    }
    elseif ($_GET['action'] == 'statistiques') {
        statistica();
    }
    elseif ($_GET['action'] == 'montrer partie') {
        $gid = $_GET['gid'];
        $nopartie = $gid;
        if (isset($_GET['but']))
            abandon($nopartie);   

        montrerPartie($nopartie);
    }
    elseif ($_GET['action'] == 'parties terminées') {
        partiesterminees();
    }
    elseif ($_GET['action'] == 'proposer partie') {
        proposerpartie();
    }
    elseif ($_GET['action'] == 'traiter partie') {
        $but = $_GET['but'];
        $nopartie = $_GET['id'];
        if ($but == 'accepter')
        {
            acceptation($nopartie);
        }
        if ($but == 'refuser')
        {
            refus($nopartie,$but);
        }
        if ($but == 'effacer')
            effacer($nopartie,$but);
    }
    elseif ($_GET['action'] == 'terminer partie') {
        terminerpartie($_GET['gid']);
    }
    elseif ($_GET['action'] == 'mes parties proposées') {
        mespartiesproposees();
    }
    elseif ($_GET['action'] == 'rejouer') {
        $gid = $_GET['gid'];
        
        $_SESSION['nopartie'] = $gid;
        $option = 'rejouer';

        rejouer($gid);
    }
    elseif ($_GET['action'] == 'debut') {
        debutPartie();
    }
    elseif ($_GET['action'] == 'precedent') {
        coupPrecedent();
    }
    elseif ($_GET['action'] == 'suivant') {
        coupSuivant();
    }
    elseif ($_GET['action'] == 'dernier') {
        finPartie();
    }
    elseif ($_GET['action'] == 'tourner') {
        tournerEchiquier();
    }
    elseif ($_GET['action'] == 'joueur') {
        joueur($_GET['id']);
    }
    elseif ($_GET['action'] == 'effacer partie') {
        $nopartie = $_GET['no'];
        $managerParties = new PartieManager;
        $managerParties->effacerpartie($nopartie);
    }
    elseif ($_GET['action'] == 'traiter joueur'){
        $but = $_GET['but'];
        $nojoueur = $_GET['uid'];
        if ($but == 'effacer')
            effacerjoueur($nojoueur);
    }
}
else {
    accueil();
}