<?php
if (!function_exists('cached_asset')){
	// https://laravel-tricks.com/tricks/easier-caching-with-cached-asset
	/*
		# Redirect assets with version in name to actual filename
		<IfModule mod_rewrite.c>
			RewriteEngine On
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteRule ^(.+)\.(\d+)\.(js|css)$ $1\.$3 [L]
		</IfModule>
	*/
    function cached_asset($path, $bustQuery = false){
        // Get the full path to the asset.
        $realPath = public_path($path);

        if ( ! file_exists($realPath)) {
            throw new LogicException("File not found at [{$realPath}]");
        }

        // Get the last updated timestamp of the file.
        $timestamp = filemtime($realPath);

        if ( ! $bustQuery) {
            // Get the extension of the file.
            $extension = pathinfo($realPath, PATHINFO_EXTENSION);

            // Strip the extension off of the path.
            $stripped = substr($path, 0, -(strlen($extension) + 1));

            // Put the timestamp between the filename and the extension.
            $path = implode('.', array($stripped, $timestamp, $extension));
        } else {
            // Append the timestamp to the path as a query string.
            $path  .= '?' . $timestamp;
        }

        return asset($path);
    }
}

if (!function_exists('array_to_object')){
	function array_to_object($array){
		$object = (object) array();

		foreach($array as $key=>$value){
			$object->$key = $value;
		}

		return $object;
	}
}

if (!function_exists('generate_random_hex')){
	function generate_random_hex($strlen=16){
		return strtoupper(implode(array_map(function() { return dechex(mt_rand(0, 15)); }, array_fill(0, $strlen, null))));
	}
}

if (!function_exists('format_bytes')){
	function format_bytes($size, $precision = 2){
	    $base = log($size, 1024);
	    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

	    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
}

if (!function_exists('csv_to_array')){
	function csv_to_array($filename, $trim=''){
	    $row = 0;
	    $col = 0;

	    $handle = @fopen($filename, "r");
	    if ($handle)
	    {
	        while (($row = fgetcsv($handle, 16384, ",", '"', '"')) !== false)
	        {
	            if (empty($fields))
	            {
	                $fields = $row;
	                continue;
	            }

	            foreach ($row as $k=>$value)
	            {
	                if($trim) $value = trim($value, $trim);

	                $results[$col][$fields[$k]] = $value;
	            }
	            $col++;
	            unset($row);
	        }
	        if (!feof($handle))
	        {
	            echo "Error: unexpected fgets() failn";
	        }
	        fclose($handle);
	    }

	    return $results;
	}
}

if(!function_exists('ascii_only')){
    function ascii_only($text){
        return preg_replace('/[[:^print:]]/', '', $text);
    }
}

if (!function_exists('is_valid_object')){
	function isValidObject($object, $index=""){
		if(!empty($index)){
			if(isset($object[$index]) && !empty($object[$index]) && is_object($object[$index]) && get_class($object[$index]) != 'stdClass'){
				return true;
			} else {
				return false;
			}
		}else{
			if(isset($object) && !empty($object) && is_object($object) && get_class($object) != 'stdClass'){
				return true;
			} else {
				return false;
			}

		}
	}
}

if (!function_exists('url_exists')){
	function url_exists($url = NULL){
        if ($url == NULL) return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? true : false;
   }
}

if (!function_exists('get_base64_info')){
	function get_base64_info($base64_data){
		$image_parts = explode(";base64,", $base64_data);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
		$image_base64 = base64_decode($image_parts[1]);

		return array(
			'image_type_aux' => $image_type_aux,
			'image_type' => $image_type,
			'image_base64' => $image_base64,
		);
	}
}

if(!function_exists('unslugify')){
    function unslugify($slug){
        $text = ucwords(strtolower(str_replace('-', ' ', trim($slug))));

        return $text;
    }
}

if(!function_exists('format_date')){
    function format_date($date, $format){
        if($date){
            try {
                $date = new DateTime($date);
                $date = $date->format($format);
                return $date;
            } catch (Exception $e) {
                return $date;
            }
        }else{
            return NULL;
        }
    }
}

if (!function_exists('get_states')) {
    function get_states($state=NULL){
        $states = '{"AL":"Alabama","AK":"Alaska","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","FL":"Florida","GA":"Georgia","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PA":"Pennsylvania","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}';

        $states = json_decode($states, TRUE);

        if($state){
            return $states[$state] ?? NULL;
        }

        return $states;
    }
}