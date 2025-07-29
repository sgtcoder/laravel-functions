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

if (!function_exists('get_signed_url')) {
    /**
     * get_signed_url
     *
     * @param  mixed $media
     * @return mixed
     */
    function get_signed_url($media)
    {
        return media_service()->get_signed_url($media);
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
    function sync_media($prefix, $model = null, $single = true, $media_prefix = null)
    {
        $PlankMediaClass = get_plank_media_class();

        $media_prefix ??= $prefix;

        if (request($prefix . '_delete')) {
            if (is_array(request($prefix . '_delete'))) {
                foreach (request($prefix . '_delete') as $item) {
                    $media = $PlankMediaClass::find($item);
                    if ($media) $media->delete();
                }
            } else {
                if ($model && $model->firstMedia($prefix)) $model->firstMedia($media_prefix)->delete();

                $media_delete = $PlankMediaClass::find($media_prefix);
                if ($media_delete) $media_delete->delete();
            }
        }

        // Crop new images
        if (request($prefix . '_media') && is_array(request($prefix . '_media'))) {
            foreach (request($prefix . '_media') as $item) {
                $media_data = request($prefix . '_data.' . $item);
                if ($media_data) {
                    // @phpstan-ignore-next-line
                    media_service()->update_media_crop($item, $media_data);
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

        return ($single) ? collect(request($prefix . '_media'))->values()->first() : request($prefix . '_media');
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
