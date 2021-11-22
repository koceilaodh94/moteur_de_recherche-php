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
<header id="header" style="font-style: "><br><br><br><br><h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Moteur de Recherche</h1>
		<div class="container"><br>
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
	if (isset($_POST['nom']))
{
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
	//echo  $svarmot2;
	$href="file:///";
	//$vMot = 'test';
	// Mon script
	
 
 
		$idv=mysqli_connect("localhost","root","","bdd_tiw");
		//$on=" select id FROM mot WHERE  motOrder = '$vLe'";
		$on=" select id FROM mot WHERE mot   LIKE '$svarmot1%' OR mot  LIKE '%$svarmot2' OR mot = '$tab' ";
        $resultat=mysqli_query($idv, $on) ;
		$rowcount=mysqli_num_rows($resultat);
        // $rowcount2=mysqli_num_rows($resultat2);

			if ($rowcount==0)
			{
				?>
					<h1  class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"> Aucun resultat trouver, Ce Mot n'existe pas </H1> 
				<?php 
  
				exit(0);
			}
		

		
				$reqB=" select id FROM mot WHERE mot LIKE '$svarmot1%' OR mot  LIKE '%$svarmot2'";
				$reqA=" select * FROM mot WHERE mot = '$tab' ";

				$resultat1=mysqli_query($idv, $reqA) ;
				$rowcount1=mysqli_num_rows($resultat1);
				//si le mot chercher nexiste pas 
				if ($rowcount1!=0)
				{
					$repons = $bdd->query($reqA);
				while ($donnee = $repons->fetch())
				{
					$res=$donnee['id']; 
					//echo $res; 
					//echo $donnee['mot'];	
					affichage($res); 
				}
					$repons->closeCursor(); 
					exit(0);
				}


			$repon = $bdd->query($reqB);
			while ($donnee = $repon->fetch())
			{
				$res=$donnee['id']; 
				affichage($res);
			}
				$repon->closeCursor();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////fin
}

?>
   
 </body>  
			  
			  
			  
	

			  
			  
			  
			  
			  
			  
