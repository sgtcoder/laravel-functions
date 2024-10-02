<?php

if (!function_exists('get_plank_media_class')) {
	function get_plank_media_class()
	{
		if (class_exists('App\Models\Media')) {
			return '\App\Models\Media';
		}

		return '\Plank\Mediable\Media';
	}
}

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
	/**
	 * cached_asset
	 *
	 * @param  mixed $path
	 * @param  mixed $bustQuery
	 * @return mixed
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
	/**
	 * array_to_object
	 *
	 * @param  mixed $array
	 * @return mixed
	 */
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
	/**
	 * generate_random_hex
	 *
	 * @param  mixed $strlen
	 * @return mixed
	 */
	function generate_random_hex($strlen = 16)
	{
		return strtoupper(implode(array_map(function () {
			return dechex(mt_rand(0, 15));
		}, array_fill(0, $strlen, null))));
	}
}

if (!function_exists('format_bytes')) {
	/**
	 * format_bytes
	 *
	 * @param  mixed $size
	 * @param  mixed $precision
	 * @return mixed
	 */
	function format_bytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		$suffixes = array('', 'KB', 'MB', 'GB', 'TB');

		return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
	}
}

if (!function_exists('csv_to_array')) {
	/**
	 * csv_to_array
	 *
	 * @param  mixed $filename
	 * @param  mixed $trim
	 * @return mixed
	 */
	function csv_to_array($filename, $trim = '')
	{
		$row = 0;
		$col = 0;
		$results = [];

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
	/**
	 * ascii_only
	 *
	 * @param  mixed $text
	 * @return mixed
	 */
	function ascii_only($text)
	{
		return preg_replace('/[[:^print:]]/', '', $text);
	}
}

if (!function_exists('is_valid_object')) {
	/**
	 * is_valid_object
	 *
	 * @param  mixed $object
	 * @param  mixed $index
	 * @return mixed
	 */
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
	/**
	 * url_exists
	 *
	 * @param  mixed $url
	 * @return mixed
	 */
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
	/**
	 * get_base64_info
	 *
	 * @param  mixed $base64_data
	 * @return mixed
	 */
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
	/**
	 * unslugify
	 *
	 * @param  string $slug
	 * @return string
	 */
	function unslugify($slug)
	{
		$slug = ucwords(strtolower(str_replace('-', ' ', trim($slug))));

		return $slug;
	}
}

if (!function_exists('slugify')) {
	/**
	 * slugify
	 *
	 * @param  string $slug
	 * @return string
	 */
	function slugify($slug)
	{
		$slug = trim($slug);
		$slug = str()->of($slug)->replace(':', '_');
		$slug = str()->slug($slug);
		$slug = str()->of($slug)->replace('-', '_');

		return $slug;
	}
}

if (!function_exists('format_date')) {
	/**
	 * format_date
	 *
	 * @param  mixed $date
	 * @param  mixed $format
	 * @return mixed
	 */
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
	/**
	 * get_states
	 *
	 * @param  mixed $state
	 * @param  mixed $add_states
	 * @return mixed
	 */
	function get_states($state = NULL, $add_states = [])
	{
		$states = '{"AL":"Alabama","AK":"Alaska","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","FL":"Florida","GA":"Georgia","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PA":"Pennsylvania","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}';

		$states = json_decode($states, TRUE);
		$states = array_merge($states, $add_states);

		asort($states);

		if ($state) {
			return $states[$state] ?? NULL;
		}

		return $states;
	}
}

if (!function_exists('get_countries')) {
	/**
	 * get_countries
	 *
	 * @param  mixed $country
	 * @return mixed
	 */
	function get_countries($country = NULL)
	{
		$countries = '{"AF":"Afghanistan","AX":"land Islands","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"AndorrA","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","BN":"Brunei Darussalam","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos (Keeling) Islands","CO":"Colombia","KM":"Comoros","CG":"Congo","CD":"Congo, The Democratic Republic of the","CK":"Cook Islands","CR":"Costa Rica","CI":"Cote D\"Ivoire","HR":"Croatia","CU":"Cuba","CY":"Cyprus","CZ":"Czech Republic","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands (Malvinas)","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernsey","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and Mcdonald Islands","VA":"Holy See (Vatican City State)","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran, Islamic Republic Of","IQ":"Iraq","IE":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JE":"Jersey","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","KP":"Korea, Democratic People\"S Republic of","KR":"Korea, Republic of","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Lao People\"S Democratic Republic","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libyan Arab Jamahiriya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia, The Former Yugoslav Republic of","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia, Federated States of","MD":"Moldova, Republic of","MC":"Monaco","MN":"Mongolia","ME":"Montenegro","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","AN":"Netherlands Antilles","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian Territory, Occupied","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","RE":"Reunion","RO":"Romania","RU":"Russian Federation","RW":"RWANDA","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syrian Arab Republic","TW":"Taiwan, Province of China","TJ":"Tajikistan","TZ":"Tanzania, United Republic of","TH":"Thailand","TL":"Timor-Leste","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VE":"Venezuela","VN":"Viet Nam","VG":"Virgin Islands, British","VI":"Virgin Islands, U.S.","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}';

		$countries = json_decode($countries, TRUE);

		if ($country) {
			return $countries[$country] ?? NULL;
		}

		return $countries;
	}
}

if (!function_exists('age_from_date')) {
	/**
	 * age_from_date
	 *
	 * @param  mixed $date_of_birth
	 * @return mixed
	 */
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
	/**
	 * get_signed_url
	 *
	 * @param  mixed $media
	 * @return mixed
	 */
	function get_signed_url($media)
	{
		static $MediaService;

		if (empty($MediaService)) {
			// @phpstan-ignore-next-line
			$MediaService = (new \App\Services\MediaService);
		}

		return $MediaService->get_signed_url($media);
	}
}

if (!function_exists('get_all_model_media')) {
	/**
	 * get_all_model_media
	 *
	 * @param  mixed $model
	 * @return mixed
	 */
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

if (!function_exists('replace_mappings')) {
	/**
	 * replace_mappings
	 *
	 * @param  mixed $body
	 * @param  mixed $mappings
	 * @return mixed
	 */
	function replace_mappings($body, $mappings)
	{
		foreach ($mappings as $key => $value) {
			$body = preg_replace('/{{\s*' . $key . '\s*}}/', $value, $body);
		}

		return $body;
	}
}

if (!function_exists('replace_custom_mappings')) {
	/**
	 * replace_custom_mappings
	 *
	 * @param  mixed $body
	 * @param  mixed $mappings
	 * @return mixed
	 */
	function replace_custom_mappings($body, $mappings)
	{
		foreach ($mappings as $key => $value) {
			$body = str_replace($key, $value, $body);
		}

		return $body;
	}
}

if (!function_exists('log_string')) {
	/**
	 * log_string
	 *
	 * @param  mixed $signature
	 * @param  mixed $type
	 * @param  mixed $message
	 * @param  mixed $disable_timestamp
	 * @param  mixed $newline
	 * @return mixed
	 */
	function log_string($signature = null, $type = 'DEFAULT', $message = null, $disable_timestamp = false, $newline = false)
	{
		if ($signature == null) return '';

		$log_name = explode(' ', $signature);
		$log_name = $log_name[0];

		$log = null;
		if (!$disable_timestamp) $log = '[' . now()->format('Y-m-d H:i:s') . '][' . $log_name . '][' . $type . ']: ';

		$template = match ($type) {
			'INFO' => '<fg=#ffc107;options=bold>' . $log . '</>' . $message,
			'SUCCESS' => '<fg=#28a745;options=bold>' . $log . '</>' . $message,
			'WARNING' => '<fg=#ffc107;options=bold>' . $log . '</>' . $message,
			'ERROR' => '<fg=#dc3545;options=bold>' . $log . '</>' . $message,
			'DEFAULT' => '<fg=#000000;options=bold>' . $log . '</>' . $message,
			default => '<fg=#000000;options=bold>' . $log . '</>' . $message,
		};

		if ($newline) $template .= "\n";

		return $template;
	}
}

if (!function_exists('console_log')) {
	/**
	 * console_log
	 *
	 * @param  mixed $signature
	 * @param  mixed $type
	 * @param  mixed $message
	 * @param  mixed $disable_timestamp
	 * @param  mixed $newline
	 * @return mixed
	 */
	function console_log($signature = null, $type = 'DEFAULT', $message = null, $disable_timestamp = false, $newline = false)
	{
		$template = log_string($signature, $type, $message, $disable_timestamp, $newline);

		$output = new \Symfony\Component\Console\Output\ConsoleOutput();
		$output->writeln($template);

		return true;
	}
}

if (!function_exists('convert_meters_to_miles')) {
	/**
	 * convert_meters_to_miles
	 *
	 * @param  mixed $meters
	 * @param  mixed $precision
	 * @return mixed
	 */
	function convert_meters_to_miles($meters, $precision = 2)
	{
		if ($meters === NULL) return NULL;

		return round($meters * 0.000621371, $precision);
	}
}

if (!function_exists('convert_meters_to_feet')) {
	/**
	 * convert_meters_to_feet
	 *
	 * @param  mixed $meters
	 * @param  mixed $precision
	 * @return mixed
	 */
	function convert_meters_to_feet($meters, $precision = 2)
	{
		if ($meters === NULL) return NULL;

		return round($meters * 3.2808399, $precision);
	}
}

if (!function_exists('format_bytes')) {
	/**
	 * format_bytes
	 *
	 * @param  mixed $bytes
	 * @param  mixed $precision
	 * @param  mixed $decimals
	 * @return mixed
	 */
	function format_bytes($bytes, $precision = 2, $decimals = 2)
	{
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		return round($bytes, $precision) . $units[$pow];
	}
}

if (!function_exists('adjust_brightness')) {
	/**
	 * adjust_brightness
	 *
	 * @param  mixed $hex
	 * @param  mixed $steps
	 * @return mixed
	 */
	function adjust_brightness($hex, $steps)
	{
		// Steps should be between -255 and 255. Negative = darker, positive = lighter
		$steps = max(-255, min(255, $steps));

		// Normalize into a six character long hex string
		$hex = str_replace('#', '', $hex);
		if (strlen($hex) == 3) {
			$hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
		}

		// Split into three parts: R, G and B
		$color_parts = str_split($hex, 2);
		$return = '#';

		foreach ($color_parts as $color) {
			$color   = hexdec($color); // Convert to decimal
			$color   = max(0, min(255, $color + $steps)); // Adjust color
			$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
		}

		return $return;
	}
}

if (!function_exists('template_replace')) {
	/**
	 * template_replace
	 *
	 * @param  mixed $variable
	 * @param  mixed $replace
	 * @param  mixed $string
	 * @return mixed
	 */
	function template_replace($variable, $replace, $string)
	{
		$string = preg_replace('/{{\s*' . $variable . '\s*}}/', $replace, $string);

		return $string;
	}
}

if (!function_exists('model_to_html')) {
	/**
	 * model_to_html
	 *
	 * @param  mixed $model
	 * @param  mixed $include_empty
	 * @return mixed
	 */
	function model_to_html($model, $include_empty = true)
	{
		$data = $model->getFillable();

		$html = '';
		foreach ($data as $key) {
			if ($model->$key || $include_empty) {
				$html .= '<p style="margin: 5px 0;"><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ' . $model->$key . '</p>';
			}
		}

		return $html;
	}
}

if (!function_exists('build_alert')) {
	/**
	 * build_alert
	 *
	 * @param  mixed $status
	 * @param  mixed $message
	 * @param  mixed $class
	 * @return mixed
	 */
	function build_alert($status, $message, $class = NULL)
	{
		return '<div class="alert alert-' . $status . ' ' . $class . '" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1000">' . $message . '</div>';
	}
}

if (!function_exists('get_model_count')) {
	/**
	 * get_model_count
	 *
	 * @param  mixed $model
	 * @return mixed
	 */
	function get_model_count($model)
	{
		$model = 'App\\Models\\' . $model;
		$count = (new $model)->count();

		return $count;
	}
}

if (!function_exists('sync_media')) {
	/**
	 * sync_media
	 *
	 * @param  mixed $prefix
	 * @param  mixed $model
	 * @param  mixed $single
	 * @param  mixed $media_prefix
	 * @return mixed
	 */
	function sync_media($prefix, $model = NULL, $single = true, $media_prefix = NULL)
	{
		// @phpstan-ignore-next-line
		$MediaService = (new \App\Services\MediaService);

		$PlankMediaClass = get_plank_media_class();

		$media_prefix ??= $prefix;

		if (request($prefix . '_delete')) {
			if ($model && $model->firstMedia($prefix)) $model->firstMedia($media_prefix)->delete();

			$media_delete = $PlankMediaClass::find($media_prefix);
			if ($media_delete) $media_delete->delete();
		}

		// Crop new images
		if (request($prefix . '_media')) {
			foreach (request($prefix . '_media') as $item) {
				$media_data = request($prefix . '_data.' . $item);
				if ($media_data) {
					// @phpstan-ignore-next-line
					$MediaService->update_media_crop($item, $media_data);
				}

				$media_metadata = request($prefix . '_metadata.' . $item);
				if ($media_metadata) {
					// @phpstan-ignore-next-line
					$media = $PlankMediaClass::find($item);

					if ($media) {
						$media->setAttribute('metadata', $media_metadata);
						$media->save();
					}
				}
			}

			if ($single) {
				// Sync media and orders
				if ($model) $model->syncMedia(request($prefix . '_media'), $media_prefix);
			}
		}

		if (!$single) {
			// Sync media and orders
			if ($model) $model->syncMedia(request($prefix . '_media'), $media_prefix);
		}
	}
}

if (!function_exists('update_status')) {
	/**
	 * update_status
	 *
	 * @return mixed
	 */
	function update_status()
	{
		request()->validate([
			'status' => 'required',
		]);

		$model_id = request('model_id');
		$model_type = request('model_type');
		$status = request('status');

		$model_path = '\\App\\Models\\' . $model_type;
		$model = (new $model_path)->find($model_id);

		// Update blog
		$model->update([
			'status' => $status,
		]);

		return response()->json(['status' => true, 'message' => $model_type . ' Status Updated Successfully.'], 200);
	}
}

if (!function_exists('get_signed_url')) {
	/**
	 * get_signed_url
	 *
	 * @param  mixed $media
	 * @return mixed
	 */
	function get_signed_url($media)
	{
		static $MediaService;

		if (empty($MediaService)) {
			// @phpstan-ignore-next-line
			$MediaService = (new \App\Services\MediaService);
		}

		return $MediaService->get_signed_url($media);
	}
}

if (!function_exists('is_super_admin')) {
	/**
	 * is_super_admin
	 *
	 * @return mixed
	 */
	function is_super_admin()
	{
		// @phpstan-ignore-next-line
		$user_roles = request()->user()->roles->pluck('name')->toArray();

		if (in_array('Super Admin', $user_roles)) {
			return true;
		}

		return false;
	}
}

if (!function_exists('get_tagged_models_media')) {
	/**
	 * get_tagged_models_media
	 *
	 * @param  mixed $models
	 * @param  mixed $model_tag
	 * @return mixed
	 */
	function get_tagged_models_media($models, $model_tag)
	{
		$media_items = [];
		foreach ($models as $model) {
			$media_items[] = $model->firstMedia($model_tag);
		}

		return $media_items;
	}
}

if (!function_exists('get_roles')) {
	/**
	 * get_roles
	 *
	 * @return mixed
	 */
	function get_roles()
	{
		// @phpstan-ignore-next-line
		$roles = \App\Models\Role::query();

		if (!is_super_admin()) {
			$roles->where('name', '<>', 'Super Admin');
		}

		$roles = $roles->pluck('name', 'id')->toArray();

		return $roles;
	}
}

if (!function_exists('scale_image')) {
	/**
	 * scale_image
	 *
	 * @param  mixed $url
	 * @param  mixed $max_size
	 * @param  mixed $type
	 * @return mixed
	 */
	function scale_image($url, $max_size, $type = 'height')
	{
		$data = getimagesize($url);
		$width = $data[0];
		$height = $data[1];

		if ($type == 'height') {
			$scaled_width = $width / ($height / $max_size);
			$scaled_height = $max_size;
		} else {
			$scaled_width = $max_size;
			$scaled_height = $height / ($width / $max_size);
		}

		return ['width' => $scaled_width, 'height' => $scaled_height];
	}
}

if (!function_exists('unformat_phone')) {
	/**
	 * unformat_phone
	 *
	 * @param  mixed $phone
	 * @return mixed
	 */
	function unformat_phone($phone)
	{
		$phone = preg_replace("/[^0-9]/", "", $phone);
		if (strlen($phone) == 10) $phone = '+1' . $phone;
		if (strlen($phone) == 11) $phone = '+' . $phone;

		return $phone;
	}
}

if (!function_exists('format_phone')) {
	/**
	 * format_phone
	 *
	 * @param  mixed $phone
	 * @return mixed
	 */
	function format_phone($phone)
	{
		$phone = preg_replace("/[^0-9]/", "", $phone);
		if (strlen($phone) == 11) $phone = ltrim($phone, '1');
		$phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $phone);

		return $phone;
	}
}

if (!function_exists('get_auth_prefix')) {
	/**
	 * get_auth_prefix
	 *
	 * @return mixed
	 */
	function get_auth_prefix()
	{
		$route = str()->of(request()->route()->getName())->explode('.');

		return $route[0] ?? null;
	}
}

if (!function_exists('get_intended_route')) {
	/**
	 * get_intended_route
	 *
	 * @param  mixed $prefix
	 * @return mixed
	 */
	function get_intended_route($prefix)
	{
		$intended_url = redirect()->intended()->getTargetUrl();

		$route = \Route::getRoutes()->match(\Request::create($intended_url))->getName();

		$route = str()->of($route)->explode('.');

		if (($route[0] ?? null) == $prefix) {
			return $intended_url;
		}

		return null;
	}
}

if (!function_exists('get_guards')) {
	/**
	 * get_guards
	 *
	 * @return mixed
	 */
	function get_guards()
	{
		$guards = collect(config('auth.guards'))->keys()->mapWithKeys(function ($guard) {
			return [$guard => ucwords($guard)];
		});

		return $guards;
	}
}

if (!function_exists('get_guard_data')) {
	/**
	 * get_guard_data
	 *
	 * @param  mixed $url
	 * @return array
	 */
	function get_guard_data($url)
	{
		$prefix = \Route::getRoutes()->match(\Request::create($url))->getPrefix();
		$prefix = ltrim($prefix, '/');

		$middlewares = \Route::getRoutes()->match(\Request::create($url))->gatherMiddleware();
		$middlewares = collect($middlewares)->filter(function ($middleware) {
			return str()->startsWith($middleware, 'auth');
		})->first();

		$guard = str()->of($middlewares)->replace('auth:', '')->toString();

		if ($guard == 'auth') {
			$guard = 'web';
		}

		return [
			'prefix' => $prefix,
			'guard' => $guard,
		];
	}
}

if (!function_exists('get_timezones')) {
	/**
	 * get_guards
	 *
	 * @return mixed
	 */
	function get_timezones()
	{
		$timezones = array(
			'Pacific/Midway'       => "(GMT-11:00) Midway Island",
			'US/Samoa'             => "(GMT-11:00) Samoa",
			'US/Hawaii'            => "(GMT-10:00) Hawaii",
			'US/Alaska'            => "(GMT-09:00) Alaska",
			'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
			'America/Tijuana'      => "(GMT-08:00) Tijuana",
			'US/Arizona'           => "(GMT-07:00) Arizona",
			'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
			'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
			'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
			'America/Mexico_City'  => "(GMT-06:00) Mexico City",
			'America/Monterrey'    => "(GMT-06:00) Monterrey",
			'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
			'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
			'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
			'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
			'America/Bogota'       => "(GMT-05:00) Bogota",
			'America/Lima'         => "(GMT-05:00) Lima",
			'America/Caracas'      => "(GMT-04:30) Caracas",
			'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
			'America/La_Paz'       => "(GMT-04:00) La Paz",
			'America/Santiago'     => "(GMT-04:00) Santiago",
			'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
			'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
			'Greenland'            => "(GMT-03:00) Greenland",
			'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
			'Atlantic/Azores'      => "(GMT-01:00) Azores",
			'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
			'Africa/Casablanca'    => "(GMT) Casablanca",
			'Europe/Dublin'        => "(GMT) Dublin",
			'Europe/Lisbon'        => "(GMT) Lisbon",
			'Europe/London'        => "(GMT) London",
			'Africa/Monrovia'      => "(GMT) Monrovia",
			'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
			'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
			'Europe/Berlin'        => "(GMT+01:00) Berlin",
			'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
			'Europe/Brussels'      => "(GMT+01:00) Brussels",
			'Europe/Budapest'      => "(GMT+01:00) Budapest",
			'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
			'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
			'Europe/Madrid'        => "(GMT+01:00) Madrid",
			'Europe/Paris'         => "(GMT+01:00) Paris",
			'Europe/Prague'        => "(GMT+01:00) Prague",
			'Europe/Rome'          => "(GMT+01:00) Rome",
			'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
			'Europe/Skopje'        => "(GMT+01:00) Skopje",
			'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
			'Europe/Vienna'        => "(GMT+01:00) Vienna",
			'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
			'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
			'Europe/Athens'        => "(GMT+02:00) Athens",
			'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
			'Africa/Cairo'         => "(GMT+02:00) Cairo",
			'Africa/Harare'        => "(GMT+02:00) Harare",
			'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
			'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
			'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
			'Europe/Kiev'          => "(GMT+02:00) Kyiv",
			'Europe/Minsk'         => "(GMT+02:00) Minsk",
			'Europe/Riga'          => "(GMT+02:00) Riga",
			'Europe/Sofia'         => "(GMT+02:00) Sofia",
			'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
			'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
			'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
			'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
			'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
			'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
			'Europe/Moscow'        => "(GMT+03:00) Moscow",
			'Asia/Tehran'          => "(GMT+03:30) Tehran",
			'Asia/Baku'            => "(GMT+04:00) Baku",
			'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
			'Asia/Muscat'          => "(GMT+04:00) Muscat",
			'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
			'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
			'Asia/Kabul'           => "(GMT+04:30) Kabul",
			'Asia/Karachi'         => "(GMT+05:00) Karachi",
			'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
			'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
			'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
			'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
			'Asia/Almaty'          => "(GMT+06:00) Almaty",
			'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
			'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
			'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
			'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
			'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
			'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
			'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
			'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
			'Australia/Perth'      => "(GMT+08:00) Perth",
			'Asia/Singapore'       => "(GMT+08:00) Singapore",
			'Asia/Taipei'          => "(GMT+08:00) Taipei",
			'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
			'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
			'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
			'Asia/Seoul'           => "(GMT+09:00) Seoul",
			'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
			'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
			'Australia/Darwin'     => "(GMT+09:30) Darwin",
			'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
			'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
			'Australia/Canberra'   => "(GMT+10:00) Canberra",
			'Pacific/Guam'         => "(GMT+10:00) Guam",
			'Australia/Hobart'     => "(GMT+10:00) Hobart",
			'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
			'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
			'Australia/Sydney'     => "(GMT+10:00) Sydney",
			'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
			'Asia/Magadan'         => "(GMT+12:00) Magadan",
			'Pacific/Auckland'     => "(GMT+12:00) Auckland",
			'Pacific/Fiji'         => "(GMT+12:00) Fiji",
		);

		return $timezones;
	}
}

if (!function_exists('get_guards')) {
	/**
	 * get_guards
	 *
	 * @return mixed
	 */
	function get_guards()
	{
		$guards = collect(config('auth.guards'))->keys()->mapWithKeys(function ($guard) {
			return [$guard => ucwords($guard)];
		});

		return $guards;
	}
}

if (!function_exists('create_password')) {
	/**
	 * create_password
	 *
	 * @param  mixed $deprecrated
	 * @return mixed
	 */
	function create_password($deprecrated = null)
	{
		return (new \SgtCoder\LaravelFunctions\Services\PasswordService)->password(16);
	}
}

if (!function_exists('generate_new_token')) {
	/**
	 * generate_new_token
	 *
	 * @return mixed
	 */
	function generate_new_token()
	{
		return (new \SgtCoder\LaravelFunctions\Services\PasswordService)->hex(16);
	}
}

if (!function_exists('generateNewToken')) {
	/**
	 * generateNewToken
	 *
	 * @return mixed
	 */
	function generateNewToken()
	{
		return generate_new_token();
	}
}

if (!function_exists('generate_mac_address')) {
	/**
	 * generate_mac_address
	 *
	 * @return mixed
	 */
	function generate_mac_address()
	{
		return (new \SgtCoder\LaravelFunctions\Services\PasswordService)->generate_mac_address();
	}
}

if (!function_exists('array_to_html')) {
	/**
	 * array_to_html
	 *
	 * @param  mixed $data
	 * @param  mixed $keys
	 * @return mixed
	 */
	function array_to_html($data, $keys)
	{
		$html = '<style>td{color: #fff;padding: 5px;}</style>';

		$html .= '<table>';
		$html .= '<thead><tr>';
		foreach ($keys as $key => $type) {
			$html .= '<td><strong>' . ucwords(str_replace('_', ' ', $key)) . '</strong></td>';
		}

		$html .= '</tr></thead>';

		$html .= '<tbody>';

		foreach ($data as $value) {
			$html .= '<tr>';

			foreach ($keys as $key => $types) {
				$types = explode('|', $types);
				$the_value = $value->$key;

				if (in_array('date', $types)) {
					$the_value = now()->parse($the_value)->format('m/d/Y');
				}

				$styles = implode(';', $types);

				$html .= '<td style="' . $styles . '">' . $the_value . '</td>';
			}

			$html .= '</tr>';
		}

		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}
}

if (!function_exists('content_for')) {
	/**
	 * content_for
	 *
	 * @param  mixed $section
	 * @return mixed
	 */
	function content_for($section)
	{
		return array_key_exists($section, app('view')->getSections());
	}
}

if (!function_exists('download_datatable')) {
	/**
	 * download_datatable
	 *
	 * @param  mixed $data
	 * @param  mixed $model_name
	 * @param  mixed $additional_columns
	 * @param  mixed $remove_columns
	 * @param  mixed $model_only
	 * @return mixed
	 */
	function download_datatable($data, $model_name, $additional_columns = [], $remove_columns = [], $model_only = false)
	{
		$model = '\App\Models\\' . $model_name;
		$table_name = app($model)->getTable();

		if ($model_only) {
			$columns = app($model)->getFillable();
		} else {
			$columns = collect(request('columns'))->pluck('data')->toArray();
		}

		$remove_columns = array_merge($remove_columns, ['actions']);

		$columns = array_merge($columns, $additional_columns);
		$columns = array_diff($columns, $remove_columns);

		$filename = $table_name . '_' . now()->format('Y-m-d') . '.csv';

		// Reject
		$disable_models = ['User'];
		if (in_array($model_name, $disable_models)) {
			throw new \Illuminate\Auth\Access\AuthorizationException;
		}

		request()->merge(['length' => -1]);
		$data = $data->toJson()->getData(true)['data'];

		$headers = array(
			"Content-type"        => "text/csv",
			"Content-Disposition" => "attachment; filename=$filename",
			"Pragma"              => "no-cache",
			"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
			"Expires"             => "0"
		);

		$callback = function () use ($data, $columns) {
			$file = fopen('php://output', 'w');
			fputcsv($file, $columns);

			foreach ($data as $item) {
				$data_item = [];

				foreach ($columns as $column) {
					$data_item[$column] = strip_tags($item[$column]);
				}

				fputcsv($file, $data_item);
			}

			fclose($file);
		};

		return response()->stream($callback, 200, $headers);
	}
}

if (!function_exists('strip_https')) {
	/**
	 * strip_https
	 *
	 * @param  string $url
	 * @return string
	 */
	function strip_https($url)
	{
		return preg_replace('#^https?://#', '', rtrim($url, '/'));
	}
}

if (!function_exists('icmp_ping')) {
	/**
	 * icmp_ping
	 *
	 * @param  string $ip
	 * @param  integer $count
	 * @return boolean
	 */
	function icmp_ping($ip, $count = 4)
	{
		$process = \Symfony\Component\Process\Process::fromShellCommandline('ping ' . $ip . ' -c ' . $count);
		$process->setTimeout(28800);
		$process->disableOutput();
		$process->run();

		if (!$process->isSuccessful()) {
			return false;
		}

		return true;
	}
}

if (!function_exists('email_template_exists')) {
	/**
	 * email_template_exists
	 *
	 * @param  string $template
	 * @return boolean
	 */
	function email_template_exists($template)
	{
		return view()->exists('emails.' . $template);
	}
}

if (!function_exists('make_directory')) {
	/**
	 * make_directory
	 *
	 * @param  string $path
	 * @return boolean
	 */
	function make_directory($path)
	{
		if (!Illuminate\Support\Facades\File::exists($path)) {
			Illuminate\Support\Facades\File::makeDirectory($path);
		}

		return true;
	}
}

if (!function_exists('command_log_name')) {
	/**
	 * command_log_name
	 *
	 * @param  string $command
	 * @return string
	 */
	function command_log_name($command)
	{
		$command = explode(' ', $command)[0] ?? null;

		$command = slugify($command);
		$command = $command . '.log';

		return $command;
	}
}

if (!function_exists('send_laravel_email')) {
	/**
	 * send_laravel_email
	 *
	 * @param array $to_emails
	 * @return mixed
	 */
	function send_laravel_email($to_emails)
	{
		$laravel_email = \SgtCoder\LaravelFunctions\LaravelEmail::send_laravel_email($to_emails);

		return $laravel_email;
	}
}
