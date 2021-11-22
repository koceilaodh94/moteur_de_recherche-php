<?php

function trier($texte){
$texte = strtolower($texte); 
 $text=trim($texte);
 $texte1=preg_replace("#\n|\t|\r#"," ",$text);
  $texte2=preg_replace("#;,.#"," ",$texte1);
 
$malistdesmotvide = fopen('listmots.txt','rb');
$tabMotsVides= fgets($malistdesmotvide);

fclose($malistdesmotvide);
$pieces= explode(",",$tabMotsVides); 
$texte3 = str_replace($pieces, " ", $texte2);

$separateurs = " &\t;:-_',!.'’();}{|=+<>\n\r";
$tab_mots_html = explode_bis ($separateurs, $texte3);

   $tab_mots_occurrences = array_count_values( $tab_mots_html );
  
    return $tab_mots_occurrences ;

}

function  get_title($fichier_html)
{
	$separateurs = " &; -_',!.'’()<>\n";
    $chaine_html = file_get_contents($fichier_html);

    $modele = '/<title>(.*)<\/title>/si' ;

    preg_match($modele, $chaine_html, $tableau_resultat) ;
    $tab_titel = explode_bis ($separateurs, $tableau_resultat[1]);
     $res_tit = implode(" ", $tab_titel);
    $value = trim($res_tit);
    return $value ;
}


function  traiter_description($tab)
{
	 $separateurs = " &; -_',!.'’()<>\n";
     $tab_titel = explode_bis ($separateurs, $tab);
     $res_tit = implode(" ", $tab_titel);
     $value = trim($res_tit);
     return $value ;
}



function get_meta_description ($nomdefichierHTML)
{

    $tableau_associatif_metas = get_meta_tags($nomdefichierHTML);
    return $tableau_associatif_metas['description'];   
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


function indexerHead($nomdefichierHTML)
{
	
	$titre = get_title($nomdefichierHTML);
	$description = get_meta_description($nomdefichierHTML);
	$keywords = get_meta_keywords($nomdefichierHTML);
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

	//3- Traitement du texte BODY  : Tokenisation, filtrage, occurrences
	

	$texte = strtolower($texte_body);

    $body= trier ($texte);
    return $body;
}

function strip_scripts($chaine_html){
	$modele='/<script[^>]*?>.*?<\/script>/is';
	preg_replace ($modele, '', $chaine_html);
	
} 

function concat_body_head($tit,$tit1,$url){
	 $data = [];
	$ti=multiplicationHead($tit);
	$fusionTableau = array_merge_recursive($ti,$tit1);
		
	
 foreach (  $fusionTableau as $indice => $vaeur){
				  $id=mysqli_connect("localhost","root","","bdd_tiw");
				  if (is_array ($vaeur )){
					
				      $vaeur=array_sum($vaeur);
				   
				  }
				
					  $data += [ "$indice" => $vaeur ];
			
				  
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
else {  
	   
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
	$description = get_meta_description($vv);
	$keywords = get_meta_keywords($vv);
	$desc=$description . " " . $keywords ;
	$descFinal=traiter_description($desc);
	$titre =get_title($vv);
    $co="INSERT document(id, document, titre, description)VALUES ('', '$vv', '$titre', '$descFinal')";
	mysqli_query($id, $co) ;
	$cnn=" select id FROM document WHERE document = '$vv'";
    $toto=mysqli_query($id, $cnn) ;
    $ligne=mysqli_fetch_array($toto);

}
else {   
	   
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

function affichage($res){
	 $href="file:///";
	$bdd = new PDO('mysql:host=localhost;dbname=bdd_tiw;charset=utf8', 'root', '');

$cns=" select * FROM document_mot WHERE id_mot = '$res' ORDER BY poids DESC";

$reponse = $bdd->query($cns);


while ($donnees = $reponse->fetch())
{
	
     $ress =$donnees['id_document'];
 	 $cnst=" select * FROM document WHERE id = '$ress' ";
     $rep = $bdd->query($cnst);
     while ($donn = $rep->fetch())
     {
		   echo "<br />\n";
		?>
		
	<br><br>
	<h3>
    <br>
    <strong style=color:blue;>-  <a href="<?php echo $href.$donn['document']; ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"><?php echo $donn['document']; ?></a></strong> 
   
    - Le titre du document est : <em><?php echo $donn['titre']; ?>
	<br /> </em> 
	
	
	</h3>
	
	<?php 
            $t_head=indexerHead($donn['document']);
			$t_body=indexerBody($donn['document']);
			$data=concat_body_head($t_head,$t_body,$donn['document']);
         
			?>
	<button id="a1" type="button" onclick="on_affiche('<?php echo $donn['document']; ?>')">afficher son nuage de mot</button>

    <div id="<?php echo $donn['document'] ?>" style="display:none" >
       <?php echo genererNuage( $data );
			?>
    </div>    
   <?php
					 		 
  }
          $rep->closeCursor();	 
  
 }

$reponse->closeCursor();
}
?>
<script language="javascript" type="text/javascript">
    function on_affiche(id) {
              let d1= document.getElementById(id);
                if(getComputedStyle(d1).display != "none"){
    d1.style.display = "none";
  } else {
    d1.style.display = "block";
  }
                   
               
    }
</script>     
