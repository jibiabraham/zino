﻿<?php
	set_include_path( '../:./' );
	
	global $water;
	global $places;
	global $page;
	
	require '../libs/rabbit/rabbit.php';
	
	Rabbit_Construct();
		
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=utf-8' );
	
	$arr = array( 
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ιωάννινα' , '0' , '0' , '1' , '2005-08-13 15:42:21' , '62.74.136.91' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Αθήνα' , '0' , '0' , '1' , '2005-08-13 15:42:41' , '62.74.136.91' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Χανιά' , '0' , '0' , '10' , '2005-08-13 15:47:53' , '213.5.74.129' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κύπρος' , '0' , '0' , '12' , '2005-08-31 22:24:56' , '83.235.249.132' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Αγρίνιο' , '0' , '0' , '10' , '2005-09-03 23:39:55' , '213.5.74.182' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ερέτρια' , '0' , '0' , '10' , '2005-09-13 11:41:44' , '213.5.74.70' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Σκύρος' , '0' , '0' , '19' , '2005-11-28 16:39:51' , '84.254.17.26' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Άργος' , '0' , '0' , '2' , '2006-07-13 20:26:24' , '213.5.89.251' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Χαλκίδα' , '0' , '0' , '58' , '2007-10-09 04:07:41' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Καρπενήσι' , '0' , '0' , '58' , '2007-10-09 04:07:52' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Άμφισσα' , '0' , '0' , '58' , '2007-10-09 04:08:01' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Λαμία' , '0' , '0' , '58' , '2007-10-09 04:08:09' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Λιβαδειά' , '0' , '0' , '58' , '2007-10-09 04:08:18' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Πολύγυρος' , '0' , '0' , '58' , '2007-10-09 04:08:26' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Βέροια' , '0' , '0' , '58' , '2007-10-09 04:08:34' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κιλκίς' , '0' , '0' , '58' , '2007-10-09 04:08:42' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κατερίνη' , '0' , '0' , '58' , '2007-10-09 04:08:58' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Σέρρες' , '0' , '0' , '58' , '2007-10-09 04:09:07' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Θεσαλλονίκη' , '0' , '0' , '58' , '2007-10-09 04:09:19' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Άγιος Νικόλαος' , '0' , '0' , '58' , '2007-10-09 04:09:41' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ηράκλειο' , '0' , '0' , '58' , '2007-10-09 04:09:49' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ρέθυμνο' , '0' , '0' , '58' , '2007-10-09 04:10:03' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Δράμα' , '0' , '0' , '58' , '2007-10-09 04:10:13' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Αλεξανδρούπολη' , '0' , '0' , '58' , '2007-10-09 04:10:24' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Καβάλα' , '0' , '0' , '58' , '2007-10-09 04:10:32' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κομοτηνή' , '0' , '0' , '58' , '2007-10-09 04:10:44' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ξάνθη' , '0' , '0' , '58' , '2007-10-09 04:10:52' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Άρτα' , '0' , '0' , '58' , '2007-10-09 04:11:00' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Πρέβεζα' , '0' , '0' , '58' , '2007-10-09 04:11:26' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ηγουμενίτσα' , '0' , '0' , '58' , '2007-10-09 04:11:34' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κέρκυρα' , '0' , '0' , '58' , '2007-10-09 04:11:43' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κεφαλλονιά' , '0' , '0' , '58' , '2007-10-09 04:11:52' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Λευκάδα' , '0' , '0' , '58' , '2007-10-09 04:12:02' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ζάκυνθος' , '0' , '0' , '58' , '2007-10-09 04:12:11' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Χίος' , '0' , '0' , '58' , '2007-10-09 04:12:19' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Λέσβος' , '0' , '0' , '58' , '2007-10-09 04:12:28' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Σάμος' , '0' , '0' , '58' , '2007-10-09 04:12:41' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Τρίπολη' , '0' , '0' , '58' , '2007-10-09 04:12:50' , '85.75.115.123' , '0' )",	
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ναύπλιο' , '0' , '0' , '58' , '2007-10-09 04:12:59' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κόρινθος' , '0' , '0' , '58' , '2007-10-09 04:13:08' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Σπάρτη' , '0' , '0' , '58' , '2007-10-09 04:13:17' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Καλαμάτα' , '0' , '0' , '58' , '2007-10-09 04:13:25' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ερμούπολη' , '0' , '0' , '58' , '2007-10-09 04:13:37' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Ρόδος' , '0' , '0' , '58' , '2007-10-09 04:13:50' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Καρδίτσα' , '0' , '0' , '58' , '2007-10-09 04:13:59' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Λάρισα' , '0' , '0' , '58' , '2007-10-09 04:14:08' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Βόλος' , '0' , '0' , '58' , '2007-10-09 04:14:17' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Τρίκαλα' , '0' , '0' , '58' , '2007-10-09 04:14:26' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Πάτρα' , '0' , '0' , '58' , '2007-10-09 04:14:35' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Μεσολόγγι' , '0' , '0' , '58' , '2007-10-09 04:14:45' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Πύργος' , '0' , '0' , '58' , '2007-10-09 04:15:01' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Φλώρινα' , '0' , '0' , '58' , '2007-10-09 04:15:12' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Γρεβενά' , '0' , '0' , '58' , '2007-10-09 04:15:24' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Καστοριά' , '0' , '0' , '58' , '2007-10-09 04:15:33' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Κοζάνη' , '0' , '0' , '58' , '2007-10-09 04:15:42' , '85.75.115.123' , '0' )",
				"INSERT INTO `merlin_places` VALUES ( '' , 'Έδεσσα' , '0' , '0' , '58' , '2007-10-09 04:22:46' , '85.75.115.123' , '0' )"	
	);
	foreach( $arr as $sql ) {
		mysql_query( $sql );
	}
	?><h2>Uni Inserts</h2>
	