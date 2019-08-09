<?php
defined("PASS") or die("Dosya yok!");

function calendar ($year=null, $month=null, $days = array()){
	$year  = $year  ? $year  : date("Y");
	$month = $month ? $month : date("n");

	$first_of_month = mktime(0,0,0,$month,1,$year);
	$month_names = showDate("months");
	$day_names = showDate("days_abbr");

	$weekday = date("w", $first_of_month);
	$month_name = $month_names[$month];

	$weekday = ($weekday + 6) % 7; #adjust for 1
	$title   = label($month_name).", ".$year;

	$calendar = "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"calendar\">\n"
	. "<thead>\n"
	. "<tr>\n"
	. "<td colspan=\"7\" class=\"title\">".$title."</td>"
	. "</tr>\n"
	. "<tr class=\"daynames\">\n";

	$i = 1;
	foreach ($day_names as $d){
		$calendar.= "<td class=\"".($i > 5 ? "name day weekend" : "day name")."\">".label($d)."</td>";
		$i++;
	}
	
	$calendar.= "	</tr>\n"
	. "</thead>\n"
	. "<tbody>\n"
	. "<tr class=\"daysrow\">\n";

	for ($i=0; $i < $weekday; $i++){
		$calendar.= "<td class=\"day\">&nbsp;</td>";
	}
	
	for ($day=1, $days_in_month=date("t",$first_of_month); $day <= $days_in_month; $day++, $weekday++){
		if ($weekday == 7){
			$weekday = 0;
  	$calendar.= "</tr>\n"
  	. "<tr class=\"daysrow\">\n";
		}
		
		if (isset($days[$day]) and is_array($days[$day])){
			@list($link, $classes, $content) = $days[$day];
			if (is_null($content)){
			 $content = $day;
			}
			$calendar.= "<td".($classes ? " class=\"".htmlspecialchars($classes)."\"" : "").">"
			. ($link ? "<a href=\"".htmlspecialchars($link)."\">".$content."</a>" : $content)."</td>";
		}else{
			if ($day == date("j")){
				$calendar.= "<td class=\"day selected today\">".$day."</td>\n";
			}else{
				$calendar.= "<td class=\"".($weekday > 4 ? "day weekend" : "day")."\">".$day."</td>\n";
			}
		}
	}

	for ($i=0; $i < (7 - $weekday); $i++){
		$calendar.= "<td class=\"day\">&nbsp;</td>\n";
	}

	$calendar.= "</tr>\n"
	. "</tbody>\n"
	. "</table>\n";
	
	return $calendar;
}
?>