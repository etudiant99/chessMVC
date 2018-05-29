<?php
session_start();

function chess()
{
    require('view/frontend/chessView.php');
}

function connection()
{
    $pseudo = '';
    $password = '';
    $type_confirmation = '';
    $type_pseudo = "hidden";
    $type_confirmation = "hidden";
    $parties = new PartieManager;
    $validation = new ConnectionManager;
    if (isset($_POST['envoi']))
    {
        extract($_POST);
        $resultat = $validation->connecter_joueur($pseudo,$password);
        $valeur_pseudo = $resultat['pseudo'];
        $type_pseudo = $resultat['type_pseudo'];
        $valeur_confirmation = $resultat['confirmation'];
        $type_confirmation = $resultat['type_confirmation'];
        $statut_pseudo = $resultat['pseudo_ok'];
        if ($statut_pseudo == true)
        {
            $mesparties = $parties->partieencours($pseudo);
            if ($mesparties != null)
                header('Location: ./?action=mes parties');
            else
                header('Location: ./?action=les parties');
        }   
    }
      
    require('view/frontend/conectionView.php');
}

function deconnection()
{
    $uidActif = $_SESSION['uid'];
    unset($_SESSION['uid']);
    unset($_SESSION['pseudo']);
    $managerconnections = new ConnectionManager;
    $managerconnections->quitter($uidActif);
}

function inscription()
{
    $success = '&nbsp;&nbsp;';
    $pseudo = '';
    $email = '';
    $type_pseudo = "hidden";
    $type_confirmation = "hidden";
    $valeur_pseudo = '';
    $validation = new Inscription;
    if (isset($_POST['envoi']))
    {
        extract($_POST);
        $resultat = $validation->enregistrer_joueur($pseudo,$email);
        $valeur_pseudo = $resultat['pseudo'];
        $type_pseudo = $resultat['type_pseudo'];
        $valeur_confirmation = $resultat['confirmation'];
        $type_confirmation = $resultat['type_confirmation'];
        $statut_pseudo = $resultat['pseudo_ok'];
        if ($statut_pseudo == true)
            $success = 'Consultez vos courriels';
    }
    
    require('view/frontend/inscriptionView.php');
}

function oublie()
{
    $type_courriel = "hidden";
    $valid = false;
    $validation = new JoueurManager;
    $success = '&nbsp;&nbsp;';
    $email = '';
    
    if (isset($_POST['envoi']))
    {
        extract($_POST);
        $resultat = $validation->informer_joueur($email);
        $valeur_courriel = $resultat['courriel'];
        $type_courriel = $resultat['type_courriel'];
        $success = $resultat['success'];
    }
    
    require('view/frontend/oublieView.php');
}

function accueil()
{
    require('view/frontend/acceuilView.php');
}

function mesparties()
{
    $uidActif = $_SESSION['uid'];
    $managerPartieproposee = new PartieproposeeManager;
    $joueurs = new JoueurManager;
    $mespartiesproposees = $managerPartieproposee->getListpppersonnelles($uidActif);
    
    if (count($mespartiesproposees) > 0)
        require('view/frontend/personalProposedGamesView.php');
    else
    {
        $managerParties = new PartieManager;
        $joueurs = new JoueurManager;
        $managerParties->setListePartiesActif($uidActif);
        $mesparties = $managerParties->getListepartiesActif();
    
        require('view/frontend/myGamesView.php');
    }
}

function partiesterminees()
{
    $uidActif = $_SESSION['uid'];
    $managerParties = new PartieManager;
    $lesparties = $managerParties->getListPartiesterminees($uidActif);
    $joueurs = new JoueurManager;
    
    require('view/frontend/completedGamesView.php');
}

function partiesproposees()
{
    $uidActif = $_SESSION['uid'];
    $managerPartieproposee = new PartieproposeeManager;
    $joueurs = new JoueurManager;
    $lespartiesproposees = $managerPartieproposee->getList($uidActif);
    
    require('view/frontend/proposedGamesView.php');
}

