<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=2" >
	<link rel="icon" type="image/png" href="web/assets/img/logo.png" >	
    <link href="web/assets/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" >
	<link href="web/assets/css/bootstrap/bootstrap-theme.css" rel="stylesheet" type="text/css" >
	<link rel="stylesheet" media="all" href="web/assets/css/geo3x/geo3x-login.css" type="text/css" >
</head>

<body>
<?php 
	include 'web/src/dbgpw.php';
	session_start();
	/*Vérification si l'utilisateur existe*/
	if(!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		session_destroy();
		header('location:index.php');
		exit();
	}
	/*tentative de connection db*/
	try
	{
		$bdd = new PDO('mysql:host='.$server.';dbname='.$database, $db_user, $db_pass);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	
	/*on recupere les données db*/
	$stmt = $bdd->prepare('SELECT Id_Base, NomBase, DescriptionBase FROM gpwbd WHERE Id_Base = ? LIMIT 0,1');
	$stmt->bindValue(1, $_GET['ref']);
	$stmt->execute();
	$dbm = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$dbm['Id_Base']; 	$dbm['NomBase']; 	$dbm['DescriptionBase'];
	
	/* on recupere les données utilisateurs */
    // $stmtu = $bdd->prepare('SELECT Id_Client FROM gpwuser_gpw WHERE Login = ? LIMIT 0,1');
	// $stmtu->bindValue(1, $_SESSION['username']);
	// $stmtu->execute();
	// $dbmu = $stmtu->fetch(PDO::FETCH_ASSOC);
	
	// $dbmu['Id_Client'];
	$bdd = null;
	
	
	/*tentative de connection db*/
	try
	{
		$bdd = new PDO('mysql:host='.$dbm['DescriptionBase'].';dbname='.$dbm['NomBase'], $db_user, $db_pass);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	/* on recupere les données trackers en fonction de la bd choisie */
    //$stmtp = $bdd->prepare('SELECT * FROM tpositions0 WHERE Id_Client = ? LIMIT 0,1000');
	//$stmtp->bindValue(1, $dbmu['Id_Client']);
	$stmtp = $bdd->prepare('SELECT * FROM tpositions0 LIMIT 0,1000');
	$stmtp->execute();
?>
 <div class="container-fluid">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<h3 class="well">
			<center>
				<img src="web/assets/img/logo.png" class="img-rounded" width="50" height="50"/>R&eacute;sum&eacute; de la consommation de donn&eacute;es<img src="web/assets/img/logo.png" class="img-rounded" width="50" height="50"/>
			</center>
		</h3>
		<table class="table table-hover">
			<thead>
				<tr class="success">
					<td>#</td>
					<th>Tracker</th>
					<th>Nom</th>
					<th>Derni&egrave;re Date</th>
					<th>Donn&eacute;es re&ccedil;ue (mois pass&eacute; | mois en cours)</th>
					<th>Consommation estim&eacute;e (mois pass&eacute; | mois en cours)</th>
					<!--th>Données SMS (mois pass&eacute; | mois en cours)</th-->
				</tr>
			</thead>
			<tbody>
<?php	
	$coef47 = 47; $coef250 = 250; $cpt = 1;
	while($dbmp = $stmtp->fetch()){
		echo '<tr>';
			echo '<td>'.$cpt.'</td>';
			echo '<th>'.$dbmp['Pos_Id_tracker'].'</th>';
			echo '<th>'.$dbmp['Nom_tracker'].'</th>';
			echo '<th>'.$dbmp['Pos_DateTime_position'].'</th>';
			echo '<th>'.$coef47*$dbmp['NbrPosLastMonth'].' Mo | '.$coef47*$dbmp['NbrPosMois'].' Mo</th>';
			echo '<th>'.$coef250*$dbmp['NbrPosLastMonth'].' Mo | '.$coef250*$dbmp['NbrPosMois'].' Mo</th>';
			//echo '<td>'.$dbmp[''].' | '.$dbmp[''].'</td>';
		echo '</tr>';
		$cpt+=1;
	}
	$stmtp->closeCursor();
	//var_dump($dbm);
?>
			</tbody>
		</table>
	</div>
	<div class="col-md-2"></div>
 </div><
</body>
</html>