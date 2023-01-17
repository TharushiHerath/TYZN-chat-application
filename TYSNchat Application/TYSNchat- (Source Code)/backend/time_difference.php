<?php
	function time_diff($timestamp){
		$time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        
        $seconds = $time_difference;  

        $minutes = round($seconds / 60 ); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  

        if ($seconds <= 60) {
        	return "Just Now";
        }

        else if ($minutes <= 60) {
        	if ($minutes == 1) {
        		return "1 min";
        	}
        	else{
        		return "$minutes mins";
        	}
        }

        else if ($hours <= 24) {
        	if($hours==1) {  
		       return "1 hr";  
		    }  
		    else {  
		       return "$hours hrs";  
		    }  
        }
        else if($days <= 7) {  
	     	if($days==1) {  
	       		return "yesterday";  
	     	}  
	        else {  
	       		return "$days days";  
	     	}  
	   	}

		//4.3 == 52/12
	   	else if($weeks <= 4.3) {  
	     	if($weeks==1) {  
	       		return "a week";  
	     	}  
	        else {  
	       		return "$weeks weeks";  
	     	}  
	   	}

	   	else if($months <=12) {  
	     	if($months==1) {  
	       		return "a month ago";  
	     	}  
	        else {
	       		return "$months months ago";  
	     	}  
	   	}
	   	else {  
	     	if($years==1) {  
	       		return "1 year ago";  
	     	}  
	        else {  
	       		return "$years years ago";  
	     	}  
	   	} 

	}

	function time_joined($timestamp){
		strtotime($timestamp);

	}

	function time_diff_notf($timestamp){
		$time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        
        $seconds = $time_difference;  

        $minutes = round($seconds / 60 ); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  

        if ($seconds <= 60) {
        	return "Just Now";
        }

        else if ($minutes <= 60) {
        	if ($minutes == 1) {
        		return "1 minute ago";
        	}
        	else{
        		return "$minutes minutes ago";
        	}
        }

        else if ($hours <= 24) {
        	if($hours==1) {  
		       return "1 hour ago";  
		    }  
		    else {  
		       return "$hours hours ago";  
		    }  
        }
        else if($days <= 7) {  
	     	if($days==1) {  
	       		return "yesterday";  
	     	}  
	        else {  
	       		return "$days days ago";  
	     	}  
	   	}

		//4.3 == 52/12
	   	else if($weeks <= 4.3) {  
	     	if($weeks==1) {  
	       		return "one week ago";  
	     	}  
	        else {  
	       		return "$weeks weeks ago";  
	     	}  
	   	}

	   	else if($months <=12) {  
	     	if($months==1) {  
	       		return "one month ago";  
	     	}  
	        else {
	       		return "$months months ago";  
	     	}  
	   	}
	   	else {  
	     	if($years==1) {  
	       		return "1 year ago";  
	     	}  
	        else {  
	       		return "$years years ago";  
	     	}  
	   	} 

	}

	function time_diff_activity($timestamp){
		$time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        
        $seconds = $time_difference;  

        $minutes = round($seconds / 60 ); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  

        if ($seconds <= 60) {
        	return "Now";
        }
        else if ($minutes <= 60) {
        	if ($minutes == 1) {
        		return "1 m";
        	}
        	else{
        		return "$minutes m";
        	}
        }

        else if ($hours <= 24) {
        	if($hours==1) {  
		       return "1 h";  
		    }  
		    else {  
		       return "$hours h";  
		    }  
        }
        else if($days <= 7) {  
	     	if($days==1) {  
	       		return "1 d";  
	     	}  
	        else {  
	       		return "$days d";  
	     	}  
	   	}

		//4.3 == 52/12
	   	else if($weeks <= 4.3) {  
	     	if($weeks==1) {  
	       		return "1 w";  
	     	}  
	        else {  
	       		return "$weeks w";  
	     	}  
	   	}

	   	else if($months <=12) {  
	     	if($months==1) {  
	       		return "1 m";  
	     	}  
	        else {
	       		return "$months m";  
	     	}  
	   	}
	   	else {  
	     	if($years==1) {  
	       		return "1 y";  
	     	}  
	        else {  
	       		return "$years y";  
	     	}  
	   	} 

	}
	function time_diff_messenger_activity($timestamp){
		$time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        
        $seconds = $time_difference;  

        $minutes = round($seconds / 60 ); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  

        if ($seconds <= 60) {
        	return "a moment ago";
        }

        else if ($minutes <= 60) {
        	if ($minutes == 1) {
        		return "1m ago";
        	}
        	else{
        		return ''.$minutes.'m ago';
        	}
        }

        else if ($hours <= 24) {
        	if($hours==1) {  
		       return "1h ago";  
		    }  
		    else {  
		       return ''.$hours.'h ago';  
		    }  
        }
        else if($days <= 7) {  
	     	if($days==1) {  
	       		return "1d ago";  
	     	}  
	        else {  
		       return ''.$days.'d ago';
	     	}  
	   	}

		//4.3 == 52/12
	   	else if($weeks <= 4.3) {  
	     	if($weeks==1) {  
	       		return "1w ago";  
	     	}  
	        else {  
		       return ''.$weeks.'w ago';
	     	}  
	   	}

	   	else if($months <=12) {  
	     	if($months==1) {  
	       		return "1month ago";  
	     	}  
	        else {
		       return ''.$months.'months ago';
	     	}  
	   	}
	   	else {  
	     	if($years==1) {  
	       		return "1y ago";  
	     	}  
	        else {  
		       return ''.$years.'y ago'; 
	     	}  
	   	} 

	}

	function time_diff_messenger_send($timestamp){
		$time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        
        $seconds = $time_difference;  

        $minutes = round($seconds / 60 ); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  

        if ($seconds <= 60) {
        	return "<i class='fa fa-circle' aria-hidden='true' style='color: #14BD05;'></i> Active Now";
        }

        else if ($minutes <= 60) {
        	if ($minutes == 1) {
        		return "1 m ago";
        	}
        	else{
        		return "$minutes m ago";
        	}
        }

        else if ($hours <= 24) {
        	if($hours==1) {  
		       return "1 h ago";  
		    }  
		    else {  
		       return "$hours h ago";  
		    }  
        }
        else if($days <= 7) {  
	     	if($days==1) {  
	       		return "1 d ago";  
	     	}  
	        else {  
	       		return "$days d ago";  
	     	}  
	   	}

		//4.3 == 52/12
	   	else if($weeks <= 4.3) {  
	     	if($weeks==1) {  
	       		return "1 w ago";  
	     	}  
	        else {  
	       		return "$weeks w ago";  
	     	}  
	   	}

	   	else if($months <=12) {  
	     	if($months==1) {  
	       		return "1 month ago";  
	     	}  
	        else {
	       		return "$months months ago";  
	     	}  
	   	}
	   	else {  
	     	if($years==1) {  
	       		return "1 y ago";  
	     	}  
	        else {  
	       		return "$years y ago";  
	     	}  
	   	} 

	}
	
?>