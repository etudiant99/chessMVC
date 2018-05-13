/**
 *
 * infobulles jquery plugin
 * @author Benjamin Bellantonio
 * http://benjaminb.fr
 * © 2012
 * 
 */
(function($) {
    
    // Declaration de notre plugin
    $.infobulles = function(element, options) {

        // Mise en place des options par défaut
        var defaults = {
            // Position de l'infobulle (ne,nw,se,sw)
            'position'              : 'ne',
            // Affichage du point d'intéroggation
            // au survol de l'élément
            'question_mark'         : true 
        };
        
        
        // Pour éviter la confusion avec $(this)
        // on declare plugin comme varible
        // pour l'instance de notre plugin
        var plugin = this;
        
        // On crée un objet vide qui contiendra
        // les options de notre plugin
        plugin.options = {}

        
        // Référence à l'élément jQuery que le plugin affecte
        var $element = $(element);
        // Référence à l'élément HTML que le plugin affecte
        var element = element;

        // La méthode dite "constructeur" qui sera appelée
        // lorsque l'objet sera crée
        plugin.init = function() {

            // on stocke les options dans un objet en fusionnant
            // les options par defaut et celle ajoutée en parametre
            plugin.options = $.extend({}, defaults, options);
            
            // On déclare toutes les méthodes utiles
            // à la construction du plugin
            setInfobulles();
            setStyle();
            setEvents();
            
        }

        /**
         * Méthode privée
         * Pour mettre en place le style de l'élément
         * qui sera affecté par notre plugin
         */
        var setStyle = function(){
            
            // Pour l'élément affecté
            // On ajoute une bordure
            $element.css({
                'color' : 'blue'
            });
            
            // Si l'option question_mark est a true
            // On ajoute le cursor en point d'intérogation
            if(plugin.options.question_mark){
                $element.css({
                    'cursor'        : 'pointer'
                });
            }     
        }
        
        /**
         * Méthode privée
         * Pour insérer dans le dom l'infobulle associée
         * à l'élément affecté par le plugin
         */
        var setInfobulles = function(){

            // On genere un nombre aléatoire
            var randomId = Math.floor(Math.random()*99999999999999999);
            // On assigne à l'élément un attribut contenant
            // ce nombre aléatoire
            $element.attr('data-element-id','id_'+randomId);

            // On récupere les infos à afficher dans l'infobulle
            // J'ai choisi d'ajouter aux element un attribut
            // data-info qui contiendra le texte de l'infobulle
            // exemple <h1 data-info="texte de l'infobulle ">Mon titre</h1>
            var info = $element.attr('data-info');

            // On rajoute au document l'infobulle avec comme contenu
            // les infos à afficher et en attribut contenant le nombre aléatoire
            $('body').append('<span class="infobulle_item" data-infobulle-id="id_'+randomId+'">'+info+'</span>');

        }
        
        /**
         * Méthode privée
         * Pour mettre en place l'événement lors du survol
         * de l'élément et positionner l'infobulle
         * Mise en place de l'évenement pour masquer l'infobulle
         */
        var setEvents = function(){
 
            var id;
            // Lors du survol de l'élément    
            $(element).on('mouseenter',function(){
                
                // On recupere l'attribut contenant le nombre aléatoire
                id = $(this).attr('data-element-id');
                // Et on affiche l'infobulle s'y référant
                // (le stop(true, true) sert a stopper l'animation et
                // la remettre à 0 avant de la lancer
                $('.infobulle_item[data-infobulle-id='+id+']').stop(true, true).fadeIn(100);
                
                // Lorsqu'on va deplacer notre souris au dessus de l'élément
                $(document).on('mousemove',function(e){
                    // On va positionner l'infobulle pour qu'elle suive notre souris
                    // Ici on appele une methode privée qui va se charger de cela
                    // On passe en parametre le nombre aléatoire
                    // et la position X et Y de la souris
                    setInfobullePosition(id,e.pageX,e.pageY);
                });
                
            // Lorsque la souris ne survolera plus l'élément 
            }).on('mouseleave',function(){
                // On recupere l'attribut contenant le nombre aléatoire    
                id = $(this).attr('data-element-id');
                // Et on masque l'infobulle s'y référant
                $('.infobulle_item[data-infobulle-id='+id+']').hide();
                   
                    
            });
            
            
        }
        
        /**
         * Méthode privée
         * Pour placer l'infobulle pres de la souris
         * @param id = nombre aléatoire pour associer element et infobulle
         * @param x = position de la souris sur l'axe des x
         * @param y = position de la souris sur l'axe des y
         */
        var setInfobullePosition = function(id,x,y){
            
            // On récupere les dimensions de l'infobulle que l'on doit afficher
            var infoBulleHeight = $('.infobulle_item[data-infobulle-id='+id+']').outerHeight();
            var infoBulleWidth = $('.infobulle_item[data-infobulle-id='+id+']').outerWidth();
            
            // Selon la position renseignée en parametre
            // On va définir le X et le Y de l'infobulle
            switch(plugin.options.position){
                
                case 'ne' :
                    x = x+25;
                    y = y-infoBulleHeight+150;
                    break;
                case 'se' :
                    x = x+5;
                    y = y+5;
                    break;
                case 'sw' :
                    x = x-infoBulleWidth-5;
                    y = y+5;
                    break;
                case 'nw' :
                    x = x-infoBulleWidth-5;
                    y = y-infoBulleHeight-5;
                    break;
                default :
                    x = x+5;
                    y = y-infoBulleHeight-5;
                    break;
                
            }
            
            // On assigne à l'infobulle la position souhaitée
            $('.infobulle_item').css({
                'top' : y,
                'left': x
            });
            
        }
        
       
        // On appele la méthode publique init
        // qui va se charger de mettre en place
        // toutes les méthodes de notre plugin
        // pour qu'il fonctionne
        plugin.init();

    }

    // On ajoute le plugin à l'objet jQuery $.fn
    $.fn.infobulles = function(options) {

        // Pour chacuns des élément du dom à qui on a assigné le plugin
        return this.each(function() {

            // Si le plugin n'as pas deja été assigné à l'élément
            if (undefined == $(this).data('infobulles')) {

                // On crée une instance du plugin avec les options renseignées
                var plugin = new $.infobulles(this, options);

                // on stocke une référence de notre plugin
                // pour pouvoir accéder à ses méthode publiques
                // (non utilisé dans ce plugin)
                $(this).data('infobulles', plugin);

            }

        });

    }

})(jQuery);
