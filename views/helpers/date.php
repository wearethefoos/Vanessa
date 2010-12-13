<?php
class DateHelper extends AppHelper {
/* Works out the time since the entry post, takes a an argument in unix time (seconds)
*/
    function timeSince($original) {
        // array of time period chunks
        $chunks = array(
    	array(60 * 60 * 24 * 365 , 'year'),
    	array(60 * 60 * 24 * 30 , 'month'),
    	array(60 * 60 * 24 * 7, 'week'),
    	array(60 * 60 * 24 , 'day'),
    	array(60 * 60 , 'hour'),
    	array(60 , 'min'),
    	array(1 , 'sec'),
        );
    
        $today = time(); /* Current unix time  */
        $since = $today - $original;
    
        // $j saves performing the count function each time around the loop
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
    
    	$seconds = $chunks[$i][0];
    	$name = $chunks[$i][1];
    
    	// finding the biggest chunk (if the chunk fits, break)
    	if (($count = floor($since / $seconds)) != 0) {
    	    break;
    	}
        }
    
        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    
        if ($i + 1 < $j) {
    	// now getting the second item
    	$seconds2 = $chunks[$i + 1][0];
    	$name2 = $chunks[$i + 1][1];
    
    	// add second item if its greater than 0
    	if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
    	    $print .= ($count2 == 1) ? ', 1 '.$name2 : " $count2 {$name2}s";
    	}
        }
        return $print;
    }
}