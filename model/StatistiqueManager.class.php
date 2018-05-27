<?php
class StatistiqueManager extends Piece
{
    public static $instance;
    

    public function __construct()
    {
        self::$instance = $this;
    }
    
    public function get($uid)
    {
        $uid = (int) $uid;
        $sql = 'SELECT * FROM statistiques WHERE uid=?';
        $q = $this->executerRequete($sql, array($uid));
        if (!$q)
            die("Table statistiques inexistante");

        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        if (!$donnees)
            die("donnees inexactes");

        return new Statistique($donnees);
    }

    public function getList()
    {
        $statistiques = array();
        
        $sql = 'SELECT * FROM statistiques';
        $q = $this->executerRequete($sql);

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $statistiques[] = new Statistique($donnees);
        }
        
        return $statistiques;
    }

    public function nbpartiesjouees()
    {
        $sql = 'SELECT * FROM statistiques';
        $q = $this->executerRequete($sql);
        
        $parties_jouees = 0;
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees['gains_b'] > 0)
                $parties_jouees += $donnees['gains_b'];
            if ($donnees['pertes_b'] > 0)
                $parties_jouees += $donnees['pertes_b'];
            if ($donnees['nulles_b'] > 0)
                $parties_jouees += $donnees['nulles_b'];
        }
        
        return $parties_jouees;
    }
    
    public function formate_date($date)
    {
        $date_formatee = substr($date,8,2)."/".substr($date,5,2)."/".substr($date,0,4);
    
        if ($date == '')
            return '';
        else
            return $date_formatee;
    }

    
}

?>