function statistiques()
{
    $uidActif = $_SESSION['uid'];
    $managerParties = new PartieManager;
    $joueur = $managerParties->trouveJoueur($uidActif);
    $managerStatistiques = new StatistiqueManager;
    $lesstatistiques = $managerStatistiques->getList();
    $statistique = $managerStatistiques->get($uidActif);
    $dateInscription = $managerStatistiques->formate_date($joueur->date_inscription());
    $gainsblancs = $statistique->gains_b();
    $gainsnoirs = $statistique->gains_n();
    $pertesblancs = $statistique->pertes_b();
    $nullesblancs = $statistique->nulles_b();
    $nullesnoires = $statistique->nulles_n();
    $pertesnoires = $statistique->pertes_n();
    $partiesavecblancs = $managerParties->countpartiesblancs($uidActif);
    $partiesavecnoirs = $managerParties->countpartiesnoirs($uidActif);
    $partiesencours = $partiesavecblancs+$partiesavecnoirs;
                    
    if ($partiesencours < 2)
        $message_pour_parties = 'partie en cours';
    else
        $message_pour_parties = 'parties en cours';
    if ($statistique->partiestotales() < 2)
        $message_parties_jouees = ' partie terminée ';
    else
        $message_parties_jouees = ' parties terminées ';
                        
    require('view/frontend/myStatisticView.php');
}

function profil()
{
    if (isset($_GET['pw1']))
        $pw1 = $_GET['pw1'];
    if (isset($_GET['pw2']))
        $pw2 = $_GET['pw2'];
    $joueurs = new JoueurManager;
    if (isset($pw1) & isset($pw2))
        $joueurs->traiterpassword($pw1,$pw2);
    
    require('view/frontend/profilView.php');
}

function usager()
{
    $uidActif = $_SESSION['uid'];
    $sexe = null;
    if (isset($_GET['sexe']))
        $sexe = $_GET['sexe'];
    if (isset($_GET['pays']))
        $pays = $_GET['pays'];
    if (isset($_GET['jour']))
        $day = $_GET['jour'];
    if (isset($_GET['mois']))
        $month = $_GET['mois'];
    if (isset($_GET['annee']))
        $year = $_GET['annee'];
    if (isset($_GET['naissance']))
        $naissance = $_GET['naissance'];
    if (isset($_GET['photo']))
        $photo = $_GET['photo'];

    $usagers = new JoueurManager;
    $usager = $usagers->trouveJoueur($uidActif);
    $date_naissance = $usager->naissance();
    if ($date_naissance != null)
    {
        $arr1 = explode('-', $date_naissance);
        if ($arr1[0] != '0000')
        {
            $jour = $arr1[2];
            $mois = $arr1[1];
            $annee = $arr1[0];
        }
    }
    if (isset($sexe))
    {
        $changement = $year.'-'.$month.'-'.$day;
        $usagers->traiterinfos($sexe,$pays,$changement);
    }

    require('view/frontend/usagerView.php');
}

function maphoto()
{
    $uidActif = $_SESSION['uid'];
    $resultat = false;
    $nom_destination = null;
    $nom_destination = './public/images/joueurs/'.'h'.$uidActif.'.jpg';
    $types = '{jpg,jpeg}';
    $joueurs = new JoueurManager;
    if (isset($_POST['MAX_FILE_SIZE']))
    {
        $taille_maximum = $_POST['MAX_FILE_SIZE'];
        $taille_image = $_FILES['nom_du_fichier']['size'];
        $nom_temporaire = $_FILES['nom_du_fichier']['tmp_name'];
    }
    if (isset($taille_maximum))
    {
        $type_fichier = $_FILES['nom_du_fichier']['type'];
        if (($taille_image < $taille_maximum) && ($type_fichier == "image/jpeg"))
        {
            $resultat = move_uploaded_file($nom_temporaire,$nom_destination);
            $filename = $nom_destination;
            $width = 200;
            $height = 200;
            list($width_orig, $height_orig) = getimagesize($filename);
            $ratio_orig = $width_orig/$height_orig;
            if ($width/$height > $ratio_orig)
                $width = $height*$ratio_orig;
            else
                $height = $width/$ratio_orig;
            // Redimensionnement
            $image_p = imagecreatetruecolor($width, $height);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagejpeg($image_p,$nom_destination);
            imagedestroy($image_p);
            if ($resultat)
                $joueurs->updatephoto($uidActif);
        }
    }
    
    $uidActif = $_SESSION['uid'];
    $joueurs = new JoueurManager;
    $usager = $joueurs->trouveJoueur($uidActif);
    
    require('view/frontend/mypictureView.php');
}

