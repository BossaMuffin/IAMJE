<?php
/*
* 2015-2020 SAS COM O ZONE
*
* NOTICE OF LICENSE
*
*  @author Jean-Eudes Méhus <com@comozone.com>
*  @copyright  2015-2020 COM O ZONE
*  @license    Jean-Eudes Méhus property
*/
// on utilise cette variable pour valider l'include du fichier
$OBfuncIncludeJeton = true ;

//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste à répartir les fonctions dans plusieurs classes et renomer correctement les instances

class OBaseFunctions 
{

// Constantes
  const   KEYMAX = 6 ; // doit être supérieur ou égal à 6

// Propriété 
  // initiée dans route_serv.req.php
  public $g_url ;
  // les types possibles dépendes d'abord de la fonction php gettype
  // a chaque type correspond un type SQL (utilisé pour créer les colonnes ou on enregistrera les données)
  // et une taille maximale par défaut
  // ATTENTION il ne peut pas y avoir d'espace dans les types car ils sont utilisé pour etre les noms des tables !! XXXXXXXXXXXXXXx
  // Gérer les types none !!!!!!!!!!!!!!!
  public $p_Ttypes = [  0 => [  "name"  => "unknown type",
                                "bdd"   => "none",
                                "size"  => 0 ],

                        1 => [  "name"  => "NULL",
                                "bdd"   => "none",
                                "size"  => 0 ],

                        2 => [  "name"  => "integer", 
                                "bdd"   => "INT",
                                "size"  => 255 ],

                        3 => [  "name"  => "double", 
                                "bdd"   => "FLOAT",
                                "size"  =>  255 ],

                        4 => [  "name"  => "string", 
                                "bdd"   => "VARCHAR",
                                "size"  => 255 ],

                        5 => [  "name"  => "email", 
                                "bdd"   => "VARCHAR",
                                "size"  => 255 ],

                        6 => [  "name"  => "web", 
                                "bdd"   => "VARCHAR",
                                "size"  => 255 ],

                        7 => [  "name"  => "array", 
                                "bdd"   => "none",
                                "size"  => 0 ],

                        8 => [  "name"  => "object", 
                                "bdd"   => "none",
                                "size"  => 0 ],

                        9 => [  "name"  => "resource", 
                                "bdd"   => "none",
                                "size"  => 0 ],
                      ] ;

/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : none
* @return : none
*/
  function  __construct( )
  {
  // fin construct
  }
  // ------------------------------------

/* ------------------- CLONE ----------------------- 
* Empêche le clonage
* @value : none
* @return : none
*/
  private function  __clone()
  {
  // fin clone
  }
  // ------------------------------------



/* --------- DEFINE URL -------------------------------------------------------- 
* Fonction qui enregistre l'url de base de la page dans une propriété 
* @param url de la page
* @return charge l'url dans une propriété de la classe
*/
  function define_url( $g_url )
  {
      $this->g_url = $g_url ;
  // Fin define url
  }
  // ------------------------------------

/* --------- SHOW VAR -------------------------------------------------------- 
* Permet soit un echo pour une var scalable, soit l'appelle de la méthode printr si c'est une var non scalable 
* @param url de la page
* @return charge l'url dans une propriété de la classe
*/
  function show( $var, $pIsOpen = true, $pIsSQL = false )
  {
      if ( is_scalar( $var ) )
      {
        echo $var ;
      }
      else
      {
        $this->printr( $var, $pIsOpen = true, $pIsSQL = false ) ;
      }
  // fin show
  }
  // ------------------------------------


/* --------- PRINTR -------------------------------------------------------- 
* Fonction d'affichage préformaté de variable non typée pour nos débuggages
* @param unknow $var : variable, tableau, objet... à afficher
* @param string $pIsSQL : True => Mode affichage SQL / String => Couleur du conteneur
* @param bool $pIsOpen : True => Conteneur déplié par défaut
* @return Code HTML d'un conteneur dépliable / repliable avec scrollbar auto
*/
  function printr( $var, $pIsOpen = true, $pIsSQL = false )
  {
      $lColor = ( is_string( $pIsSQL ) ? $pIsSQL : ( $pIsSQL === true ? '#FFF5DD' : '#F2FFEE' ) ) ;
      $pIsSQL = ( $pIsSQL === true || $lColor == '#FEE' ) ;
      $var = ( $pIsSQL === true ? wordwrap( $var . ";\n", 100 ) : $var ) ;
      $lHeight = ( $pIsSQL === true ? '100px' : '200px' ) ;
      $lUniqId = uniqid( md5( rand() ) ) ;

      echo '<table  cellspacing="0" cellpadding="0" 
                    style=" width:100%;
                            border:1px dashed gray;
                            background-color:' . $lColor . ';">
        <tr>
          <td>
            <a  style=" display:block;
                        padding:4px;" 
                title="Cliquer pour ouvrir ou fermer l\'affichage détaillé"
                href="javascript:void(0);"
                onClick="var tr = document.getElementById(\'printr_' . $lUniqId . '\');
                  if (tr.style.display!=\'none\') tr.style.display = \'none\';
                  else tr.style.display = \'table-row\';"><img
                src="' . $this->g_url . 'img/sort-down.png" border="none" height="30px" />
            </a>
          </td>
        </tr>
        <tr style="display:' . ( $pIsOpen ? 'table-row':'none') .';"
          id="printr_' . $lUniqId . '"><td><textarea
          style=" padding:2 5px;
                  width:100%;
                  overflow:auto;
                  height:' . $lHeight . ';
                  background-color:transparent;
                  border:none;
                  border-top:1px dashed gray;
                  font-size:11px;
                  font-family:monospace;"
        title="Affichage avec print_r() pour debug" ' . ($pIsSQL===true ? ' onFocus="select();"' : '' ) . '>' ;

      @print_r( $var ) ;

      echo '</textarea></td></tr></table>' ;

  // fin printr
  }
  // ------------------------------------

/* -------------------------------------- VALIDATE WEB : EMAIL ou URL  --------------------------- 
* valide une url (par defo) ou un email, en fonction de mode (url ou mail)
* @param : une chaine de caractere ressemblant à un email ou une url
* @value : none
* @return : boolean ++
*/

  function validate_web( $c_var, $mode = "url" )
  {
    $reponse["err"] = 0 ;
    $reponse["val"] = "" ;
    $reponse[0] = false ;

    if ( is_string( $c_var ) )
    {

      $reponse["val"] = $c_var ;
      // retirer les caractère étrange
      // $reponse["val"] = trim($c_var, '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~') ;
      // on definit si l'on cherche à valider un email ou une url web
      if ( $mode == "url" )
      {
        // on  definit le regex URL (traite 99% des cas, y compris les email, le ftp, https, les connexions, les sous dossiers, sous domaines, variables get ...)
        $l_regex = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS' ;
      } 
      else 
      {
        // on  definit le regex EMAIL
        $l_regex = '_^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)?$_iuS' ;
      }
      
      if ( $c_var != '' ) 
      { 
        $l_string = preg_replace( $l_regex, '', $c_var ) ;

        if ( empty( $l_string ) )
        {
          $reponse[0] = true ;
        }
        else 
        {
          $reponse["err"] = 3 ;
        }

      } 
      else 
      {
        $reponse["err"] = 2 ;
      }
    }
    else
    {
      $reponse["err"] = 1 ;
    } 
   
    return $reponse ;

  // fin validate_web  
  }
  // ---------------------------------


/* -------------------------------------- GETTYPE  --------------------------- 
* determine le type du parametre
* @param : tout type
* @value : none
* @return : 
* Les chaînes de caractères que peut retourner la fonction PHP gettype (injecté dans ["val"]) sont les suivantes :
* "boolean"
* "integer"
* "double"(pour des raisons historiques, "double" est retournée lorsqu'une valeur de type float est fournie, au lieu de la chaîne "float")
* "string"
* "array"
* "object"
* "resource"
* "resource (closed)" à partir de PHP 7.2.0
* "NULL"
* "unknown type"
*/
  function get_type( $var )
  {

    $reponse["err"] = 0 ;
    $reponse["val"] = "" ;
    $reponse["subval"] = "" ;
    $reponse[0] = false ;

    $l_type = gettype( $var ) ;

    
    if ( $l_type == "string" )
    {
      if ( ! is_numeric($var) )
      {
        
        if ( $this->validate_web( $var, "mail" ) )
        {
          $reponse["subval"] = $this->p_Ttypes[6]["name"] ;
          // c'est un email calssique type nom@domaine.ext
        }
        else if ( $this->validate_web( $var ) )
        {
          $reponse["subval"] = $this->p_Ttypes[6]["name"] ;
          // c'est une adresse web qui peut être très complexe
        }

      }
      else
      {//9999999999999999999  XXXXXXXXXXXXXXXXXXXXx
        // ATTENTION : en fait il n'est pas toujours DOUBLE OU FLOTTANT, 
        // cela permet juste d'identifier une donnée du type "78" au lieu de 78
        // il faudrait faire en sorte qu'elle distingue les entiers flottant double ... 
        // même sous forme de chaine de caractère (entre guillement) car récupérer d'un "explode" depuis les GET 
        //999999999999999999999999999
        $l_type = "double" ;
      }
      
    }   
   

    $reponse["val"] = $l_type ;
    $reponse["subval"] = $l_type ;

    return  $reponse ;

  // fin get type
  }
  // ------------------------------------




/* -------------------------------------- recupere lurl appelé sans parametre --------------------------- 
* @param :
* @value : none
* @return : char $l_TurlGet[0]
*/
  function UrlSansParametres()
  {
    $l_urlCourante = $_SERVER['REQUEST_URI'] ;
    $l_TurlGet = explode( "?", $l_urlCourante ) ;
    return  $l_TurlGet[0] ;

  // fin Url sans parametre
  }
  // ------------------------------------



/* ---------------------------------- annonce si le parametre est le sous domaine de lurl appelée -------------- 
* @param :
* @value : 
* @return : booléen
*/
  function UrlSousDomaine( $sousDomaine )
  {
            
    $l_urlCourante = $_SERVER['HTTP_HOST'] ;
    $l_reponse = false ;
    $l_Ttest = explode( $sousDomaine, $l_urlCourante ) ;
    
    if ( isset( $l_Ttest[1] ) )
    {
      $l_reponse = true ;
    }
    else
    {
      $l_reponse = false ;
    }

    return  $l_reponse;

  // fin url sans parametre
  }
  // ------------------------------------


/* GENERE IA CHAR KEY -------------------------------------------------------------------- GENERE IA CHAR KEY 
* Genere une chaine de caractere lettre minuscule et majuscule et chiffre avec des char spéciaux ( -!$ )
@Input :
[option] len int : longeur de la clé (supérieur à 4!!!)

@Return: une table avec 0: BOOL 0 -> pb , 1-> ok ;
[value] : la clé demandée
et [erreur] 
*/
  function genereCharKey( $len = self::KEYMAX ) 
  {
    $l_Treponse[0] = false ;
    $l_Treponse['erreur'] = 0 ;

    $l_Treponse["value"] = "" ;
    $l_i_car = 0 ;
    $l_car = "" ;
    $l_newcar = "" ;
    $l_chaine = "!abc-def!ghi-jkl!mnp-qrs!tuv-wxy!ABC-DEF!GHI-JKL!MNO-PQR!STU-VWX!YZ1-234!567-980!" ;
    $l_chainechiffre = "123456798" ;
    $l_chainecoz = "cozCOZ" ;
    $l_chainesep = "?-?!?" ;
    srand( (double)microtime()*1000000 );

  //premier caractere de la chaine sera un chiffre

    $l_newcar = $l_chainechiffre[rand()%strlen( $l_chainechiffre )] ;
    $l_Treponse["value"] .= $l_newcar ;
    $l_i_car++ ;
    $l_car = $l_newcar ;

  //deuxieme caractere de la chaine sera un separatif
    $l_newcar = $l_chainesep[rand()%strlen( $l_chainesep )] ;
    $l_Treponse["value"] .= $l_newcar ;
    $l_i_car++ ;
    $l_car = $l_newcar ;

  // les len - 2 autres caracteres sont generés depuis $chaine dans une boucle while
    while ( $l_i_car < $len-4 ) 
    {
      $l_newcar = $l_chaine[rand()%strlen($l_chaine)] ;
      if ( $l_newcar == "-" or $l_newcar == "$" )
      {
        if ( $l_newcar != $l_car )
        {
          $l_Treponse["value"] .= $l_newcar ;
          $l_i_car++ ;
        }
      }
      else
      { 
        $l_Treponse["value"] .= $l_newcar ;
        $l_i_car++ ; 
      }
      
      $l_car = $l_newcar ;
    }
  //lavant dernier separatif
    while ( $l_i_car < $len-3 ) 
    {
      $l_newcar = $l_chainesep[rand()%strlen( $l_chainesep )] ;
      if ( $l_newcar == "-" or $l_newcar == "!" )
      {
        if ( $l_newcar != $l_car )
        {
          $l_Treponse["value"] .= $l_newcar ;
          $l_i_car++ ;
        }
      }
      else
      { 
        $l_Treponse["value"] .= $l_newcar ;
        $l_i_car++ ;
      }
      
      $l_car = $l_newcar ;
    }

  //les 3 derniers caracteres
    while ( $l_i_car < $len )
    {
      $l_newcar = $l_chainecoz[rand()%strlen( $l_chainecoz )] ;
      $l_Treponse["value"] .= $l_newcar ;
      $l_i_car++ ;
      $l_car = $l_newcar ;
    }
    $l_Treponse[0] = true ;
    return $l_Treponse;

  // fin genere char key
  }
  // ------------------------------------


/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBaseFunctions */
}