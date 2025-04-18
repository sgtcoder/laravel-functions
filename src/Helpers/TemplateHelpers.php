<?php

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