function parties()
{
    $managerParties = new PartieManager;
    $joueurs = new JoueurManager;
    $lesparties = $managerParties->setListeParties();
    $lesparties = $managerParties->getListeParties();
    
    require('view/frontend/gamesView.php');
}

function joueurs()
{
    $managerJoueurs = new JoueurManager;
    $joueurs = $managerJoueurs->getList();
    
    require('view/frontend/playersView.php');
}

function statistica()
{
    $statistiques = new StatistiqueManager;
    $nbpartiesjouees = $statistiques->nbpartiesjouees();
    $managerJoueurs = new JoueurManager;
    $managerParties = new PartieManager;
    $joueursinscrits = $managerJoueurs->countinscrits();
    $joueursconnectes = $managerJoueurs->countenligne();
    $joueursrecemmentconnectes = $managerJoueurs->countrecemmentconecte();
    $partiesencours = $managerParties->countencours();
    
    if ($joueursrecemmentconnectes > 1)
        $phrase_joueur_connecte = " joueurs se sont connectés ";
    else
        $phrase_joueur_connecte = " joueur s'est connecté ";
    if ($joueursconnectes > 1)
        $phrase_joueur_en_ligne = ' joueurs en ligne.';
    else
        $phrase_joueur_en_ligne = ' joueur en ligne.';
    if ($partiesencours > 1)
        $messagenbparties = " parties ";
    else
        $messagenbparties = " partie ";
    require('view/frontend/StatisticsView.php');
}

function montrerPartie($nopartie)
{
    $parties = new PartieManager;
    $joueurs = new JoueurManager;
    $partie = $parties->get($nopartie);
    $mangeaille = $partie->getMangeaille();
    $titre = $partie->getTitre();
    $affichagePromotion = null;
    $position = $parties->getPositionDepart();
    $trait = $parties->Montrait();
    $lettres = array('a','b','c','d','e','f','g','h');
    $flip = $partie->getFlipBase();
    $cliquable = $partie->getCliquable();
    $lastmove = $parties->Lastmove();
    //$partie->duree_restante();
    
    if($lastmove != '')
    {
        $derniercoup = -1;
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
        if (($position[$end] == 'p' && $trait == 1) || ($position[$end] == 'P' && $trait == -1))
            $derniercoup = $end;
    }
    else
    {
        $start = -1;
        $end = -1;
        $derniercoup = -1;
    }
    
    $cell1 = -1;
    $cell2 = -1;
    if (isset($_GET['depart']))
        $cell1 = $_GET['depart'];
            
    if (isset($_GET['arrivee']))
        $cell2 = $_GET['arrivee'];

    if ($cell2 != -1 && $cell2 == $cell1)
    {
        $cell1 = -1;
        $cell2 = -1;
    }
    if ($cell1 != -1)
    {
        if ($cell1>47 && $position[$cell1] == 'P')
        {
            $positiontemp = $position;
            $parties->setPromotion('Q');
            $affichagePromotion = $parties->dessinerPiecesPromotion('1',$partie->gid(),$cell1,$cell2);
        }
        if ($cell1<16 && $position[$cell1] == 'p')
        {
            $positiontemp = $position;
            $parties->setPromotion('q');
            $affichagePromotion = $parties->dessinerPiecesPromotion('-1',$partie->gid(),$cell1,$cell2);
        }
        if (isset($_GET['piece']))
        {
            $mapiece = $_GET['piece'];
            $parties->setPromotion($mapiece);
        }
        else
            $mapiece = '';
                
        $lapiece = $parties->trouve($position[$cell1]);
        $endroitsPossibles =  $lapiece->endroitsPossibles($position,$cell1,$trait,$derniercoup);
            
        if (isset($lapiece))
        {
            $lapiece->deplacer($position,$cell1,$trait,$derniercoup);
            $positions = $lapiece->positionsPossibles();
            $piecesattaquees = $lapiece->piecesAttaquees();
            $piecesdefendues = $lapiece->piecesDefendues();
        }   
    }
    $couleur = 1;
    require('view/frontend/playView.php');
}

