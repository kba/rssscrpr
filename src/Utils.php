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

    static function monthNameToNumber($str)
    {
        $str = self::translateMonth($str);
        $replacements = array(
            "January" => '01',
            "February" => '02',
            "March" => '03',
            "April" => '04',
            "May" => '05',
            "June" => '06',
            "July" => '07',
            "August" => '08',
            "September" => '09',
            "October" => '10',
            "November" => '11',
            "December" => '12',
        );
        foreach ($replacements as $de => $en)
        {
            $str = str_replace($de, $en, $str);
        }
        return $str;
    }

    static function translateMonth($str)
    {
        $replacements = array(
            "Januar" => "January",
            "Jänner" => "January",
            "Februar" => "February",
            "März" => "March",
            "Mai" => "May",
            "Juni" => "June",
            "Juli" => "July",
            "Oktober" => "October",
            "Dezember" => "December"
        );
        foreach ($replacements as $de => $en)
        {
            $str = str_replace($de, $en, $str);
        }
        return $str;
    }


}

?>
