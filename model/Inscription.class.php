<?php
class Inscription extends Modele
{
    public function valide_infos($pseudo,$courriel)
    {
        $valeur_pseudo = "";
        $valeur_confirmation = "";
        $type_pseudo = "hidden";
        $type_confirmation = "hidden";
        $statut_pseudo = true;
        $helo_address = "mail.vif.ca";
        $verifierdomaine = true;
        $verifieradresse = true;
        $adresseretour = "etudiant@vif.ca";
        $erreursretournees = false;

        $essai = new Courriel;
        $resultats = array();

        // 4 Vérifications pour le pseudo
        if ($pseudo == "")
        {
            $valeur_pseudo = 'entrez le pseudo';
            $statut_pseudo = false;
        }
        else if (strlen($pseudo) < 5)
        {
            $valeur_pseudo = 'pseudonyme trop court';
            $statut_pseudo = false;
        }
        else if (strlen($pseudo) > 20)
        {
            $valeur_pseudo = 'pseudonyme trop long';
            $statut_pseudo = false;
        }
        else
        {
            // Vérification des informations
            $sql = "select count(*) as nombre from login where pseudo = ?";
            $resultat = $this->executerRequete($sql, array($pseudo));
            $ligne = $resultat->fetch();
            $nombre = $ligne['nombre'];

            if ($nombre > 0)
            {
                $valeur_pseudo = "pseudonyme déjà présent";
                $statut_pseudo = false;
            }
        }

        // vérification pour le courriel
        if ($courriel == "")
        {
            $valeur_confirmation = "entrez le courriel";
            $statut_pseudo = false;
        }  

        if ($courriel != "")
        {
            $bonne_adresse = $this->VerifierAdresseMail($courriel);
            if ($bonne_adresse == false)
            {
                $valeur_confirmation = "format entré incorrect";
                $statut_pseudo = false;
            }
            else
            {
                $sql = "select count(*) as nombre from users where courriel = ?";
                $resultat = $this->executerRequete($sql, array($courriel));
                $ligne = $resultat->fetch();
                $nombre = $ligne['nombre'];
                if ($nombre > 0)
                {
                    $valeur_confirmation = "courriel déjà utilisé";
                    $statut_pseudo = false;
                }
            }
        }    
    
        if ($valeur_confirmation != "")
            $type_confirmation = "text";
    
        if ($valeur_pseudo != "")
            $type_pseudo = "text";
    
        $resultats['pseudo'] = $valeur_pseudo;
        $resultats['confirmation'] = $valeur_confirmation;
        $resultats['type_pseudo'] = $type_pseudo;
        $resultats['type_confirmation'] = $type_confirmation;
        $resultats['pseudo_ok'] = $statut_pseudo;
    
        return $resultats;
    }

    public function VerifierAdresseMail($adresse) 
    {
        $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
        if (preg_match($Syntaxe,$adresse))
            return true;    
        else
            return false;  
    }

    public function Genere_Password($size)
    {
        $password = '';        
        
        // Initialisation des caractères utilisables
        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        
        for($i=0;$i<$size;$i++){
            $password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        }
        
        return $password;
    }

    public function EnvoiCourriel($adresse_email,$nom_utilisateur,$pascrypte)
    {
        // Preparation du mail
        $nom = "Adminstrateur du site";
        $mail = "denis@etudiant99.com";

        // voici la version Mine 
        $headers = "MIME-Version: 1.0\r\n"; 

        // ici on détermine le mail en format text 
        $headers .= "Content-type: text/html; charset=UTF-8\r\n"; 
        
        $headers .= "From: $nom <$mail>\r\nX-Mailer:PHP";
        // dans le From tu mets l'expéditeur du mail 

        $sujet="Validation inscription"; 
        $subject="$sujet"; 
        
        $destinataire=  $nom_utilisateur.' <'.$adresse_email.'>';

        $adresse = $_SERVER['PHP_SELF'];
        $adresseServeur = $_SERVER['SERVER_ADDR'];

        $corps = "<p>Bonjour, ".$nom_utilisateur."</p>";
        $corps .= "<p>Votre inscription au site <b><u>jouezauxechecs</b></u> a été réalisée.<br />";
        $corps.= "Un mot de passe temporaire vous est envoyé maintenant.<br />";
        $corps.= "Il permettra de vous connecter au site, et de jouer des parties d'échecs.</p>";
        
        $corps.= "<p>Je veux vous préciser que ce mot de passe est connu de vous seul.<br />";
        $corps.= "Il a été créé automatiquement. Alors, même l'administrateur du site<br />";
        $corps.= "ne connaît pas ce mot de passe qui vous a été envoyé.</p>";
        
        $corps.= "<p>De plus, ce mot de passe temporaire a été ajouté, automatiquement, à une <br />";
        $corps.= "base de données, mais de facon cryptée, donc illisible par l'administrateur aussi.</p>";
        
        $corps.= "<p>Alors, pour vous connecter au site,</p>";
        
        $corps.="<p>&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Pseudo:</b></u>  ".$nom_utilisateur."</p>";
        $corps.="<p>&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Mot de passe:</u></b>  ".$pascrypte."</p>";
        
        //$corps.='<p><a href="http://etudiant99.com/jouezauxechecs/">Jouer aux echecs</a></p>';
        $corps.='<p><a href="http://localhost/jouezauxechecs/">Jouer aux echecs</a></p>'; // en local
        $corps.="<p>Vous pouvez modifier votre mot de passe par le menu <b><u>profil</b></u>.<br />";
        $corps.="Vous serez déconnecté et devrez vous reconnecter avec le nouveau mot de passe "."</p>";
        $corps.="<p>::::::::::::::: MAIL AUTOMATIQUE - NE PAS Y RÉPONDRE :::::::::::::::</p>";

        mail($destinataire,$subject,$corps,$headers);       
        
        Return;
    }

    public function enregistrer_joueur($pseudo,$courriel)
    {
        $panier = $this->valide_infos($pseudo,$courriel);
        $valeur_pseudo = $panier['pseudo'];
        $valeur_confirmation = $panier['confirmation'];
        $type_pseudo = $panier['type_pseudo'];
        $type_confirmation = $panier['type_confirmation'];
        $statut_pseudo = $panier['pseudo_ok'];

        if (($statut_pseudo == true) &&($type_confirmation == "hidden")){
            $le_sexe_personne = 'h';
            
            $sql = "insert into users (sexe, courriel, date_inscription) 
            values ('$le_sexe_personne', '$courriel', now())";
            $this->executerRequete($sql);
            
            $sql = "select uid from users order by uid desc";
            $resultat = $this->executerRequete($sql);
            $ligne = $resultat->fetch();
            $mon_uid = $ligne['uid'];      

            $secret = $this->Genere_Password(8);
            $invisible =  md5($secret);
            
            $sql = "insert into login (uid, pseudo, bidon, date_inscription) values ($mon_uid, '$pseudo', '$invisible', now())";
            $this->executerRequete($sql);
            
            $sql = "insert into statistiques (uid) values ($mon_uid)";
            $this->executerRequete($sql);

            $this->EnvoiCourriel($courriel,$pseudo,$secret);
            
        }

        return $panier;
    }
}
?>