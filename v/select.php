<html>
<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>TIW</title>

	<!--Google Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,600" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500i" rel="stylesheet">
	<!--CSS-->
	<link rel="stylesheet" href="css/bootstrap.css">
	
</head>
<header id="header" style="font-style: "><h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Moteur de Recherche</h1>
		<div class="container"><br><br><br><br><br>
			<div class="row align-items-center justify-content-between d-flex">
				<div id="logo">
					<a href="index.php" style="padding-left:450PX"><img src="img/search.png" alt="" title="" /></a>
				</div>
		
			</div>
		</div>
		<br>
	</header>
<body>
<section class="home-banner-area relative">
		<div class="container">
			<div class="row fullscreen d-flex align-items-center justify-content-center">
				<div class="banner-content col-lg-8 col-md-12">
					<h1 class="wow fadeIn" data-wow-duration="4s">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h1>
					<p class="text-black" style="color: black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Saisissez votre recherche.
					</p>
					<div class="input-wrap">
						<form method="post" action="select.php" class="form-box d-flex justify-content-between">
							<input type="text" placeholder="Search" class="form-control" name="nom">
							<button type="submit" class="btn search-btn">Search</button>
						</form>
					</div>		
			</div>
 
        <body class="container" style='background: url(headerg.jpg)'>
              
           <?php
		   
include 'function.php';		   

if (isset($_POST['nom'])) {

$tab=$_POST['nom'];

try
{

	$bdd = new PDO('mysql:host=localhost;dbname=bdd_tiw;charset=utf8', 'root', '');
	
}
catch(Exception $e)
{
	
        die('Erreur : '.$e->getMessage());
}
 $var=strlen($tab);
 $varsur2=$var/2;
 $svarmot1= substr($tab, 0, -$varsur2);
 $svarmot2=substr($tab, -$varsur2);
 //echo $svarmot1;
 //echo("wwwwwwww");
 //echo  $svarmot2;
 $href="file:///";
 //echo $vLe;
  $idv=mysqli_connect("localhost","root","","bdd_tiw");
      //  $on=" select id FROM mot WHERE  motOrder = '$vLe'";
		       $on=" select id FROM mot WHERE mot   LIKE '$svarmot1%' OR mot  LIKE '%$svarmot2' OR mot = '$tab' ";
               
		      //  $ondeux=" select id FROM mot WHERE motOrder   LIKE '%$vLe%'";

		$resultat=mysqli_query($idv, $on) ;
				//$resultat2=mysqli_query($idv, $ondeux) ;

		 $rowcount=mysqli_num_rows($resultat);
         		// $rowcount2=mysqli_num_rows($resultat2);

 if ($rowcount==0) {
	 ?>
	  <h1  class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"> Ce Mot n'existe pas, veuillez saisir un autre </H1> 
	  <?php 
  // echo 'ce mot nest pas trouve veuilez ressayer un autre ';
   exit(0);
}
		// $cn=" select id FROM mot WHERE mot   LIKE '$svarmot1%' OR mot  LIKE '%$svarmot2' OR mot = '$tab' OR motOrder = '$vLe'";

		
 $cn=" select id FROM mot WHERE mot='$tab' OR mot LIKE  '$svarmot1%' ";


$repon = $bdd->query($cn);

while ($donnee = $repon->fetch())
{

 $res=$donnee['id']; 
   
}

$repon->closeCursor();

$cns=" select * FROM document_mot WHERE id_mot = '$res' ORDER BY poids DESC";

$reponse = $bdd->query($cns);


while ($donnees = $reponse->fetch())
{
	
     $ress =$donnees['id_document'];
 	 $cnst=" select * FROM document WHERE id = '$ress' ";
     $rep = $bdd->query($cnst);
     while ($donn = $rep->fetch())
       {echo "<br />\n";
		?><h3>
	
		<br>				  
    <strong >- Le Lien du document est : <a style=color:blue; href="<?php echo $href.$donn['document']; ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"><?php echo $donn['document']; ?></a></strong>
    		<br><br>				  
						  
 
    <strong>- Le titre du doc est : <em style="color: blue"><?php echo $donn['titre']; ?>
	<br /></em> </strong>
	<!--<em><?php echo $donnees['poids']; ?></em>-->
	</h3>
	
	<?php 
            $t_head=indexerHead($donn['document']);
			$t_body=indexerBody($donn['document']);
			$data=ajouterDOC($t_head,$t_body,$donn['document']);
         
			?>
    <div id="<?php echo $donn['document'] ?>"  >
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
   


	  