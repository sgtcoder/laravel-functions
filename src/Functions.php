<?php
if (!function_exists('cached_asset')) {
	// https://laravel-tricks.com/tricks/easier-caching-with-cached-asset
	/*
		# Redirect assets with version in name to actual filename
		<IfModule mod_rewrite.c>
			RewriteEngine On
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteRule ^(.+)\.(\d+)\.(js|css)$ $1\.$3 [L]
		</IfModule>
	*/
	function cached_asset($path, $bustQuery = false)
	{
		// Get the full path to the asset.
		$realPath = public_path($path);

		if (!file_exists($realPath)) {
			throw new LogicException("File not found at [{$realPath}]");
		}

		// Get the last updated timestamp of the file.
		$timestamp = filemtime($realPath);

		if (!$bustQuery) {
			// Get the extension of the file.
			$extension = pathinfo($realPath, PATHINFO_EXTENSION);

			// Strip the extension off of the path.
			$stripped = substr($path, 0, - (strlen($extension) + 1));

			// Put the timestamp between the filename and the extension.
			$path = implode('.', array($stripped, $timestamp, $extension));
		} else {
			// Append the timestamp to the path as a query string.
			$path  .= '?' . $timestamp;
		}

		return asset($path);
	}
}

if (!function_exists('array_to_object')) {
	function array_to_object($array)
	{
		$object = (object) array();

		foreach ($array as $key => $value) {
			$object->$key = $value;
		}

		return $object;
	}
}

if (!function_exists('generate_random_hex')) {
	function generate_random_hex($strlen = 16)
	{
		return strtoupper(implode(array_map(function () {
			return dechex(mt_rand(0, 15));
		}, array_fill(0, $strlen, null))));
	}
}

if (!function_exists('format_bytes')) {
	function format_bytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		$suffixes = array('', 'KB', 'MB', 'GB', 'TB');

		return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
	}
}

if (!function_exists('csv_to_array')) {
	function csv_to_array($filename, $trim = '')
	{
		$row = 0;
		$col = 0;

		$handle = @fopen($filename, "r");
		if ($handle) {
			while (($row = fgetcsv($handle, 16384, ",", '"', '"')) !== false) {
				if (empty($fields)) {
					$fields = $row;
					continue;
				}

				foreach ($row as $k => $value) {
					if ($trim) $value = trim($value, $trim);

					$results[$col][$fields[$k]] = $value;
				}
				$col++;
				unset($row);
			}
			if (!feof($handle)) {
				echo "Error: unexpected fgets() failn";
			}
			fclose($handle);
		}

		return $results;
	}
}

if (!function_exists('ascii_only')) {
	function ascii_only($text)
	{
		return preg_replace('/[[:^print:]]/', '', $text);
	}
}

if (!function_exists('is_valid_object')) {
	function is_valid_object($object, $index = "")
	{
		if (!empty($index)) {
			if (isset($object[$index]) && !empty($object[$index]) && is_object($object[$index]) && get_class($object[$index]) != 'stdClass') {
				return true;
			} else {
				return false;
			}
		} else {
			if (isset($object) && !empty($object) && is_object($object) && get_class($object) != 'stdClass') {
				return true;
			} else {
				return false;
			}
		}
	}
}

if (!function_exists('url_exists')) {
	function url_exists($url = NULL)
	{
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

if (!function_exists('get_base64_info')) {
	function get_base64_info($base64_data)
	{
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

if (!function_exists('unslugify')) {
	function unslugify($slug)
	{
		$text = ucwords(strtolower(str_replace('-', ' ', trim($slug))));

		return $text;
	}
}

if (!function_exists('format_date')) {
	function format_date($date, $format)
	{
		if ($date) {
			try {
				$date = new DateTime($date);
				$date = $date->format($format);
				return $date;
			} catch (Exception $e) {
				return $date;
			}
		} else {
			return NULL;
		}
	}
}

if (!function_exists('get_states')) {
	function get_states($state = NULL)
	{
		$states = '{"AL":"Alabama","AK":"Alaska","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","FL":"Florida","GA":"Georgia","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PA":"Pennsylvania","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}';

		$states = json_decode($states, TRUE);

		if ($state) {
			return $states[$state] ?? NULL;
		}

		return $states;
	}
}

if (!function_exists('get_countries')) {
	function get_countries($country = NULL)
	{
		$countries = '{"AF":"Afghanistan","AX":"land Islands","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"AndorrA","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","BN":"Brunei Darussalam","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos (Keeling) Islands","CO":"Colombia","KM":"Comoros","CG":"Congo","CD":"Congo, The Democratic Republic of the","CK":"Cook Islands","CR":"Costa Rica","CI":"Cote D\"Ivoire","HR":"Croatia","CU":"Cuba","CY":"Cyprus","CZ":"Czech Republic","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands (Malvinas)","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernsey","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and Mcdonald Islands","VA":"Holy See (Vatican City State)","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran, Islamic Republic Of","IQ":"Iraq","IE":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JE":"Jersey","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","KP":"Korea, Democratic People\"S Republic of","KR":"Korea, Republic of","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Lao People\"S Democratic Republic","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libyan Arab Jamahiriya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia, The Former Yugoslav Republic of","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia, Federated States of","MD":"Moldova, Republic of","MC":"Monaco","MN":"Mongolia","ME":"Montenegro","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","AN":"Netherlands Antilles","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian Territory, Occupied","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","RE":"Reunion","RO":"Romania","RU":"Russian Federation","RW":"RWANDA","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syrian Arab Republic","TW":"Taiwan, Province of China","TJ":"Tajikistan","TZ":"Tanzania, United Republic of","TH":"Thailand","TL":"Timor-Leste","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VE":"Venezuela","VN":"Viet Nam","VG":"Virgin Islands, British","VI":"Virgin Islands, U.S.","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}';

		$countries = json_decode($countries, TRUE);

		if ($country) {
			return $states[$country] ?? NULL;
		}

		return $countries;
	}
}

if (!function_exists('age_from_date')) {
	function age_from_date($date_of_birth)
	{
		$today = date('Y-m-d');

		try {
			$years = date_diff(date_create($date_of_birth), date_create($today))->format('%y');
		} catch (exception $e) {
			return NULL;
		}

		return $years;
	}
}

if (!function_exists('get_signed_url')) {
	function get_signed_url($media)
	{
		static $MediaService;

		if (empty($MediaService)) {
			$MediaService = (new \App\Services\MediaService);
		}

		return $MediaService->get_signed_url($media);
	}
}

if (!function_exists('get_all_model_media')) {

	function get_all_model_media($model)
	{
		$media_items = [];

		foreach ($model->getAllMediaByTag() as $media_group) {
			foreach ($media_group as $media) {
				$media_items[] = $media;
			}
		}

		return $media_items;
	}
}
