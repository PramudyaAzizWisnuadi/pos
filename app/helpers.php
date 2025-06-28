<?php

if (!function_exists('setting_toko')) {
    function setting_toko($key = null)
    {
        $setting = \App\Models\SettingToko::getSetting();

        if ($key) {
            return $setting->$key ?? '';
        }

        return $setting;
    }
}