function jouerlecoup($nopartie,$coup,$changementB,$changementN)
{
    $managerParties = new PartieManager;
    $managerParties->jouer($nopartie,$coup,$changementB,$changementN);
}

function proposerpartie()
{
    $uidActif = $_SESSION['uid'];
    $proposeur = 0;
    if (isset($_GET['proposeur']))
        $proposeur = $_GET['proposeur'];
    if (isset($_GET['color']))
        $color = $_GET['color'];
    if (isset($_GET['cadence']))
        $cadence = $_GET['cadence'];
    if (isset($_GET['reserve']))
        $reserve = $_GET['reserve'];
    if (isset($_GET['commentaire']))
        $commentaire = $_GET['commentaire'];

    $partiesproposees = new PartieproposeeManager;
    $joueur = new JoueurManager;
    $uid = $joueur->exists($proposeur);
    
    if (isset($color))
        $partiesproposees->add($uid,$uidActif,$color,$cadence,$reserve,$commentaire);
    
    require('view/frontend/ProposeAPartView.php');
}

function mespartiesproposees()
{
    $uidActif = $_SESSION['uid'];
    $managerPartieproposee = new PartieproposeeManager;
    $joueurs = new JoueurManager;
    $mespartiesproposees = $managerPartieproposee->getListmespp($uidActif);
    
    require('view/frontend/myProposedGamesView.php');
}

function acceptation($nopartie)
{
    $managerParties = new PartieManager;
    $lesparties = $managerParties->acepter($nopartie,$_SESSION['uid']);
}

function refus($nopartie,$but)
{
    $managerParties = new PartieproposeeManager;
    $lesparties = $managerParties->refuser($nopartie,$but);
}

function effacer($nopartie,$but)
{
    $managerParties = new PartieproposeeManager;
    $lesparties = $managerParties->refuser($nopartie,$but);
}

function effacerjoueur($uid)
{
    $managerJoueurs = new JoueurManager;
    $managerJoueurs->effacer($uid);
}

function rejouer($gid)
{
    $_SESSION['nopartie'] = $gid;
    $parties = new  PartieManager;
    $partie = $parties->get($gid);
    $titre = $partie->getTitre();
    $totalcoups = $partie->getNbCoups();
    $mangeaille = $partie->getMangeaille();
    $ign = $parties->Ign();
    $flip = $partie->getFlipBase();
    $lastmove = $parties->Lastmove();
    if($lastmove != '')
    {
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
    }
    else
    {
        $start = -1;
        $end = -1;
    }
    $position = $parties->position();
    $lettres = array('a','b','c','d','e','f','g','h');
    $couleur = 1;
    require('view/frontend/replayView.php');
  }
  
function debutPartie()
{
    $gid = $_GET['id'];
    $flip = $_GET['f'];
    $parties = new PartieManager;
    $partie = $parties->get($gid);
    $titre = $partie->getTitre();
    $ign = '';
    $totalcoups = 0;
    $lettres = array('a','b','c','d','e','f','g','h');
    $parties->positionarbitraire($partie->getIgn(),$totalcoups);
    $position = $parties->position();
    $lastmove = $parties->Lastmove();
    if($lastmove != '')
    {
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
    }
    else
    {
        $start = -1;
        $end = -1;
    }
    $couleur = 1;
    require('view/frontend/replayView.php');
}
function coupPrecedent()
{
    $gid = $_GET['id'];
    $flip = $_GET['f'];
    $choix = $_GET['t'];
    $parties = new PartieManager;
    $partie = $parties->get($gid);
    $titre = $partie->getTitre();
    $ign = $partie->getIgn();
    $choix--;
    if ($choix <= 0)
    {
        $choix = 0;
        $ign = "";
    }
    $totalcoups = $choix;
    $lettres = array('a','b','c','d','e','f','g','h');
    $parties->positionarbitraire($partie->getIgn(),$choix);
    $mangeaille = $parties->getMangeaille();
    $position = $parties->position();
    $lastmove = $parties->Lastmove();
    if($lastmove != '')
    {
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
    }
    else
    {
        $start = -1;
        $end = -1;
    }
    $couleur = 1;
    require('view/frontend/replayView.php');
}

