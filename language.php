<?php
if( !defined("MAP_API_KEY") ) die( "Error 1" );

function __($symbol) {
    $lang = [
        "purpose" => [
            "en" => "Please help VOA Persian improve your satellite TV reception.",
            "fa" => "لطفا به بخش فارسی صدای آمریکا برای دریافت تصاویر ماهواره ای بهتر کمک کنید"
        ],
        "disclaimer" => [
            "en" => "No personal information will be collected beyond signal location and quality.",
            "fa" => "بجز موقعیت محلی  و کیفیت سیگنالی که دریافت می کنید هیچگونه اطلاعات  خصوصی دیگری  جمع آوری  نخواهد شد"
        ],
        "click" => [
            "en" => "Please click or tap map to place marker where you are currently located. Use zoom buttons to enlarge the map.",
            "fa" => "لطفا محل فعلی خود را با کلیک  کردن یا گذاشتن علامت  به روی نقشه مشخص  کنید"
        ],
        "quality" => [
            "en" => "How is the quality of your satellite TV reception right now?",
            "fa" => "تصاویری که هم اکنون از ماهواره  می گیرید چه کیفیتی دارد؟"
        ],
        "clear" => [
            "en" => "Clear",
            "fa" => "شفاف است"
        ],
        "noisy" => [
            "en" => "Noisy",
            "fa" => "پارازیت دارد"
        ],
        "send" => [
            "en" => "Please click here to send your results.",
            "fa" => "برای ارسال پاسخ تان،  لطفا اینجا را کلیک کنید"
        ],
        "thanks" => [
            "en" => "Thank you.",
            "fa" => "متشکریم"
        ]
    ];

    // debug routine
    if( $symbol === true ) {

        // in case some translations get left out
        $all_languages = array();
        foreach( $lang as $sym => $languages ) {
            foreach( $languages as $language => $line ) {
                if( !isset($all_languages[$language]) ) $all_languages[$language] = 0;
                $all_languages[$language]++;
            }
        }

        echo "<meta charset='utf-8' />";
        echo "<table border='1'><tr><th>symbol</th><th>";
        echo implode("</th><th>", array_keys($all_languages) );
        echo "</tr>";

        foreach( $lang as $sym => $langs ) {
            echo "<tr>";
            echo "<td>{$sym}</td>";
            foreach( $all_languages as $language => $count ) {
                echo "<td>";
                if( !isset($lang[$sym][$language]) ) {
                    echo "";
                } else {
                    echo $lang[$sym][$language];
                }
                echo "</td>";
            }

            echo "</tr>";
        }
        echo "</table>";
        die;
    }

    if( !isset( $lang[$symbol]) ) return( $symbol );
    if( !isset( $lang[$symbol][LANG] ) ) return( $symbol );

    return( $lang[$symbol][LANG] );
}

// uncomment to debug all symbols
// __(true);
