<?php
namespace App\Traits;

trait UtilsTrait
{
    /**
     * Generate Random String Token From A Set Of Chars
     * @param $length Length of retun string token
     */
    public function generateStringToken($length = 6)
    {
        $string_set = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string_length = strlen($string_set);
        $string = str_repeat($string_set, ceil($length / $string_length));
        $shuffled_string = str_shuffle($string);
        $random_string = substr($shuffled_string, 1, $length);
        return $random_string;
    }
}
