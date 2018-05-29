<?php
class JoueurManager extends StatistiqueManager
{
    public static $instance;
    

    public function __construct()
    {
        self::$instance=$this;
    }
    
    public function valide_infos($courriel)
    {
        $valeur_courriel = "";
        $type_courriel = "hidden";
        $statut_info = true;
        $helo_address = "mail.etudiant99.com";
        $verifierdomaine = true;
        $verifieradresse = true;
        $adresseretour = "denis@etudiant99.com";
        $erreursretournees = false;
        $resultats = array();
        
        // Vérifications pour le courriel
        if ($courriel == "")
            $valeur_courriel = 'entrez le courriel';
        else
        {
            $inscription = new Inscription;
            $bonne_adresse = $inscription->VerifierAdresseMail($courriel);
            if ($bonne_adresse == false)
                $valeur_courriel = "format entré incorrect";
        }
        
        if ($valeur_courriel != "")
        {
            $type_courriel = "text";
            $statut_info = false;
        }
            
        $resultats['success'] = '&nbsp;&nbsp;';
        $resultats['courriel'] = $valeur_courriel;
        $resultats['type_courriel'] = $type_courriel;
        $resultats['info_ok'] = $statut_info;
        
        return $resultats;
    }
    
    public function informer_joueur($email)
    {
        $panier = $this->valide_infos($email);
        $statut_info = $panier['info_ok'];
        
        if ($statut_info == true)
        {
            $sql = "SELECT * FROM login l INNER JOIN users u ON u.uid = l.uid where u.courriel=?";
            $q = $this->executerRequete($sql, array($email));
            $pseudo = $q->fetch(PDO::FETCH_ASSOC);
            $suite = new Inscription;
            $pass = $suite->Genere_Password(8);
            $uid = $pseudo['uid'];
                        
            $to = $email;
            $subject = 'Nouveau mot de passe';
            $message = '<p>Voici vos identifiants</p>
            <p>Pseudo : <b>'.$pseudo['pseudo'].'</b><br />
            Mot de passe <b>'.$pass.'</b></p>
            <p>Il est recommandé de changer ce mot de passe</p>';
      
            $headers = 'From:noreply@etudiant99.com'."\r\n";
            $headers.='MIME-version: 1.0'."\r\n";
            $headers.='Content-type: text/html; charset=utf-8'."\r\n";
            mail($to,$subject,$message,$headers);

            $invisible =  md5($pass);
            $sql = "update login set bidon = ? where uid=?";
            $resultat = $this->executerRequete($sql, array($invisible,$uid));
                
            $success = 'Consultez vos courriels';
            $panier['success'] = $success;
        }
        
        return $panier;
    }
    
  public function trouveJoueur($id)
  {
    $id = (int) $id;
    $sql = 'SELECT * FROM login l, users u WHERE l.uid = ? and u.uid = ?';
    $q = $this->executerRequete($sql, array($id,$id));
    
    if (!$q)
        die("Tables inexistantes");

    $donnees = $q->fetch(PDO::FETCH_ASSOC);

    return new Joueur($donnees);
  }

  public function exists($id)
  {
    $sql = "SELECT * FROM login where pseudo=?";
    $q = $this->executerRequete($sql, array($id));
    $donnees = $q->fetch(PDO::FETCH_ASSOC);
    $uid = $donnees['uid'];
    
    if (count($uid) == 1)
        return $uid;
    else
        return 0;
  }
  
  public function countinscrits()
  {
    $sql = 'SELECT COUNT(*) AS compteur FROM login';
    $req = $this->executerRequete($sql);
    $nb = $req->fetchColumn();
    
    return $nb;
  }

  public function countenligne()
  {
    $sql = 'SELECT COUNT(*) AS compteur FROM login WHERE connecte=1';
    $req = $this->executerRequete($sql);
    $nb = $req->fetchColumn();
    
    return $nb;
  }


  public function count($personne)
  {
    $sql = "SELECT COUNT(*) AS compteur FROM parties where finalisation=0 and (uidb=? or uidn=?)";
    $q = $this->executerRequete($sql, array($personne,$personne));
    $nb = $q->fetchColumn();

    return $nb;
  }

    public function getList()
    {
        $joueurs = array();
        $sql = 'SELECT * FROM login l,users u WHERE l.uid = u.uid order by l.elo desc, l.date_inscription, l.elo';
        $q = $this->executerRequete($sql);
        
        if (!$q)
            die("Table inexistante");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $joueurs[] = new Joueur($donnees);
        }
        
