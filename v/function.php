<?php

function trier($t){
$t = strtolower($t);  
$malistdesmotvide = fopen('listmots.txt','rb');
$tabMotsVides= fgets($malistdesmotvide);

fclose($malistdesmotvide);
$pieces= explode(",",$tabMotsVides); 
$t = str_replace($pieces, " ", $t);
$t=trim($t) ;
 // supprimer les espaces (ou dautre caracteres )au debut et fin de la chaine 

$separateurs = " &;:-_',!.'’()}{|=+<>\n";
$tab_mots_html = explode_bis ($separateurs, $t);

   $tab_mots_occurrences = array_count_values( $tab_mots_html );
   //  activer la ligne 162 151 184 jusqua 188  ET TOUT LES print_tab
      // print_tab($tab_mots_occurrences);
   
   // $jsk=implode(' ', $ta);
   // echo "LA CHAINE DES MOTS A GARDER: " .$jsk;
   // echo "<br />\n";
    return $tab_mots_occurrences ;

}

function  get_title($fichier_html)
{
	
    $separateurs = "\ ,&; -_',!.'’()<>\n";
    $chaine_html = file_get_contents($fichier_html);

    $modele = '/<title>(.*)<\/title>/si' ;

    preg_match($modele, $chaine_html, $tableau_resultat) ;
$tab_titel = explode_bis ($separateurs, $tableau_resultat[1]);
$res_tit = implode(" ", $tab_titel);
$res=trim($res_tit);

    return $res ;
}



function  trierdesc($fic)
{
	
    $separateurs = "\ ,&; -_',!.'’()<>\n";

$tab_titel = explode_bis ($separateurs, $fic);
$res_tit = implode(" ", $tab_titel);
$res=trim($res_tit);

    return $res ;
}

function get_meta_description ($nomdefichierHTML)
{

    $tableau_associatif_metas = get_meta_tags($nomdefichierHTML);
    return $tableau_associatif_metas['description'] ;   
}

function get_meta_keywords ($nomdefichierHTML)
{
	
    $tableau_associatif_metas = get_meta_tags($nomdefichierHTML);
    return $tableau_associatif_metas['keywords'] ;   
}


function  get_body($fichier_html)
{
	
    $chaine_html = file_get_contents($fichier_html);

    $modele = '/<body>(.*)<\/body>/si' ;

    preg_match($modele, $chaine_html, $tableau_resultat) ;

    return $tableau_resultat[1] ;
}



function explode_bis($separateurs, $concatenation)
{
    $tok = strtok($concatenation, $separateurs);
    if(strlen($tok)>2) $tab_mots_html[] = $tok;
    while ($tok !== false)
    {
        $tok = strtok($separateurs);
        if(strlen($tok)>2) $tab_mots_html[] = $tok;
    }
    return $tab_mots_html;
}

function print_tab($tab_mots_html)
{
    foreach ($tab_mots_html as $indice => $valeur)  echo $indice , " = ", $valeur, '<br>';
}





function  getredandance($vv)
{
	 $id=mysqli_connect("localhost","root","","bdd_tiw");
        $con=" select redondance FROM redondanceindex WHERE redondance = '$vv'";
		$resultat=mysqli_query($id, $con) ;
   $rowcount=mysqli_num_rows($resultat);
   
 if ($rowcount==0) {
    $co="INSERT redondanceindex(id, redondance)VALUES ('', '$vv')";
mysqli_query($id, $co) ;
$cnn=" select id FROM redondanceindex WHERE redondance = '$vv'";
$toto=mysqli_query($id, $cnn) ;
 $ligne=mysqli_fetch_array($toto);
 
}
else {   // actions }
	   
        $cn=" select id FROM redondanceindex WHERE redondance = '$vv'";
$toto=mysqli_query($id, $cn) ;
	$ligne=mysqli_fetch_array($toto);
}
return $ligne['id'];
}






function indexerHead($nomdefichierHTML)
{
	//1- Extraction des éléments du head à indexer
	$titre = get_title($nomdefichierHTML);
	$description = get_meta_description($nomdefichierHTML);
	$keywords = get_meta_keywords($nomdefichierHTML);

	//2- Texte du HEAD à INDEXER 
	$texte_head = $titre . " " . $description . " " . $keywords ;
	$texte_head=trim($texte_head);
	  $tit= trier ($texte_head);
	return $tit;
	

}
function indexerBody($nomdefichierHTML)
{
	//Extraction de la chaine HTML BODY
	$texte_html_body = get_body($nomdefichierHTML);
	
	//Suppression des script : javascript par exemple
	strip_scripts($texte_html_body);
		
	//Suppresion du formatage HTML
	$texte_body =  strip_tags($texte_html_body);
$texte_body=trim($texte_body);
	//3- Traitement du texte BODY  : Tokenisation, filtrage, occurrences
	$separateurs = " ,!.'’()";

	$texte = strtolower($texte_body);

$tit= trier ($texte);
return $tit;
}

