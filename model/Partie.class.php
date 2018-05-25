<?php
class Partie  extends PartieManager
{
    private $_gid;
    private $_uidb;
    private $_uidn;
    protected $_trait;
    private $_tour;
    private $_date_debut;
    private $_date_dernier_coup;
    private $_date_fin;
    private $_cadencep;
    protected $_reservep;
    protected $_reserve_uidb;
    protected $_reserve_uidn;
    private $_finalisation;
    private $_finalisationstylisee;
    private $_actif;
    private $_uidActif;
    private $_laclasse;
    private $_usercolor;
    private $_flip;
    private $_flipbase;
    private $_tempsRestant;
    private $_pourcentageABlancs;
    private $_pourcentageBBlancs;
    private $_pourcentageANoirs;
    private $_pourcentageBNoirs;
    private $_changementB;
    private $_changementN;
    protected $_reserveBlanche;
    protected $_reserveNoire;
    protected $_cliquable;
    protected $_imagemacouleur;
    protected $_adversaire;
    protected $_lesblancs;
    protected $_nbcoups;
    protected $_situation;
    protected $_ign;
    protected $_mangeaille;
    protected $_positionroiblanc;
    protected $_positionroinoir;
    protected $_roiattaque;
    protected $_mat;
    protected $_partienulle;
    protected $_nbcoupspossibles;
    private $_titre;
        
    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
        $this->setImageMaCouleur();
        $this->setTour();
        $this->setSituation();
        $this->setFlipBase();
        $this->setMat();
        $this->setPartieNulle();
        $this->setTitre();
        if (isset($donnees['trait']))
            $this->settrait($donnees['trait']);
        $this->setuidb($donnees['uidb']);
        $this->setuidn($donnees['uidn']);
    }
    
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            $this->setActif($donnees['actif']);
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method))
                $this->$method($value);
        }
    }

    public function getSituation()
    {
        return $this->_situation;
    }
    
    public function getNbCoups()
    {
        return $this->_nbcoups;
    }
    
    public function gid()
    {
        return $this->_gid;
    }
    
    public function uidb()
    {
        return $this->_uidb;
    }

    public function uidn()
    {
        return $this->_uidn;
    }
    
    public function getTrait()
    {
        return $this->_trait;
    }
    
    public function getMangeaille()
    {
        return $this->_mangeaille;
    }

    public function getNbCoupsPossibles()
    {
        return $this->_nbcoupspossibles;
    }
    public function getPositionRoiBlanc()
    {
        return $this->_positionroiblanc;
    }
    
    public function getPositionRoiNoir()
    {
        return $this->_positionroinoir;
    }
    
    public function getRoiAttaque()
    {
        return $this->_roiattaque;
    }
    
    public function getMat()
    {
        return $this->_mat;
    }
    
    public function getPartieNulle()
    {
        return $this->_partienulle;
    }
    
    private function setTitre()
    {
        $lesBlancs = $this->trouveJoueur($this->uidb());
        $lesNoirs = $this->trouveJoueur($this->uidn());
        $_SESSION['lesblancs'] = $lesBlancs->pseudo();
        $_SESSION['lesnoirs'] = $lesNoirs->pseudo();
        $contenulesBlancs = $lesBlancs->pseudo().'<br />';
        $contenulesBlancs .= 'Elo: '.$lesBlancs->elo().'<br />';
        $contenulesBlancs .= 'Age: '.$lesBlancs->age().'<br /><br />';
        $contenulesBlancs .= '<img src='.$lesBlancs->photo().'>';
        $lejoueurblanc = '<span class="infobulle" data-info="'.$contenulesBlancs.'">'.$lesBlancs->pseudoimage().'</span>';
        $contenulesNoirs = $lesNoirs->pseudo().'<br />';
        $contenulesNoirs .= 'Elo: '.$lesNoirs->elo().'<br />';
        $contenulesNoirs .= 'Age: '.$lesNoirs->age().'<br /><br />';
        $contenulesNoirs .= '<img src='.$lesNoirs->photo().'>';
        $lejoueurnoir = '<span class="infobulle" data-info="'.$contenulesNoirs.'">'.$lesNoirs->pseudoimage().'</span>';
        
        if (($this->finalisation() != 'Partie non terminée') && ($this->uidb() == $this->actif() || $this->uidn() == $this->actif()))
        {
            $phrase = '<b>Ma partie</b> #</b>&nbsp;'.$this->gid().':&nbsp;&nbsp;&nbsp;'.$lejoueurblanc.' - '.$lejoueurnoir;
            $phrase .= '<br /><br /><br />';
        }
        else if ($this->finalisation() == 'Partie non terminée')
        {
            $phrase = '<b>Partie en cours # '.$this->gid().'</b>:&nbsp;'.$lejoueurblanc.' - '.$lejoueurnoir;
            $phrase .= '<br />'.$this->getSituation().' - Cadence: '.$this->cadencep().' jours/coup<br />Réserve = '.$this->reservep().' jours';
        }
        else
        {
            $phrase = '<b>Partie terminee # '.$this->gid().'</b>:&nbsp;&nbsp;&nbsp;'.$lejoueurblanc.' - '.$lejoueurnoir;
            $phrase .= '<br />Cadence: <b>'.$this->cadencep().'</b> jours/coup, Reserve = <b>'.$this->reservep().'</b> jours <br />';
            $phrase .= 'Commencée le <b>'.$this->formate_date($this->datedebut()).'</b>, terminée le <b>'.$this->formate_date($this->datefin()).'<br />';
            $phrase .= '<b>'.$this->finalisation().'</b>, apres '.$this->getNbCoups().' coups';
        }
        $this->_titre = $phrase;
    }

    public function getTitre()
    {
        return $this->_titre;
    }
    
    public function actif()
    {
        return $this->_actif;
    }
    
    public function setFlipBase()
    {
        if ($this->getTrait() == 1)
            $this->_flipbase = 0;
        else
            $this->_flipbase = 1;
    }
    
    public function getFlipBase()
    {
        return $this->_flipbase;
    }
    
    public function setUsercolor()
    {
        $uidActif = $_SESSION['uid'];
        if ($uidActif == $this->uidb())
        {
            $this->_usercolor = 1;  // blancs
            $this->_flip = 0;       // on tourne pas l'échiquier
            $this->_adversaire = $this->uidn();
            $this->_lesblancs = $this->uidb();
        } 
        else
        {
            $this->_usercolor = 0;  // noirs
            $this->_flip = 1;       // on tourne l'échiquier
            $this->_adversaire = $this->uidb();
            $this->_lesblancs = $this->uidn();
        }
    }
    
    public function getAdversaire()
    {
        return $this->_adversaire;
    }

    public function getLesBlancs()
    {
        return $this->_lesblancs;
    }

    public function flip()
    {
        return $this->_flip;
    }

    public function getTour()
    {
        return $this->_tour;
    }

    public function datedebut()
    {
        $ladate = $this->_date_debut;
        
        return $ladate;
    }

    public function datederniercoup()
    {
        
        return $this->_date_dernier_coup;
    }

    public function datefin()
    {
        return $this->_date_fin;
    }

    public function cadencep()
    {
        return $this->_cadencep;
    }

    public function reservep()
    {
        return $this->_reservep;
    }

    public function reserve_uidb()
    {
        return $this->_reserve_uidb;
    }

    public function reserve_uidn()
    {
        return $this->_reserve_uidn;
    }

    public function finalisation()
    {
        return $this->_finalisation;
    }
    
    public function finalisationStylisee()
    {
        return $this->_finalisationstylisee;
    }
    
    public function laclasse()
    {
        return $this->_laclasse;
    }

    public function setImageMaCouleur()
    {
        if ($this->actif() == $this->_uidb)
            $image = '<img src="./public/images/white.gif">';
        else
            $image = '<img src="./public/images/black.gif">';
        
        $this->_imagemacouleur = $image;
    }
    
    public function getImageMaCouleur()
    {
        return $this->_imagemacouleur;
    }

    public function tempsRestant()
    {
        return $this->_tempsRestant;
    }
    
    public function pourcentage_a_blancs()
    {
        if ($this->_pourcentageABlancs < 0)
            $this->_pourcentageABlancs = 0;

        return number_format($this->_pourcentageABlancs,0);
    }

    public function changementB()
    {
        return $this->_changementB;
    }
    
    public function changementN()
    {
        return $this->_changementN;
    }


    public function pourcentage_a_noirs()
    {
        if ($this->_pourcentageANoirs < 0)
            $this->_pourcentageANoirs = 0;
            
        return number_format($this->_pourcentageANoirs,0);
    }

    public function cliquable()
    {
        $uidActif = $_SESSION['uid'];
        $trait = $this->getTrait(); // 1 si blanc   &  -1 si noir
        $cliquable = false;
        
        if ($trait == 1 and $this->uidb() == $uidActif)
            $cliquable = true;
        if ($trait == -1 and $this->uidn() == $uidActif)
            $cliquable = true;
            
        $this->_cliquable = $cliquable;
    }
    
    public function setMat()
    {
        $this->_mat = false;
        if ($this->getRoiAttaque())
            if ($this->getNbCoupsPossibles() == 0)
                $this->_mat = true;
    }
    
    public function setPartieNulle()
    {
        $this->_partienulle = false;
        if (!$this->getRoiAttaque())
            if ($this->getNbCoupsPossibles() == 0)
                $this->_partienulle = true;
    }
    
    public function setNbcoupspossibles($id)
    {
        $this->_nbcoupspossibles = $id;
    }
    
    private function interval()
    {
        $dernier_coup  = $this->_date_dernier_coup;
        $cadence = $this->cadencep();
        switch ($cadence)
        {
            case 1:
                return $dernier_coup.' + 1 day';
                break;
            case 2:
                return $dernier_coup.' + 2 day';
                break;
            case 3:
                return $dernier_coup.' + 3 day';
                break;
        }
    }

    public function calculVeritableJourMinuteSeconde()
    {
        $aujourdhui = new DateTime();
        $DateFin = new DateTime($this->interval());
        $DateDernierCoup = new DateTime($this->_date_dernier_coup);
        switch ($this->cadencep())
        {
            case 1:
                $temps_alloue = new DateInterval('P1D');
                break;
            case 2:
                $temps_alloue = new DateInterval('P2D');
                break;
            case 3:
                $temps_alloue = new DateInterval('P3D');
                break;
        }
        $TempsRestant = $aujourdhui->diff($DateFin);
        if($aujourdhui->sub($temps_alloue) > $DateDernierCoup)
        {
            $TempsRestant->d = -$TempsRestant->d;
            $TempsRestant->h = -$TempsRestant->h;
            $TempsRestant->i = -$TempsRestant->i;
            $TempsRestant->s = -$TempsRestant->s;
        }

        return $TempsRestant;
    }

    public function calculJourMinuteSeconde()
    {
        $aujourdhui = new DateTime();
        $DateFin = new DateTime($this->interval());
        $DateDernierCoup = new DateTime($this->_date_dernier_coup);
        switch ($this->cadencep())
        {
            case 1:
                $temps_alloue = new DateInterval('P1D');
                break;
            case 2:
                $temps_alloue = new DateInterval('P2D');
                break;
            case 3:
                $temps_alloue = new DateInterval('P3D');
                break;
        }
        $TempsRestant = $aujourdhui->diff($DateFin);
        if($aujourdhui->sub($temps_alloue) > $DateDernierCoup)
        {
            $TempsRestant->d = 0;
            $TempsRestant->h = 0;
            $TempsRestant->i = 0;
            $TempsRestant->s = 0;
        }

        return $TempsRestant;
    }

    public function getreserveBlanche()
    {
        return $this->_reserveBlanche;
    }

    public function getreserveNoire()
    {
        return $this->_reserveNoire;
    }

    public function getUserColor()
    {
        return $this->_usercolor;
    }
    
    public function getCliquable()
    {
        return $this->_cliquable;
    }
    
    public function getIgn()
    {
        return $this->_ign;
    }
    
    /*
    //
    // Setters
    //
    //
    */
    public function setGid($id)
    {
        $this->_gid = $id;
    }
        
    public function setuidb($id)
    {
        $this->_uidb = $id;
    }

    public function setuidn($id)
    {
        $this->_uidn = $id;
    }
    
    public function settrait($id)
    {
        $this->_trait = $id;
    }
    
    public function setSituation()
    {
        if ($this->getTrait() == 1)
            $this->_situation = 'trait aux blancs';
        else
            $this->_situation = 'trait aux noirs';
    }

    public function setActif($id)
    {
        $this->_actif = $id;
    }

    public function setDate_debut($id)
    {
        $this->_date_debut = $id;
    }

    public function setDate_dernier_coup($id)
    {
        $this->_date_dernier_coup = $id;
    }

    public function setDate_fin($id)
    {
        $this->_date_fin = $id;
    }

    public function setCadencep($id)
    {
        $this->_cadencep = $id;
    }

    public function setReservep($id)
    {
        $this->_reservep = $id;
    }

    public function setReserve_uidb($id)
    {
        $this->_reserve_uidb = $id;
    }

    public function setReserve_uidn($id)
    {
        $this->_reserve_uidn = $id;
    }
    
    public function setNbCoups($id)
    {
        $this->_nbcoups = $id;
    }

    public function setLaclasse($id)
    {
        $this->_laclasse = $id;
    }
    
    public function setLescoups($id)
    {
        $this->_ign = $id;
    }
    
    public function setMangeaille($id)
    {
        $this->_mangeaille = $id;
    }
    
    public function setPositionroiblanc($id)
    {
        $this->_positionroiblanc = $id;
    }

    public function setPositionroinoir($id)
    {
        $this->_positionroinoir = $id;
    }
    
    public function setRoiAttaque($id)
    {
        $this->_roiattaque = $id;
    }
    
    public function setTour()
    {
        $changement_b = 0;
        $changement_n = 0;
        $this->_pourcentageABlancs = 0;
        $this->_pourcentageANoirs = 0;
        $trait = $this->getTrait(); // 1 blancs  -1 noirs
        $TempsRestant = $this->calculJourMinuteSeconde();
        $tempsRestantVeritable = $this->calculVeritableJourMinuteSeconde();
        $this->Cliquable();
        $this->setUsercolor();
        $secondesJours = $tempsRestantVeritable->d*24*3600;
        $secondesHeures = $tempsRestantVeritable->h*3600;
        $secondesMinutes = $tempsRestantVeritable->i*60;
        $secondes = $tempsRestantVeritable->s;
        $temps_maximum_en_secondes = $secondesJours+$secondesHeures+$secondesMinutes+$secondes;
        $nombre_secondes_maintenant = time();
        $reste = $temps_maximum_en_secondes;
        $rnombre_jours_restant = $temps_maximum_en_secondes/3600/24; // nous avons un nombre de secondes divisé par 3600 puis par 24
        $letemps = $TempsRestant->d.'j '.$TempsRestant->h.'h '.$TempsRestant->i.'min '.$TempsRestant->s.'s';
        if($letemps != '0j 0h 0min 0s')
        {
            $this->_reserveBlanche = $this->reserve_uidb();
            $this->_pourcentageABlancs = round($this->_reserveBlanche/$this->reservep()*100,2);
            $this->_reserveNoire = $this->reserve_uidn();
            $this->_pourcentageANoirs = round($this->_reserveNoire/$this->reservep()*100,2);
        }
        else
        {
            switch ($trait)
            {
                case 1:
                    $changement_b = $this->reserve_uidb() + $rnombre_jours_restant;
                    $this->_changementB = $changement_b;
                    $this->_reserveBlanche = $this->reserve_uidb() + $rnombre_jours_restant;
                    $this->_pourcentageABlancs = round($this->_reserveBlanche/$this->reservep()*100,2);
                    $this->_reserveNoire = $this->reserve_uidn();
                    $this->_pourcentageANoirs = round($this->reserve_uidn()/$this->reservep()*100,2);
                    break;
                case -1:
                    $changement_n = $this->reserve_uidn() + $rnombre_jours_restant;
                    $this->_changementN = $changement_n;
                    $this->_reserveBlanche = $this->reserve_uidb();
                    $this->_pourcentageABlancs = round($this->reserve_uidb()/$this->reservep()*100,2);
                    $this->_reserveNoire = $this->reserve_uidn() + $rnombre_jours_restant;
                    $this->_pourcentageANoirs = round($this->_reserveNoire/$this->reservep()*100,2);
                    break;
            }
        }
        if ($this->getCliquable())
            switch ($trait)
            {
                case 1:
                    if ($this->_pourcentageABlancs > 0)
                        $this->_tour = '<a href="?action=montrer partie&amp;gid='.$this->gid().'">à vous de jouer</a>';
                    else
                        $this->_tour = '<a href="?action=terminer partie&amp;gid='.$this->gid().'">partie terminée</a>';
                    break;
                case -1:
                    if ($this->_pourcentageANoirs > 0)
                        $this->_tour = '<a href="?action=montrer partie&amp;gid='.$this->gid().'">à vous de jouer</a>';
                    else
                        $this->_tour = '<a href="?action=terminer partie&amp;gid='.$this->gid().'">partie terminée</a>';
                    break;
            }
        else
            $this->_tour = '<a href="?action=montrer partie&amp;gid='.$this->gid().'">à votre adversaire</a>';
    }
    
    public function setFinalisationStylisee($id)
    {
        switch ($id)
        {
            case 0:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Partie non terminée</a>';
                break;
            case 1:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Nulle sur proposition des blancs</a>';
                break;
            case 2:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Nulle sur proposition des noirs</a>';
                break;
            case 3:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Les blancs abandonnent</a>';
                break;
            case 4:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Les noirs abandonnent</a>';
                break;
            case 5:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Les blancs gagnent au temps</a>';
                break;
            case 6:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Les noirs gagnent au temps</a>';
                break;
            case 7:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Les blancs gagnent(mat)</a>';
                break;
            case 8:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Les noirs gagnent(mat)</a>';
                break;
            default:
                $this->_finalisationstylisee = '<a href="?action=rejouer&amp;&gid='.$this->gid().'">Fin de partie anormale</a>';
        }
    }

    public function setFinalisation($id)
    {
        $this->setFinalisationStylisee($id);
        switch ($id) 
        {
            case 0:
                $la_fin = "Partie non terminée";
                break;
            case 1:
                $la_fin = "Nulle sur proposition des blancs";
                break;
            case 2:
                $la_fin = "Nulle sur proposition des noirs";
                break;
            case 3:
                $la_fin = "Les blancs abandonnent";
                break;
            case 4:
                $la_fin = "Les noirs abandonnent";
                break;
            case 5:
                $la_fin = "Les blancs gagnent au temps";
                break;
            case 6:
                $la_fin = "Les noirs gagnent au temps";
                break;
            case 7:
                $la_fin = "Les blancs gagnent(mat)";
                break;
            case 8:
                $la_fin = "Les noirs gagnent(mat)";
                break;
            default:
                $la_fin = "Fin de partie anormale";
        }

        $this->_finalisation = $la_fin;
    }

}
?>