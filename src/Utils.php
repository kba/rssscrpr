<?php

class Utils
{

    static function contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }

    static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    static function throw400($msg)
    {
        http_response_code(400);
        header('Content-Type: text/plain');
        echo $msg;
        exit;
    }

    static function var_error_log( $object=null ){
        ob_start();                    // start buffer capture
        var_dump( $object );           // dump the values
        $contents = ob_get_contents(); // put the buffer into a variable
        ob_end_clean();                // end capture
        error_log( $contents );        // log contents of the result of var_dump( $object )
    }

    static function trimHard($str)
    {
        $str = trim($str);
        $str = preg_replace('/^[^A-Za-z0-9]+/', '', $str);
        $str = preg_replace('/[^A-Za-z0-9]+$/', '', $str);
        return $str;
    }

    static function ensureAbsoluteUrl($url, $compareUrl)
    {
        if (substr($url, 0, 4) == 'http') {
            return $url;
        } else {
            $urlParsed = parse_url($compareUrl);
            $result = $urlParsed['scheme'] .  '://' . $urlParsed['host'];
            // TODO chomp trailing slashes and then implode for clarity
            if ( strlen(dirname($urlParsed['path'])) > 1 && // i.e. not just /
                substr($url, 0, 1) !== "/" ) {// 
                if (substr($urlParsed['path'],-1) == "/") {
                    $result .= $urlParsed['path'];
                } else {
                    $result .= dirname($urlParsed['path']) . '/';
                }
            }
            return $result . $url;
        }
    }

}

?>
