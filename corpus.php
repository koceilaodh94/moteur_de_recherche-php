
<?php
include 'function.php';
//Augmentation du temps
//d'exécution de ce script
set_time_limit (900);

//dossier/copus de fichers à lire
$path= "C:/wamp/www/moteur_de_recherche/doc/";
$path2= "C:/wamp/www/moteur_de_recherche/doc/";

explorerDir($path);

function explorerDir($path)
{
	$souchaine="htm";
$souch="doc";
$s="/";
$path2=$path; 

        $folder = opendir($path);
          while($url = readdir($folder))
        {
						

			
			if (is_file($path.$url) == true) {
                 //  addtobase($url);
		echo $url, "<BR>";
         echo $path, "<BR>"; 
		 $pathUrl=$path.$url;
		 echo $pathUrl, "<BR>";
            addtobase($pathUrl);
		 }
		
		else
			if (is_dir($path.$url.$s))
            if (($url != ".") && ($url != "..")&& ($url != "...")){
			//if (strpos($url, $souch) !== FALSE){
		//if(is_dir("$path3.$url.$s")==false){
			
			$path2=$path.$url.$s;
			explorerDir($path2);  
			
		
		
			}		
}       

        closedir($folder);
}



function addtobase($pathUrl){
	
	
			$tit=indexerHead($pathUrl);
			$tit1=indexerBody($pathUrl);
		$dd=mysqli_connect("localhost","root","","bdd_tiw");

			$data=concat_body_head($tit,$tit1,$pathUrl);
			foreach ($data as $indice => $valeur) {


				 echo $indice , " = ", $valeur, '<br>';
				 $urlindex=geturl($pathUrl);
				$motindex=getmot ($indice);	
				$consr="INSERT document_mot(id_document, id_mot , poids) VALUES ('$urlindex', '$motindex', ' $valeur')" ;
	            mysqli_query($dd, $consr) ;
				 
				 }
	
}
?>

