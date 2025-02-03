<?php


if (!function_exists('fkr')) {
    function fkr($lang = 'ar', $class = null)
    {
        if ($lang == "ar") {
            $lang = "ar_SA";
        } elseif ($lang == "en") {
            $lang = "en_US";
        }

        $fkr = Faker\Factory::create($lang);
        if (!empty($class)) {
            $myclass = 'Faker\\Provider\en_US\\' . $class;
            $fkr->addProvider(new $myclass($fkr));
        }
        return $fkr;
    }
}




if (!function_exists('randImage')) {
    function randImage()
    {
        $randomImages = [
            asset('img/R.jpg'),
            asset('img/www.naturephotographie.com-purple-duplication.jpg'),
            asset('img/Above-Keem-Bay.jpg'),
        ];
        return $randomImages[rand(0, 2)];
    }
}

if (!function_exists('randIcon')) {
    function randIcon()
    {
        $randIcon = [
            asset('img/icons/facebook.png'),
            asset('img/icons/instagram.png'),
            asset('img/icons/snapchat.png'),
            asset('img/icons/twitter.png'),
            asset('img/icons/whatsapp.png'),
            asset('img/icons/youtube.png'),
        ];
        return $randIcon[rand(0, 5)];
    }
}
