<?php

if (!function_exists('get_countries')) {
    /**
     * get_countries
     *
     * @param  mixed $country
     * @return mixed
     */
    function get_countries($country = null)
    {
        $countries = '{"AF":"Afghanistan","AX":"land Islands","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"AndorrA","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","BN":"Brunei Darussalam","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos (Keeling) Islands","CO":"Colombia","KM":"Comoros","CG":"Congo","CD":"Congo, The Democratic Republic of the","CK":"Cook Islands","CR":"Costa Rica","CI":"Cote D\"Ivoire","HR":"Croatia","CU":"Cuba","CY":"Cyprus","CZ":"Czech Republic","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands (Malvinas)","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernsey","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and Mcdonald Islands","VA":"Holy See (Vatican City State)","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran, Islamic Republic Of","IQ":"Iraq","IE":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JE":"Jersey","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","KP":"Korea, Democratic People\"S Republic of","KR":"Korea, Republic of","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Lao People\"S Democratic Republic","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libyan Arab Jamahiriya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia, The Former Yugoslav Republic of","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia, Federated States of","MD":"Moldova, Republic of","MC":"Monaco","MN":"Mongolia","ME":"Montenegro","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","AN":"Netherlands Antilles","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian Territory, Occupied","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","RE":"Reunion","RO":"Romania","RU":"Russian Federation","RW":"RWANDA","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syrian Arab Republic","TW":"Taiwan, Province of China","TJ":"Tajikistan","TZ":"Tanzania, United Republic of","TH":"Thailand","TL":"Timor-Leste","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VE":"Venezuela","VN":"Viet Nam","VG":"Virgin Islands, British","VI":"Virgin Islands, U.S.","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}';

        $countries = json_decode($countries, true);

        if ($country) {
            return $countries[$country] ?? null;
        }

        return $countries;
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
    function get_states($state = null, $add_states = [])
    {
        $states = '{"AL":"Alabama","AK":"Alaska","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","FL":"Florida","GA":"Georgia","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PA":"Pennsylvania","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}';

        $states = json_decode($states, true);
        $states = array_merge($states, $add_states);

        asort($states);

        if ($state) {
            return $states[$state] ?? null;
        }

        return $states;
    }
}

if (!function_exists('get_provinces')) {
    /**
     * get_provinces
     *
     * @param  mixed $province
     * @return mixed
     */
    function get_provinces($province = null)
    {
        $provinces = '{"AB":"Alberta","BC":"British Columbia","MB":"Manitoba","NB":"New Brunswick","NL":"Newfoundland and Labrador","NS":"Nova Scotia","ON":"Ontario","PE":"Prince Edward Island","QC":"Quebec","SK":"Saskatchewan","YT":"Yukon","NT":"Northwest Territories","NU":"Nunavut"}';

        $provinces = json_decode($provinces, true);

        if ($province) {
            return $provinces[$province] ?? null;
        }

        return $provinces;
    }
}

if (!function_exists('get_states_html')) {
    /**
     * get_states_html
     *
     * @param  mixed $placeholder
     * @param  mixed $selected
     * @return mixed
     */
    function get_states_html($placeholder = null, $selected = null)
    {
        $states = get_states();

        $html = '';

        if ($placeholder) {
            $html = '<option value="">' . $placeholder . '</option>';
        }

        foreach ($states as $state_key => $state_value) {
            $html .= '<option value="' . $state_key . '"' . ($selected == $state_key ? ' selected' : '') . '>' . $state_value . '</option>';
        }

        return $html;
    }
}

if (!function_exists('get_provinces_html')) {
    /**
     * get_provinces_html
     *
     * @param  mixed $placeholder
     * @param  mixed $selected
     * @return mixed
     */
    function get_provinces_html($placeholder = null, $selected = null)
    {
        $provinces = get_provinces();

        $html = '';

        if ($placeholder) {
            $html = '<option value="">' . $placeholder . '</option>';
        }

        foreach ($provinces as $province_key => $province_value) {
            $html .= '<option value="' . $province_key . '"' . ($selected == $province_key ? ' selected' : '') . '>' . $province_value . '</option>';
        }

        return $html;
    }
}
