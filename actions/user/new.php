<?php
    function ActionUserNew( tString $username, tString $password, tString $password2, tString $email ) {
    	//Grabs the info posted by user for register.
    	$username		= $username->Get();
    	$password		= $password->Get();
    	$password2		= $password2->Get();
    	$email			= $email->Get();
    	
    	$validunames = "/^[a-zA-Z0-9_]{3,}$/";
    	
    	if ( !preg_match( $validunames , $username ) ) { // If the username contains invalid characters
    		return Redirect( '?p=register&usernameinvalid=yes' ); // redirect to register, but with the usernameinvalid variable set to yes
    	}
    	
    	if ( $password == "" ) { // if the password field is blank
    		return Redirect( "?p=register&nopassword=yes&u=$username&e=$email" );
    	}
    	if ( $password != $password2 ) { // If the password one doesnt match the password typed in for the second time
    		return Redirect( "?p=register&passwordmismatch=yes&u=$username&e=$email" );
    	}
    	
    	$usercreated = MakeUser( $username , $password , $email ); //attempts to create a user entry in the database
    	switch ( $usercreated ) {
    		case 1:
    			// ok
                return Redirect( '?p=k' );
    		case 2:
    			// existing username
                return Redirect( "?p=register&usernameexists=yes&e=$email" );
    		case 3:
    			// existing e-mail
                return Redirect( "?p=register&emailexists=yes&u=$username" );
            case 5:
    			// spambot (too many registrations from the same IP during the last couple minutes)
                return Redirect( "?p=register&screwyou=yes&u=$username" );
    		default:
    			w_die( "MakeUser() return value is invalid." ) ; //Throws an exception
    	}
    }
?>