function coupSuivant()
{
    $gid = $_GET['id'];
    $flip = $_GET['f'];
    $choix = $_GET['t'];
    $parties = new PartieManager;
    $partie = $parties->get($gid);
    $titre = $partie->getTitre();
    $ign = $partie->getIgn();
    
    $totalcoups = $partie->getNbCoups();
    $choix++;
    if ($choix >= $totalcoups)
        $choix = $totalcoups;
    else
        $totalcoups = $choix;
    $lettres = array('a','b','c','d','e','f','g','h');
    $parties->positionarbitraire($partie->getIgn(),$choix);
    $mangeaille = $parties->getMangeaille();
    $position = $parties->position();
    $lastmove = $parties->Lastmove();
    if($lastmove != '')
    {
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
    }
    else
    {
        $start = -1;
        $end = -1;
    }
    $couleur = 1;
    require('view/frontend/replayView.php');
}

function finPartie()
{
    $gid = $_GET['id'];
    $flip = $_GET['f'];
    $parties = new PartieManager;
    $partie = $parties->get($gid);
    $mangeaille = $partie->getMangeaille();
    $titre = $partie->getTitre();
    $ign = $partie->getIgn();
    $totalcoups = $partie->getNbCoups();
    $lettres = array('a','b','c','d','e','f','g','h');
    $parties->positionarbitraire($partie->getIgn(),$totalcoups);
    $position = $parties->position();
    $lastmove = $parties->Lastmove();
    if($lastmove != '')
    {
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
    }
    else
    {
        $start = -1;
        $end = -1;
    }
    $couleur = 1;
    require('view/frontend/replayView.php');
}

function tournerEchiquier()
{
    $gid = $_GET['id'];
    $flip = $_GET['f'];
    $choix = $_GET['t'];
    $parties = new PartieManager;
    $partie = $parties->get($gid);
    $titre = $partie->getTitre();
    $ign = $partie->getIgn();
    $totalcoups = $partie->getNbCoups();
    if($flip == 0)
        $flip = 1;
    else
        $flip = 0;

    if ($choix <= 0)
    {
        $choix = 0;
        $ign = "";
    }
    if ($choix >= $totalcoups)
        $choix = $totalcoups;

    $totalcoups = $choix;
    $lettres = array('a','b','c','d','e','f','g','h');
    $parties->positionarbitraire($partie->getIgn(),$totalcoups);
    $mangeaille = $parties->getMangeaille();
    $position = $parties->position();
    $lastmove = $parties->Lastmove();
    if($lastmove != '')
    {
        $lmove = explode("-",$lastmove);
        $start = $lmove[0];
        $end = $lmove[1];
    }
    else
    {
        $start = -1;
        $end = -1;
    }
    $couleur = 1;
    require('view/frontend/replayView.php');
}

function nulle($nopartie)
{
    $managerParties = new PartieManager;
    $managerParties->indiquernulle($nopartie);
}

function abandon($nopartie)
{
    $uidActif = $_SESSION['uid'];
    $parties = new PartieManager;
    $parties->abandonner($uidActif,$nopartie);
}

function terminerpartie($nopartie)
{
    $uidActif = $_SESSION['uid'];
    $parties = new PartieManager;
    $parties->terminerPartie($uidActif,$nopartie);
}

function joueur($id)
{
    $managerjoueurs = new JoueurManager;
    $individu = $managerjoueurs->trouveJoueur($id);
    $managerStatistiques = new StatistiqueManager;
    $statistique = $managerStatistiques->get($id);
    
    $pseudo = $individu->pseudo();
    $elo = $individu->elo();
    $age = $individu->age();
    $description = $individu->description();
    $photo = $individu->photo();
    $monpays = $individu->pays();
    $partiestotales = $statistique->partiestotales();
    $gainstotaux = $statistique->gainstotaux();
    
    if (isset($monpays) and ($monpays != 'zz.png')) 
    {
        $pays = './public/images/pays/'.$individu->pays();
        $imagepays = '<img border="2" width="40" src="'.$pays.'">';
    }
    else
        $imagepays = '';
         

    
    require('view/frontend/UserView.php');
}