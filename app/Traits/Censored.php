<?php

namespace App\Traits;

trait Censored
{
    public function applyCensored($toCensored) {
        // Split the name into parts (assuming it's first name and last name separated by a space)
        $name_parts = explode(" ", $toCensored);

        // Get the first name (if available)
        $first_name = isset($name_parts[0]) ? $name_parts[0] : '';

        // Censor the first name
        $censored_first_name = $this->censoredStrings($first_name);

        // Get the last name (if available)
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

        // Censor the last name
        $censored_last_name = $this->censoredStrings($last_name);

        // Combine censored first and last names
        $censored_name = $censored_first_name . " " . $censored_last_name;

        return $censored_name;
    }

    public function censoredStrings($str) {
        // Get the length of the string
        $length = strlen($str);

        // If the length is less than or equal to 2, return the original string
        if ($length <= 2) {
            return $str;
        }

        // Get the first two characters of the string
        $first_two_chars = substr($str, 0, 2);

        // Create a string of asterisks with the same length as the remaining characters
        $remaining_asterisks = str_repeat("*", $length - 2);

        // Combine the first two characters with the asterisks
        $censored_str = $first_two_chars . $remaining_asterisks;

        return $censored_str;
    }
}
