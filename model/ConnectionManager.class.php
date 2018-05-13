<?php

class ConnectionManager
{
    public function valide_infos($pseudo,$password)
    {
        $valeur_pseudo = "";
        $valeur_confirmation = "";
        $type_pseudo = "hidden";
        $type_confirmation = "hidden";
        $statut_pseudo = true;
        
        // Vérifications pour le pseudo
        if ($pseudo == "")
        {
            $valeur_pseudo = 'champ obligatoire';
            $statut_pseudo = false;
        }
        else
        {
            $existe = $this->exists($pseudo);
            if ($existe == '0')
            {
                $valeur_pseudo = 'pseudo introuvable';
                $statut_pseudo = false;
            }
            else
            {
                // Vérifications pour le mmot de passe
                if (md5($password) != $existe)
                {
                    $valeur_confirmation = 'mot de passe incorrect';
                    $statut_pseudo = false;
                }
            }
        }
        // Vérifications pour le mmot de passe
        if ($password == "")
        {
            $valeur_confirmation = 'champ obligatoire';
            $statut_pseudo = false;
        }
        
        if ($valeur_pseudo != "")
            $type_pseudo = "text";
        if ($valeur_confirmation != "")
            $type_confirmation = "text";
            
        $resultats['pseudo'] = $valeur_pseudo;
        $resultats['confirmation'] = $valeur_confirmation;
        $resultats['type_pseudo'] = $type_pseudo;
        $resultats['type_confirmation'] = $type_confirmation;
        $resultats['pseudo_ok'] = $statut_pseudo;
        
        return $resultats;
    }
    
    public function check_pseudo($p)
    {
        if(!empty($p))
        {
            $p = strip_tags($p);
        
            $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
            $req = $bdd->prepare('SELECT id FROM login WHERE pseudo=:pseudo');
            $req->execute(array(':pseudo'=>$p));
            if($req->rowCount()==0)
                echo true;
            else
                echo false;
        }
        else
            echo 'no';
    }
    
    public function connecter_joueur($pseudo,$password)
    {
        $panier = $this->valide_infos($pseudo,$password);        
        $statut_pseudo = $panier['pseudo_ok'];
        
        if ($statut_pseudo == true)
        {
            $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
            $uid = $this->getId($pseudo);
            $_SESSION['uid'] = $uid;
            $_SESSION['pseudo'] = $pseudo;
            $this->add($uid);
        }
        
        return $panier;    
    }
  
  public function exists($pseudo)
  {
    try 
    {
        $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
        $requete = "SELECT * FROM login where pseudo='".$pseudo."'";
        $q = $db->query($requete);
        if (!$q)
            die("Table login inexistante");

        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        $bidon = $donnees['bidon'];
        
        if (count($bidon) == 1)
            return $bidon;
        else
            return '0';
    }
    catch (PDOException $e)
    {
        die("Impossibilité d'accéder à la base de données<br/>");
    }
  }
  
  public function getId($pseudo)
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $requete = "SELECT * FROM login WHERE pseudo = '$pseudo'";
    $q = $db->query($requete);
    $donnees = $q->fetch(PDO::FETCH_ASSOC);
    $uid = $donnees['uid'];
    
    return $uid;
  }
  
  public function get($pseudo)
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $requete = "SELECT * FROM login WHERE pseudo = '$pseudo'";
    $q = $db->query($requete);
    $donnees = $q->fetch(PDO::FETCH_ASSOC);

    return new Connection($donnees);
  }

  public function count($id)
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $requete = "SELECT COUNT(*) FROM verif where id=$id";

    return $db->query($requete)->fetchColumn();
  }
  
  public function getList()
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $connections = array();
        
    $requete = 'SELECT * FROM verif';
    $q = $db->query($requete);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
        $connections[] = new Connection($donnees);
    }
        
    return $connections;
  }
    
  public function add($uid)
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $requete = "update login set connecte = true where uid = $uid";
    $q = $db->query($requete);
    $requete = "update users set date_connection = now() where uid = $uid ";
    $q = $db->query($requete);
    $requete = "insert into verif (uid) values ('$uid')";
    $q = $db->query($requete);
  }
  
  public function connection($uidActif)
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $requete = 'UPDATE login SET connecte=true where uid='.$uidActif;
    $q = $db->query($requete);
    
  }
    
  public function quitter($uidActif)
  {
    $db = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
    $requete = 'UPDATE login SET connecte=false where uid='.$uidActif;
    $q = $db->query($requete);
        
    $requete = 'UPDATE login SET connecte=false where uid='.$uidActif;
    $q = $db->query($requete);
        
    $requete = "DELETE from verif where uid=$uidActif";
    $q = $db->query($requete);
        
    header('Location: ./');   
  }

}

?>