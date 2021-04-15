<?php 

/**
* Throw an alert
* 
* @param string $title Title of the alert
* @param mixed $text Content of the alert
* @param string $type Type of the alert
* @return mixed
*/
function throw_alert($title, $text, $type)
{
    echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>{$title}</strong> {$text}</div>";
}

/**
* Checker if selected string contains an selected word
* 
* @param string $haystack Selected string
* @param string $needle Selected word
* @return bool
*/
function str_contains_helper($haystack, $needle)
{
    return strpos($haystack, $needle) !== false;
}
/**
* Generetes code for 2fa
*
* @return int
*/
function generatePIN($digits = 6){
    $i = 0;
    $pin = "";
    while($i < $digits){
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}


/**
* Captcha code generator
*
* @return int
*/
function generateCaptcha()
{
    return rand(10000, 99999);
}

function redirect_to($address, $time)
{
    echo "<meta http-equiv='refresh' content='{$time};url={$address}'>";
}

function getValue($json, $section, $value)
{
    $arr = json_decode($json['data'], true);
    return $arr[$section][$value];
}

function getFromJson($json, $section, $value, $section2 = false)
{
    $arr = json_decode($json, true);
    return $arr[$section][$value]; 
}

function createLink($string)
{
    $string = str_replace(' ', '', $string);
    $string = htmlspecialchars($string);
    return $string;
}

function getDates()
{
    $now = time();
    $your_date = strtotime("1 Januar 2016");
    $datediff = $now - $your_date;

    $date = (string) floor($datediff / (60 * 60 * 24));
    $fl = 0;
    for ($i=0; $i < count($date); $i++) { 
        if(count($date)>1){
            $fl = str_split($date)[0].'.'.$date[$i+1];
        }else{
            $fl = '0.'.str_split($date)[0];
        }
    }
    echo $fl;
}

function toDate($timestamp)
{
    return date('d.m.Y H:i:s', $timestamp);
}

function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}

function ref_format($string1, $string2,$str, $step, $reverse = false) {
 
    if ($reverse)
        return strrev(chunk_split(strrev($str), $step, ' '));

    return chunk_split($str, $step, "F5y".$string2."4658F".$string1."G891Y657F4152".$string2."F15A6y25");
}

function mysql_password($password)
{
    $text = "The Quick : Brown Fox Jumped Over The Lazy / Dog";
    $mypassword = "%F20Lan-Host_C-Ry_pted".$password."/%F20--E";
    $middle = strrpos(substr($text, 0, floor(strlen($text) / 2)), ' ') + 1;
    $string1 = substr(md5($mypassword), 0, $middle);
    $string2 = substr(md5($mypassword), $middle);
    $string11 = ref_format($string2,$string1,$string1, 3);
    $string12 = ref_format($string1,$string2,$string1, 7);
    $final = "_Cr-_C_Cr-546fds123546765".$string12."_r-Y-p_Cred_-pt-ed_".$string11."_Cr-Y_".$string12."Cr-pt".$string1."-ed_-Y-".$string11."pt-ed_pt-ed_-".$string2."-Crypt".$string12."ed-4FLAN-".$string2."H0S".$string11."T9F-Crypted-";
    $final2 = $string11."F1XC68".md5($final)."B9Y148F".$string12;
    return hash('sha256', $final2);
}

function generateRandomString($random_strings)
{
    $Characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $QuantiteCharacter = strlen($Characters);
    $QuantiteCharacter--;
    $final_string = NULL;

    for ($x = 1; $x <= $random_strings; $x++) {
        $position = rand(0, $QuantiteCharacter);
        $final_string .= substr($Characters, $position, 1);
    }

    return $final_string;
}
