<?php
class ConnectionManager extends Modele
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
        
            $sql = 'SELECT id FROM login WHERE pseudo=?';
            $q = $this->executerRequete($sql, array(':pseudo'=>$p));
            if($q->rowCount()==0)
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
        $sql = "SELECT * FROM login where pseudo=?";
        $q = $this->executerRequete($sql, array($pseudo));
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
    $sql = "SELECT * FROM login WHERE pseudo = ?";
    $q = $this->executerRequete($sql, array($pseudo));
    $donnees = $q->fetch(PDO::FETCH_ASSOC);
    $uid = $donnees['uid'];
    
    return $uid;
  }
  
  public function get($pseudo)
  {
    $sql = "SELECT * FROM login WHERE pseudo = ?";
    $q = $this->executerRequete($sql, array($pseudo));
    $donnees = $q->fetch(PDO::FETCH_ASSOC);

    return new Connection($donnees);
  }

  public function count($id)
  {
    $sql = "SELECT COUNT(*) FROM verif where id=?";
    return $this->executerRequete($sql, array($id));
  }
  
  public function getList()
  {
    $connections = array();
        
    $sql = 'SELECT * FROM verif';
    $q = $this->executerRequete($sql);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
        $connections[] = new Connection($donnees);
    }
        
    return $connections;
  }
    
  public function add($uid)
  {
    $sql = "update login set connecte = true where uid = ?";
    $q = $this->executerRequete($sql, array($uid));
    $sql = "update users set date_connection = now() where uid = ?";
    $q = $this->executerRequete($sql, array($uid));
    $sql = "insert into verif (uid) values ('$uid')";
    $q = $this->executerRequete($sql);
  }
  
  public function connection($uidActif)
  {
    $sql = 'UPDATE login SET connecte=true where uid=?';
    $q = $this->executerRequete($sql, array($uidActif));
  }
    
  public function quitter($uidActif)
  {
    $sql = 'UPDATE login SET connecte=false where uid=?';
    $q = $this->executerRequete($sql, array($uidActif));
                
    $sql = "DELETE from verif where uid=?";
    $q = $this->executerRequete($sql, array($uidActif));
        
    header('Location: ./');   
  }

}

?>