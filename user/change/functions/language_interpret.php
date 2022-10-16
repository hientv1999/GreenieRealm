<?php
    function decode($language) {
        $string = "";
        switch ($language) {
            case "af":
                $string =   "Afrikaans";
                break;
            case "ga":
                $string =   "Irish";
                break;
            case "sq":
                $string =   "Albanian";
                break;
            case "it":
                $string =   "Italian";
                break;
            case "ar":
                $string =   "Arabic";
                break;
            case "ja":
                $string =   "Japanese";
                break;
            case "az":
                $string =   "Azerbaijani";
                break;
            case "kn":
                $string =   "Kannada";
                break;
            case "eu":
                $string =   "Basque";
                break;
            case "ko":
                $string =   "Korean";
                break;
            case "bn":
                $string =   "Bengali";
                break;
            case "la":
                $string =   "Latin";
                break;
            case "be":
                $string =   "Belarusian";
                break;
            case "lv":
                $string =   "Latvian";
                break;
            case "bg":
                $string =   "Bulgarian";
                break;
            case "lt":
                $string =   "Lithuanian";
                break;
            case "ca":
                $string =   "Catalan";
                break;
            case "mk":
                $string =   "Macedonian";
                break;
            case "zh-CN":
                $string =   "Chinese Simplified";
                break;
            case "ms":
                $string =   "Malay";
                break;
            case "zh-TW":
                $string =   "Chinese Traditional";
                break;
            case "mt":
                $string =   "Maltese";
                break;
            case "hr":
                $string =   "Croatian";
                break;
            case "no":
                $string =   "Norwegian";
                break;
            case "cs":
                $string =   "Czech";
                break;
            case "fa":
                $string =   "Persian";
                break;
            case "da":
                $string =   "Danish";
                break;
            case "pl":
                $string =   "Polish";
                break;
            case "nl":
                $string =   "Dutch";
                break;
            case "pt":
                $string =   "Portuguese";
                break;
            case "en":
                $string =   "English";
                break;
            case "ro":
                $string =   "Romanian";
                break;
            case "eo":
                $string =   "Esperanto";
                break;
            case "ru":
                $string =   "Russian";
                break;
            case "et":
                $string =   "Estonian";
                break;
            case "sr":
                $string =   "Serbian";
                break;
            case "tl":
                $string =   "Filipino";
                break;
            case "sk":
                $string =   "Slovak";
                break;
            case "fi":
                $string =   "Finnish";
                break;
            case "sl":
                $string =   "Slovenian";
                break;
            case "fr":
                $string =   "French";
                break;
            case "es":
                $string =   "Spanish";
                break;
            case "gl":
                $string =   "Galician";
                break;
            case "sw":
                $string =   "Swahili";
                break;
            case "ka":
                $string =   "Georgian";
                break;
            case "sv":
                $string =   "Swedish";
                break;
            case "de":
                $string =   "German";
                break;
            case "ta":
                $string =   "Tamil";
                break;
            case "el":
                $string =   "Greek";
                break;
            case "te":
                $string =   "Telugu";
                break;
            case "gu":
                $string =   "Gujarati";
                break;
            case "th":
                $string =   "Thai";
                break;
            case "ht":
                $string =   "Haitian Creole";
                break;
            case "tr":
                $string =   "Turkish";
                break;
            case "iw":
                $string =   "Hebrew";
                break;
            case "uk":
                $string =   "Ukrainian";
                break;
            case "hi":
                $string =   "Hindi";
                break;
            case "ur":
                $string =   "Urdu";
                break;
            case "hu":
                $string =   "Hungarian";
                break;
            case "vi":
                $string =   "Vietnamese";
                break;
            case "is":
                $string =   "Icelandic";
                break;
            case "cy":
                $string =   "Welsh";
                break;
            case "id":
                $string =   "Indonesian";
                break;
            case "yi":
                $string =   "Yiddish";
                break;
        }
        return $string;
    }

    function encode($language){
        $string = "";
        switch ($language) {
            case "Afrikaans":
                $string =  "af" ;
                break;
            case "Irish":
                $string = "ga"  ;
                break;
            case "Albanian":
                $string = "sq"  ;
                break;
            case "Italian":
                $string =  "it" ;
                break;
            case "Arabic":
                $string =  "ar" ;
                break;
            case "Japanese":
                $string = "ja"  ;
                break;
            case "Azerbaijani":
                $string = "az"  ;
                break;
            case "Kannada":
                $string = "kn"  ;
                break;
            case "Basque":
                $string =  "eu" ;
                break;
            case "Korean":
                $string = "ko"  ;
                break;
            case "Bengali":
                $string =  "bn" ;
                break;
            case "Latin":
                $string =  "la" ;
                break;
            case "Belarusian":
                $string = "be"  ;
                break;
            case "Latvian":
                $string = "lv"  ;
                break;
            case "Bulgarian":
                $string = "bg"  ;
                break;
            case "Lithuanian":
                $string = "lt"  ;
                break;
            case "Catalan":
                $string = "ca"  ;
                break;
            case "Macedonian":
                $string = "mk"  ;
                break;
            case "Chinese Simplified":
                $string =  "zh-CN" ;
                break;
            case "Malay":
                $string = "ms"  ;
                break;
            case "Chinese Traditional":
                $string = "zh-TW"  ;
                break;
            case "Maltese":
                $string =  "mt" ;
                break;
            case "Croatian":
                $string = "hr"  ;
                break;
            case "Norwegian":
                $string = "no"  ;
                break;
            case "Czech":
                $string = "cs"  ;
                break;
            case "Persian":
                $string =  "fa" ;
                break;
            case "Danish":
                $string = "da"  ;
                break;
            case "Polish":
                $string = "pl"  ;
                break;
            case "Dutch":
                $string =  "nl" ;
                break;
            case "Portuguese":
                $string = "pt"  ;
                break;
            case "English":
                $string = "en"  ;
                break;
            case "Romanian":
                $string = "ro"  ;
                break;
            case "Esperanto":
                $string =  "eo" ;
                break;
            case "Russian":
                $string = "ru"  ;
                break;
            case "Estonian":
                $string = "et"  ;
                break;
            case "Serbian":
                $string = "sr"  ;
                break;
            case "Filipino":
                $string =  "tl" ;
                break;
            case "Slovak":
                $string =  "sk" ;
                break;
            case "Finnish":
                $string = "fi"  ;
                break;
            case "Slovenian":
                $string = "sl"  ;
                break;
            case "French":
                $string =  "fr" ;
                break;
            case "Spanish":
                $string = "es"  ;
                break;
            case "Galician":
                $string = "gl"  ;
                break;
            case  "Swahili":
                $string = "sw" ;
                break;
            case "Georgian":
                $string = "ka"  ;
                break;
            case "Swedish":
                $string = "sv"  ;
                break;
            case "German":
                $string = "de"  ;
                break;
            case "Tamil":
                $string = "ta"  ;
                break;
            case "Greek":
                $string = "el"  ;
                break;
            case "Telugu":
                $string = "te"  ;
                break;
            case "Gujarati":
                $string = "gu"  ;
                break;
            case "Thai":
                $string = "th"  ;
                break;
            case "Haitian Creole":
                $string = "ht"  ;
                break;
            case "Turkish":
                $string = "tr"  ;
                break;
            case "Hebrew":
                $string =  "iw" ;
                break;
            case "Ukrainian":
                $string = "uk"  ;
                break;
            case "Hindi":
                $string = "hi"  ;
                break;
            case "Urdu":
                $string = "ur"  ;
                break;
            case "Hungarian":
                $string = "hu"  ;
                break;
            case "Vietnamese":
                $string = "vi"  ;
                break;
            case "Icelandic":
                $string = "is"  ;
                break;
            case "Welsh":
                $string = "cy"  ;
                break;
            case "Indonesian":
                $string =  "id" ;
                break;
            case "Yiddish":
                $string =  "yi" ;
                break;
        }
        return $string;
    }
?>