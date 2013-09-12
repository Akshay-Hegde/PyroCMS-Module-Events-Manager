<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module helpers
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

if( ! function_exists('display_timespan'))
{
	function display_timespan($start, $end, $date_format = null, $time_format = null)
	{
		// Formats
		if(! $date_format) $date_format = 'F j, Y';
		
		if(! $time_format) $time_format = 'g:ia';
		
		if(is_string($start)) $start = strtotime($start);
		
		if(is_string($end)) $end = strtotime($end);
		
		// Same day?
		if(date($start, 'Ymd') == date($end, 'Ymd'))
		{
			$display[] = date($start, $date_format);
			$display[] = 'from';
			$display[] = date($start, $time_format);
			$display[] = 'to';
			$display[] = date($end, $time_format);
			
			return explode(' ', $display);
		}
	}
}