<?php
class Echiquier extends JoueurManager
{
    protected $_position = array();
    protected $_pieces = array('piece' => array(), 'couleur' => array(), 'endroit' => array());
    protected $_nombrepiecesblanches;
    protected $_nombrepiecesnoires;
    protected $_nbcases;
    protected $_contenucase;
    protected $_trait;
    protected $_bascule;
    protected $_lastmove;
    private $_positiondepart;
    protected $_promotion;
    private $_situationpriseenpassant;
    
    public function __construct()
    {
        $this->positiondepart();
        //$this->piecesEchiquier();
    }

    public function getPositionDepart()
    {
        return $this->_position;
    }

    public function positiondepart()
    {            
        $this->_lastmove = '';
        $position = array();
        
        $position[0] = 'R';
        $position[1] = 'N';
        $position[2] = 'B';
        $position[3] = 'Q';
        $position[4] = 'K';
        $position[5] = 'B';
        $position[6] = 'N';
        $position[7] = 'R';
        
        for ($i=8;$i<16;$i++) $position[$i]  = 'P';
        for ($i=16;$i<48;$i++) $position[$i] = '';
        for ($i=48;$i<56;$i++) $position[$i] = 'p';
        
        $position[56] = 'r';
        $position[57] = 'n';
        $position[58] = 'b';
        $position[59] = 'q';
        $position[60] = 'k';
        $position[61] = 'b';
        $position[62] = 'n';
        $position[63] = 'r';

        for ($i=0;$i<64;$i++)
        {
            $this->_position[$i] = $position[$i];
        }
    }

    public function piecesEchiquier()
    {   
        $pieces = array('1' => array(),'0' => array());
        $endroit = array();
        $couleur = array();
        $retour = array();
        $position = $this->_position;
        
        for ($i=0;$i<64;$i++)
        {
            if ($position[$i] != '')
            {
                if ($position[$i] == strtoupper($position[$i]))
                    $pieces['1'][$i] = $position[$i];
                else
                    $pieces['0'][$i] = $position[$i];
            }
        }
        $this->_nombrepiecesblanches = count($pieces['1']);
        $this->_nombrepiecesnoires = count($pieces['0']);
    }

    public function position()
    {
        return $this->_position;
    }
    
    private function contenuCase($indice)
    {
        $position = $this->position();
        return $position[$indice];
    }
    
    public function getSituationPriseEnPassant()
    {
        return $this->_situationpriseenpassant;
    }
    
    public function NombrePiecesBlanches()
    {
        return $this->_nombrepiecesblanches;
    }
    
    public function NombrePiecesNoires()
    {
        return $this->NombrePiecesNoires();
    }
        
    public function dateenlettres($date)
    {
        switch(substr($date,5,2))
        {
            case '01':
                $mois = 'janvier';
                break;
            case '02':
                $mois = 'février';
                break;
            case '03':
                $mois = 'mars';
                break;
            case '04':
                $mois = 'avril';
                break;
            case '05':
                $mois = 'mai';
                break;
            case '06':
                $mois = 'juin';
                break;
            case '07':
                $mois = 'juillet';
                break;
            case '08':
                $mois = 'août';
                break;
            case '09':
                $mois = 'septembre';
                break;
            case '10':
                $mois = 'octobre';
                break;
            case '11':
                $mois = 'novembre';
                break;
            case '12':
                $mois = 'décembre';
                break;
            default:
                $mois = '';
        }
        
        $dateformatee = substr($date,8,2).' '.$mois;
        
        return $dateformatee;
    }

    public function moveToText($move)
    {
        $sortie = '';
        $ligne = $this->parseInt($move/8);
	    $colonne = $move - $ligne*8;
        
        if($colonne == 0) $sortie .= 'a';
        else if($colonne == 1) $sortie .= 'b';
        else if($colonne == 2) $sortie .= 'c';
        else if($colonne == 3) $sortie .= 'd';
        else if($colonne == 4) $sortie .= 'e';
        else if($colonne == 5) $sortie .= 'f';
        else if($colonne == 6) $sortie .= 'g';
        else if($colonne == 7) $sortie .= 'h';
        
        $ligne ++;
        $sortie .= $ligne;
        
        return $sortie;
    }
    
    public function formate_date($date)
    {
        $date_formatee = substr($date,8,2)."/".substr($date,5,2)."/".substr($date,0,4);
    
        if ($date == '')
            return '';
        else
            return $date_formatee;
    }
    
    public function getPromotion()
    {
        return $this->_promotion;
    }

    public function Lastmove()
    {
        return $this->_lastmove;
    }
    
    public function Montrait()
    {
        return $this->_trait;
    }
    
    public function setPromotion($id)
    {
        $this->_promotion = $id;
    }

}
?>