        return $joueurs;
    }
    
    public function countrecemmentconecte()
    {
        $connection = array();
        $jours = 2;
        
        $sql = "SELECT date_connection FROM users WHERE date_connection != ''";
        $q = $this->executerRequete($sql);
        if (!$q)
            die("Table users inexistante");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $temps = $donnees['date_connection'];
            
            $resultat = $this->duree_precise($temps);
            $nbjours = $resultat['jours'];
            if ($nbjours < $jours)
                $connection[] = $nbjours;
        }

        return  count($connection);
    }
    
    public function motdepasse($uidActif)
    {
        $_SESSION['uid'] = $uidActif;
        ?>
        <script type="text/javascript">
            $(document).ready((function() {
            $("#lenom").focus();
            }))			
        </script>
        <script>
            var _mouseX, _mouseY;
            document.onmousemove=function(event){getMouse(event);}
        </script>
        <div class="profil">
            <div class="icones">
                <img src="./images/icons/lock.png" alt="Mot de passe" border="0" />
                <a href="index.php?action=usager"><img src="./images/icons/user_green.png" alt="Mes informations" border="0" /></a>
                <a href="index2.php" rel="superbox[iframe][600x500]"><img src="./images/icons/photo.png" alt="Ma photo" border="0" /></a>
            </div>
            <div class="gauche">
                <b><u>Mot de passe</b></u>
                <a href="javascript:" onclick="_toggle_help('Mot de passe','Peut être utilisé pour remplacer un mot de passe temporaire, ou simplement pour un besoin de sécurité.<br /><br />Il est à noter que vous devrez confirmer le mot de passe entré en le spécifiant à deux occasions.<br /><br />La validation devra être effectuée en pressant sur le bouton <b>Modifier</b>.'); return(false);"><img src="./images/help.png" border="0" alt="aide" /></a>
                <span id="helpid" style="position: absolute; z-index: 20; display: none;"></span>
                <br /><br />Utilisez le formulaire ci-contre pour modifier votre mot de passe.
                <br /> Après modification, vous serez déconnecté et devrez vous reconnecter avec le nouveau mot de passe.
            </div>
            <form>
                <p class="error"></p>
                <input type="hidden" name="action" value="profil" />
                <p>Nouveau mot de passe:<br />
                <input required="true" id="lenom" type="password" name="pw1" size="15" maxlength="10" style="text-align: left;" value="" />
                </p>
                <p>Retapez votre mot de passe:<br />
                <input required="true" type="password" name="pw2" size="15" maxlength="10" style="text-align: left;" value="" />
                </p>
                <p>
                <input style="text-align: center; font-size: 16px;" type="submit" value="Modifier" />
            </form>
        </div>
        <?php
    }
            
    private function mysql_bind($sql)
    {
        // Génère : Hll Wrld f PHP
        $vowels = array("'");
        $onlyconsonants = str_replace($vowels, "\'", $sql);

        return $onlyconsonants;
    }


    public function traiterinfos($sexe,$pays,$naissance)
    {
        $uidActif = $_SESSION['uid'];    
        $sql = "update users set sexe = ?, pays = ?, naissance = ? where uid=?";
        $this->executerRequete($sql, array($sexe,$pays,$naissance,$uidActif));
        header('Location: ?action=profil');
        
        return;
    }

    public function traiterpassword($nouveau_mot_de_passe,$confirmation_mot_de_passe)
    {
        $uidActif = $_SESSION['uid'];

        if ($nouveau_mot_de_passe != $confirmation_mot_de_passe)
            header('Location: ?action=profil');
        else
        {
            $password_crypte = md5($nouveau_mot_de_passe);
            $sql = "update login set bidon = ? where uid=?";
            $resultat = $this->executerRequete($sql, array($password_crypte,$uidActif));
                        
            unset($password_crypte);  
            unset($reception_action);
            
            header('Location: ?action=deconnection');
        }
        
    }  // fin du isset pour le post

    protected function duree_precise($time)
    {
        $retour = array();
    
        // calcul du temps écoulé en secondes
        $diff = time() - strtotime($time);
  
        $diff_jour = floor($diff/60/60/24);
        $diff -= $diff_jour*60*60*24;
  
        $diff_heure = floor($diff/60/60);
        $diff -= $diff_heure*60*60;
  
        $diff_min = floor($diff/60);
        $diff -= $diff_min*60;
  
        $diff_sec = $diff;
  
        $temp_ecoule = $diff_jour;
  
        $retour['jours'] = $diff_jour;
        $retour['heures'] = $diff_heure;
        $retour['minutes'] = $diff_min;
        $retour['secondes'] = $diff_sec;
  
    
        return $retour;
    }

    public function updatephoto($uidActif)
    {
        $sql = "update users set photo = 'o' where uid=?";
        $this->executerRequete($sql, array($uidActif));
    }
    
    public function effacer($uid)
    {   
        $sql = "delete from login where uid=?";
        $this->executerRequete($sql, array($uid));
        $sql = "delete from users where uid=?";
        $this->executerRequete($sql, array($uid));
        $sql = "delete from statistiques where uid=?";
        $this->executerRequete($sql, array($uid));
        
        header('Location:  ?action=les joueurs');
    }
}
?>