function strip_scripts($chaine_html){
	$modele='/<script[^>]*?>.*?<\/script>/is';
	preg_replace ($modele, '', $chaine_html);
	
} 

function ajouterDOC($tit,$tit1,$url){
	 $data = [];
	$ti=multiplicationHead($tit);
	$fusionTableau = array_merge_recursive($ti,$tit1);
		//echo "<br />\n";
	//  echo "-----------------CONCATINATION HEAD ET BODY AVNAT DE SAUVGARDER DANS LA BASE ----------------";
	  //	echo "<br />\n";
	
 foreach (  $fusionTableau as $indice => $vaeur){
				  $id=mysqli_connect("localhost","root","","bdd_tiw");
				  if (is_array ($vaeur )){
					
				      $vaeur=array_sum($vaeur);
				   
				  }
				 //  echo "<br />\n";
				 
					//echo $indice , " = ", $vaeur, '<br>';	
					  $data += [ "$indice" => $vaeur ];
				$urlindex=geturl($url);
				$motindex=getmot ($indice);	
				//$cons="INSERT document_mot(id_mot, id_document, poids) VALUES ('$motindex', '$urlindex', ' $vaeur')" ;
	            //mysqli_query($id, $cons) ;
				  
			  }	
return $data;
}
function multiplicationHead($tit){
	$func = function($value) {
    return $value * 1.5;
};


	foreach ($tit as $indice => $valeur){
			$ti=array_map($func, $tit);
			
		
			}
			//echo "<br />\n";
			//	echo "------------le head multiplier par 1 5---------";
			//		echo "<br />\n";
			//print_tab($ti);
			//echo "<br />\n";
	return $ti;
}
function  getmot($vv)
{
	 $id=mysqli_connect("localhost","root","","bdd_tiw");
        $con=" select mot FROM mot WHERE mot = '$vv'";
		$resultat=mysqli_query($id, $con) ;
 $rowcount=mysqli_num_rows($resultat);
   
 if ($rowcount==0) {
    $co="INSERT mot(id, mot)VALUES ('', '$vv')";
mysqli_query($id, $co) ;
 $cnn=" select id FROM mot WHERE mot = '$vv'";
$toto=mysqli_query($id, $cnn) ;
 $ligne=mysqli_fetch_array($toto);
}
else {   // actions }
	   
        $cn=" select id FROM mot WHERE mot = '$vv'";
	mysqli_query($id, $cn) ;
	$toto=mysqli_query($id, $cn) ;
	$ligne=mysqli_fetch_array($toto);
}
return $ligne['id'];
}

function  geturl($vv)
{
	 $id=mysqli_connect("localhost","root","","bdd_tiw");
        $con=" select document FROM document WHERE document = '$vv'";
		$resultat=mysqli_query($id, $con) ;
   $rowcount=mysqli_num_rows($resultat);
   
 if ($rowcount==0) {
	 $titre =get_title($vv);
	$description  = get_meta_description($vv);
	 	 $key = get_meta_keywords($vv);
		  $descriptionKeyworld = $description." ".$key; 
$descriptionKeyworld1=trierdesc($descriptionKeyworld);
	 
    $co="INSERT document(id, document, titre, description)VALUES ('', '$vv', '$titre', '$descriptionKeyworld1')";
	mysqli_query($id, $co) ;
	$cnn=" select id FROM document WHERE document = '$vv'";
$toto=mysqli_query($id, $cnn) ;
 $ligne=mysqli_fetch_array($toto);
// $ligne=mysqli_num_rows($toto);
}
else {   // actions }
	   
        $cn=" select id FROM document WHERE document = '$vv'";
	$toto=mysqli_query($id, $cn) ;
	$ligne=mysqli_fetch_array($toto);
}
 return $ligne['id'];
}



















function genererNuage( $data = array() , $minFontSize = 10, $maxFontSize = 36 )
{
    $tab_colors=array("#3087F8", "#7F814E", "#EC1E85","#14E414","#9EA0AB", "#9EA414");
       
    $minimumCount = min( array_values( $data ) );
    $maximumCount = max( array_values( $data ) );
    $spread = $maximumCount - $minimumCount;
    $cloudHTML = '';
    $cloudTags = array();
     
    $spread == 0 && $spread = 1;
    //Mélanger un tableau de manière aléatoire
    srand((float)microtime()*1000000);
    $mots = array_keys($data);
    shuffle($mots);
    
    foreach( $mots as $tag )
    {   
        $count = $data[$tag];
       
        //La couleur aléatoire
        $color=rand(0,count($tab_colors)-1);
           
        $size = $minFontSize + ( $count - $minimumCount )
            * ( $maxFontSize - $minFontSize ) / $spread;
        $cloudTags[] ='<a style="font-size: '.
            floor( $size ) .
            'px' .
            '; color:' .
            $tab_colors[$color].
            '; " title="Rechercher le tag ' .
            $tag .
            '" href="rechercher.php?q=' .
            urlencode($tag) .
            '">' .
            $tag .
            '</a>';
    }
    return join( "\n", $cloudTags ) . "\n";
}   







?>