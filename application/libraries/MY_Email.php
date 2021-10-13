<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Email extends CI_Email
{

    public function _validate_email_for_shell(&$address)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
    }
}


// if (function_exists('idn_to_ascii') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches)) {
//     $domain = defined('INTL_IDNA_VARIANT_UTS46')
//         ? idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46)
//         : idn_to_ascii($matches[2]);
//     $str = $matches[1] . '@' . $domain;
// }

// return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);