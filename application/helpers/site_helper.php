<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define("DS", "\\");
} else {
    define("DS", "/");
}

function get_cur_cache_bust()
{
    return '20201124111000';
}


if (! function_exists('get_film_id')) {
    function get_film_id()
    {
        return 0;
    }
}

if (! function_exists('get_page_nav_link')) {
    function get_page_nav_link($data)
    {
        $local_array = get_sections_array();

        if (isset($data['controller']) && strlen($data['controller']) > 0) {
            if (in_array($data['nav_item'], $local_array)) {
                return base_url() . '#' . $data['nav_item'];
            } else {
                return base_url() . $data['nav_item'];
            }
        } elseif (in_array($data['nav_item'], $local_array)) {
            return '#' . $data['nav_item'];
        } else {
            return base_url() . $data['nav_item'];
        }
    }
}

  
  if (! function_exists('get_nav_active')) {
      function get_nav_active($data)
      {
          if (isset($data['menu_active']) && ! is_array($data['menu_active'])) {
              if ($data['menu_active'] == $data['nav_item']) {
                  if (! empty($data['add_class'])) {
                      return 'current_page_item active';
                  } else {
                      return 'class="active"';
                  }
              } else {
                  return null;
              }
          }
          if (! empty($data['nav_item']) && $data['nav_item'] == 'home' && empty($data['controller'])) {
              if (! empty($data['add_class'])) {
                  return 'current_page_item active';
              } else {
                  return 'class="active"';
              }
          }
        
          if (isset($data['controller']) && strlen($data['controller']) > 0) {
              $data['controller'] = str_replace('#', '', $data['controller']);
              if ($data['controller'] == $data['nav_item']) {
                  if (! empty($data['add_class'])) {
                      return 'current_page_item active';
                  } else {
                      return 'class="active"';
                  }
              } else {
                  return null;
              }
          }
      }
  }
 
 if (! function_exists('correct_img_path')) {
     function correct_img_path($src)
     {
         $base 	= base_url();
         $src 		= str_replace('[BASEURL]', $base, $src);
         $src 		= str_replace('[BASE_URL]', $base, $src);
         $src 		= str_replace('[BASE]', $base, $src);
                
         return $src;
     }
 }
 
 
 if (! function_exists('simple_date_offset')) {
     function simple_date_offset($format, $time = null, $offset = null, $alt_format=null, $default_tz=null)
     {
         $offset_name   = !empty($_SESSION['user_timezone_name']) ? $_SESSION['user_timezone_name'] : null;
         $offset        = !empty($offset) ? $offset : $offset_name;
         $r             = $time;
         
         if (!empty($offset) && !empty($time)) {
             date_default_timezone_set($offset);
            
             if ($format == 'to_utc') {
                 $time               = date('U', strtotime(gmdate('Y-m-d H:i:s', $time)));
                 $format             = $alt_format;
             }
            
             switch ($format) {
                case 'unix':
                case 'timestamp':
                    
                    $r = date('U', $time);
                    
                break;
                default:
                    
                    $r = date($format, $time);
                    
                break;
            }
            
            
             date_default_timezone_set('UTC');
         }
         
         if (empty($offset) && !is_numeric($r) && !empty($default_tz)) {
             $r .= ' '.$this->events_model->get_tz_abbrev($default_tz);
         }

         return $r;
     }
 }
 
 if (! function_exists('date_offset')) {
     function date_offset($format, $time=null, $offset=null, $observe_dst=false, $offset_backup=null, $to_utc=false)
     {
         $dst_offset		= 0;
         $is_dst			= date('I');
         $dst_check         = null;
         
         if (empty($time)) {
             $time 		= time();
         }
         
         if (!is_numeric($time)) {
             $time = strtotime($time);
         }
         
         if (empty($offset) && !empty($offset_backup) && empty($_SESSION['user_timezone_offset'])) {
             $offset = $offset_backup;
         }
         
         //dds($offset);
         
         if (empty($offset) && !empty($_SESSION['user_timezone_offset'])) {
             $offset 	= $_SESSION['user_timezone_offset'];
         
             if (!empty($offset)) {
                 $offset 	= str_replace('GMT ', '', $offset);
                 
                 if ($offset[0] == '-') {
                     $offset 	= trim($offset, '-');
                     $offset	= $offset * 3600;
                     $offset	= '-'.$offset;
                 } else {
                     $offset 	= trim($offset, '+');
                     $offset	= $offset * 3600;
                     $offset	= '+'.$offset;
                 }
             }
         }
        
        

         if (!empty($offset) && !is_numeric($offset)) {
             $tz_name		= $offset;
             $tz 			= new DateTimeZone($offset);
             $test_date		= new DateTime();
             $test_date->setTimestamp($time);
             $offset 		= $tz->getOffset($test_date);
            
             //Check for DST compensation
             date_default_timezone_set($tz_name);
            
             $dst_check		= date('I', strtotime(date('Y-m-d H:i:s', $time)));
            
             date_default_timezone_set('UTC');
         }
        
         if ($observe_dst) {
             if ($is_dst && $is_dst != $dst_check) {
                 $dst_offset	= -3600;
             } elseif (!$is_dst && $is_dst != $dst_check) {
                 $dst_offset	= 3600;
             }
         }
        
         //Reverse time zone offset to get us back to UTC
         if ($to_utc) {
             if (stripos($offset, '-') !== false) {
                 $offset = str_replace('-', '+', $offset);
             } else {
                 $offset = str_replace('+', '-', $offset);
             }
         }
        
        
         if (!empty($offset)) {
             if (stripos($offset, '-') !== false) {
                 $offset 	= trim($offset, '-');
                 $time 		= $time - $offset + $dst_offset;
             } else {
                 $offset 	= trim($offset, '+');
                 $time 		= $time + $offset - $dst_offset;
             }
         }

        
         if (empty($format) || (!empty($format) && $format == 'unix')) {
             return $time;
         }
         return date($format, $time);
     }
 }

 if (! function_exists('simple_date_to_utc')) {
     function simple_date_to_utc($format, $time=null, $offset=null)
     {
         return simple_date_offset('to_utc', $time, $offset, $format);
     }
 }
 
 if (! function_exists('date_to_utc')) {
     function date_to_utc($format, $time=null, $offset=null, $force_localization=null)
     {
         if (empty($time)) {
             $time 		= time();
         }
         
         if ((empty($offset) || !empty($force_localization)) && !empty($_SESSION['user_timezone_name'])) {
             $offset 	= $_SESSION['user_timezone_name'];
         } elseif ((empty($offset) || !empty($force_localization)) && !empty($_SESSION['user_timezone_offset'])) {
             $offset 	= $_SESSION['user_timezone_offset'];
         }
         
         
         
         if (!empty($offset)) {
             $time = date_offset(null, $time, $offset, null, null, true);
         }
         
         
         /*
         if(empty($offset) && !empty($_SESSION['user_timezone_offset'])){
             $offset 		= str_replace('GMT ', '', $_SESSION['user_timezone_offset']);

         }elseif(!empty($offset) && !is_numeric($offset)){
            $tz 			= new DateTimeZone($offset);
            //$offset 		= $tz->getOffset(new DateTime);
            $transition 	= $tz->getTransitions($time);
            $offset			= $transition[0]['offset'];
            $offset 		= $offset / 3600;
         }

         if(!empty($offset)){
             if(stripos($offset, '-') !== false){
                 $offset 	= trim($offset,'-');
                 if($offset > 24){
                    $offset = $offset / 3600;
                 }
                 $time 		= $time + ($offset * 3600);
             }else{
                 $offset 	= trim($offset,'+');
                  if($offset > 24){
                    $offset = $offset / 3600;
                 }
                 $time 		= $time - ($offset * 3600);
             }
         }
         */
         
         
         switch (strtolower($format)) {
             case 'unix':
             case 'seconds':
                return $time;
             break;
             default:
                return date($format, $time);
             break;
         }
     }
 }
 
  if (! function_exists('format_date')) {
      function format_date($time=null, $format=null, $offset=null, $force_localization=null)
      {
          if (empty($time)) {
              $time = time();
          }
         
          if ((empty($offset) || !empty($force_localization)) && !empty($_SESSION['user_timezone_name'])) {
              $offset 	= $_SESSION['user_timezone_name'];
          } elseif ((empty($offset) || !empty($force_localization)) && !empty($_SESSION['user_timezone_offset'])) {
              $offset 	= $_SESSION['user_timezone_offset'];
          }
         
         
         
          if (!empty($offset)) {
              $time = date_offset(null, $time, $offset);
          }
         
         
          /*
          if(!empty($offset)){

              if(!is_numeric($offset) && strpos($offset, 'GMT') !== true){
                 $timezone 		= new DateTimeZone($offset);
                 $test_date		= new DateTime();
                 $test_date->setTimestamp($time);
                 $offset 		= $timezone->getOffset($test_date);
                 //$offset   		= $timezone->getOffset(new DateTime);
              }
          }

          if(empty($offset) && !empty($_SESSION['user_timezone_offset'])){
              $offset 	= $_SESSION['user_timezone_offset'];
          }


          if(!empty($offset)){
              $offset 			= str_replace('GMT ', '', $offset);
              $offset_check	= str_replace('-', '',$offset);
              if($offset_check > 23){
                  if($offset[0] == '-'){
                     $offset 			= trim($offset,'-');
                     $time 			= $time - $offset;
                  }else{
                      $offset 		= trim($offset,'+');
                      $time 			= $time + $offset;
                  }
              }else{
                   if($offset[0] == '-'){
                      $offset 	= trim($offset,'-');
                      $time 		= $time - ($offset * 3600);
                  }else{
                      $offset 	= trim($offset,'+');
                      $time 		= $time + ($offset * 3600);
                  }

              }


          }

          */
         
          switch ($format) {
             case 'dateonly':
             case 'date_only':
                $r = date('m/d/Y', $time);
            break;
            case 'caldateonly':
            case 'cal_date_only':
            case 'd':
                $r = date('d', $time);
            break;
            case 'l':
            case 'dotw':
                $r = date('l', $time);
            break;
            case 'cal_month_abrev':
            case 'M':
                $r = date('M', $time);
            break;
            case 'cal_month_abrev':
                $r = date('M', $time);
            break;
            case 'time':
                $r = date('g:iA', $time);
             break;
             case 'long_date':
                $r = date('l, F jS, Y', $time);
             break;
             case 'unix':
                return $time;
             break;
             default:
                $r = date('m/d/Y h:iA', $time);
             break;

         }
          return $r;
      }
  }
 
 if (! function_exists('get_img_base')) {
     function get_img_base($data=null)
     {
         return base_url().'img/';
     }
 }
 
  if (! function_exists('get_css_base')) {
      function get_css_base($data=null)
      {
          return base_url().'css/';
      }
  }
    
 if (! function_exists('get_site_template')) {
     function get_site_template()
     {
         return 'a';
     }
 }
 
  if (! function_exists('get_img_path')) {
      function get_img_path($data=null)
      {
          if (isset($data['film_nick'])) {
              return $_SERVER['DOCUMENT_ROOT'].'img/'.strtoupper($data['film_nick']).'/';
          } else {
              return $_SERVER['DOCUMENT_ROOT'].'img/';
          }
      }
  }
 
if (! function_exists('format_phone')) {
    function format_phone($phone)
    {
        if (empty($phone)) {
            return null;
        }
        
        if (!empty($_SERVER['ps_site']) && strtolower($_SERVER['ps_site'])== 'demo') {
            return 'xxx-xxx-xxxx';
        }
        
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) == 7) {
            return preg_replace("/(\d{3})(\d{4})/", "$1-$2", $phone);
        } elseif (strlen($phone) == 10) {
            return preg_replace("/(\d{3})(\d{3})(\d{4})/", "$1-$2-$3", $phone);
        } elseif (strlen($phone) == 11) {
            return preg_replace("/(\d{1})(\d{3})(\d{3})(\d{4})/", "$1-$2-$3-$4", $phone);
        } else {
            return $phone;
        }
    }
}
if (! function_exists('format_email')) {
    function format_email($email)
    {
        
        /*
        if(!empty($_SERVER['ps_site']) && strtolower($_SERVER['ps_site'] )== 'demo'){

            return 'test@foo.foo';
        }
        */
        
        return strtolower(str_replace(' ', '', $email));
    }
}

if (! function_exists('print_array')) {
    function print_array($arr, $title=null)
    {
        if (isset($title)) {
            echo '<h3>'.$title.'</h3>';
        }
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}
if (! function_exists('dd')) {
    function dd($arr, $title=null)
    {
        if (isset($title)) {
            echo '<h3>'.$title.'</h3>';
        }
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}
if (! function_exists('dds')) {
    function dds($arr, $title=null)
    {
        //if(is_localhost() || !empty($_GET['force_debug'])){
        if (isset($title)) {
            echo '<h3>'.$title.'</h3>';
        }
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        exit();
        //}
    }
}
if (! function_exists('get_current_url')) {
    function get_current_url()
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
}

if (! function_exists('is_local_asset')) {
    function is_local_asset($url)
    {
        $url = strtolower($url);
        if (strpos($url, 'https://') === false && strpos($url, 'http://') === false) {
            return true;
        }
        
        return false;
    }
}

if (! function_exists('get_salt')) {
    function get_salt()
    {
        $output = '#a!%@#!@&#32141235^$#%$!*&^%^&gfdadSDsadHF@)!#@#FED(!&)';
        return $output;
    }
}

if (! function_exists('saltit')) {
    function saltit($input)
    {
        $salt	= get_salt();
        $input 	= $input.$salt;
        $output	= base64_encode($input);
        return $output;
    }
}

if (! function_exists('unsalt')) {
    function unsalt($input)
    {
        $input 	= base64_decode($input);
        $salt	= get_salt();
        $output	= str_replace($salt, '', $input);
        return $output;
    }
}
if (! function_exists('alpha_only')) {
    function alpha_only($text)
    {
        return preg_replace("/[^A-Za-z]+/", "", $text);
    }
}

if (! function_exists('numeric_only')) {
    function numeric_only($text)
    {
        return preg_replace('/\D/', '', $text);
    }
}

if (! function_exists('alpha_numeric_only')) {
    function alpha_numeric_only($text)
    {
        return preg_replace("/[^a-zA-Z0-9]+/", "", $text);
    }
}

if (! function_exists('url_dec')) {
    function url_dec($input)
    {
        if (empty(htmlspecialchars(base64_decode($input, true)))) {
            //return $input;
        }
        
        if (is_numeric($input)) {
            return $input;
        }
        
        $output 	= base64_decode(urldecode($input));
        
        return $output;
    }
}

if (! function_exists('url_enc')) {
    function url_enc($input)
    {
        $output = urlencode(base64_encode($input));
        $output = str_replace('%3D%3D', '', $output);
        return $output;
    }
}

if (! function_exists('stringify')) {
    function stringify($input)
    {
        $output 	= alpha_numeric_only(strtolower($input));
        return $output;
    }
}

if (! function_exists('slugify')) {
    function slugify($text)
    {
  
      // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

if (! function_exists('get_submitted_by_human_challenge')) {
    function get_submitted_by_human_challenge($input=null)
    {
        if (!empty($input) && is_numeric($input)) {
            $time 	= $input;
        } else {
            $time 	= time();
        }
            
        $enc 	= saltit(base64_encode($time));
        return $enc;
    }
}

if (! function_exists('dec_submitted_by_human_challenge')) {
    function dec_submitted_by_human_challenge($val)
    {
        $val 	= unsalt($val);
        $val 	= base64_decode($val);
        return $val;
    }
}

if (! function_exists('submitted_by_human')) {
    function submitted_by_human($load_time)
    {
        $offset			= 5;
        $submit_time	= time();
        $cur_offset		= $submit_time - $offset;
        
        $load_time 		= dec_submitted_by_human_challenge($load_time);
        
        //Check to see if the offset is greater than
        if (!empty($load_time) && is_numeric($load_time)) {
            if ($load_time <= $cur_offset) {
                return true;
            }
        }
        return false;
    }
}
if (! function_exists('domain_exists')) {
    function domain_exists($email)
    {
        list($user, $domain) 	= explode('@', $email);
        
        if (checkdnsrr($domain, 'MX')) {
            return true;
        }
        if (checkdnsrr($domain, 'A')) {
            return true;
        }
         
        return false;
    }
}
$mx_record_really_exists = getmxrr('test.domain.org.', $mx_records_output_array);

if (! function_exists('get_affiliate_id')) {
    function get_affiliate_id()
    {
        if (!isset($_GET['affiliate']) && !isset($_GET['campaign'])) {
            return null;
        }
            
        if (isset($_GET['campaign']) && strlen($_GET['campaign']) > 0) {
            if ($_GET['campaign'] == 'affiliatereset') {
                $r['vhx_affiliate'] 		= null;
            } else {
                $r['vhx_affiliate'] 		= $_GET['campaign'];
            }
        } elseif ($_GET['affiliate']) {
            if ($_GET['affiliate'] == 'affiliatereset') {
                $r['vhx_affiliate'] 		= null;
            } else {
                $_GET['affiliate'] 			= 'partner-'.$_GET['affiliate'];
                $r['vhx_affiliate'] 		= str_replace('partner-partner-', 'partner-', $_GET['affiliate']);
            }
        }

        
        return $r;
    }
}
if (! function_exists('format_affiliate_link')) {
    function format_affiliate_link($link, $affiliate)
    {
        $platform = '';
        if (isset($link) && isset($affiliate)) {
            $link 			= strtolower($link);
            $alt_affiliate	= str_replace('partner-', '', $affiliate);
            
            if (strpos($link, 'vhx.tv')) {
                $platform 	= 'vhx';
            }
            if (strpos($link, 'vimeo.com')) {
                $platform 	= 'vimeo';
            }
            if (strpos($link, 'amazon.com')) {
                $platform 	= 'amazon';
            }
            if (strpos($link, 'itunes.com')) {
                $platform 	= 'itunes';
            }
            if (strpos($link, 'shopify.com')) {
                $platform 	= 'shopify';
            }
            if (strpos($link, 'lifeismymovie.us')) {
                $platform 	= 'shopify';
            }
            if (strpos($link, 'youtube.com')) {
                $platform 	= 'youtube';
            }
            
            switch ($platform) {
                case 'vhx':
                    $append = 'campaign='.$affiliate;
                break;
                default:
                    $append = 'affiliate='.$alt_affiliate;
                break;
            }
            
            if (strpos($link, '?')) {
                $link .= '&'.$append;
            } else {
                $link .= '?'.$append;
            }
        }
        
        return $link;
    }
}

if (! function_exists('is_localhost')) {
    function is_localhost()
    {
        $whitelist = array('127.0.0.1', "::1", "otto.local", "ps.localhost", "localhost");
        if (!empty($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['HTTP_HOST'])) {
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist) && !in_array($_SERVER['HTTP_HOST'], $whitelist)) {
                return false;
            }
        }
    
        return true;
    }
}

if (! function_exists('get_var')) {
    function get_var($var_name, $default_val=null, $save_session=null)
    {
        $got			= $_GET;
        $posted	= $_POST;
        $session 	= $_SESSION;
        $val			= null;
        
        if (isset($got[$var_name]) && strlen($got[$var_name]) > 0) {
            $val		= $got[$var_name];
        }
        
        if (!$val && isset($posted[$var_name]) && strlen($posted[$var_name]) > 0) {
            $val		= $posted[$var_name];
        }
        
        if (!$val && isset($session['vars'][$var_name]) && strlen($session['vars'][$var_name]) > 0) {
            $val		= $session['vars'][$var_name];
        }
        
        if (!$val && isset($default_val) && strlen($default_val) >0) {
            $val		= $default_val;
        }
        
        if (isset($save_session)) {
            $_SESSION['vars'][$var_name]		= $val;
        }
        
        return $val;
    }
}

if (! function_exists('remove_line_breaks')) {
    function remove_line_breaks($string=null)
    {
        return preg_replace("/\r|\n/", "", $string);
    }
}
if (! function_exists('convert_encoding')) {
    function convert_encoding($string=null, $to_encoding='UTF-8', $from_encoding = '')
    {
        if ($from_encoding == '') {
            $from_encoding = detect_encoding($string);
        }

        if ($from_encoding == $to_encoding) {
            return $string;
        }

        return mb_convert_encoding($string, $to_encoding, $from_encoding);
    }
}
if (! function_exists('detect_encoding')) {
    function detect_encoding($string)
    {
        //http://w3.org/International/questions/qa-forms-utf-8.html
        if (preg_match('%^(?: [\x09\x0A\x0D\x20-\x7E] | [\xC2-\xDF][\x80-\xBF] | \xE0[\xA0-\xBF][\x80-\xBF] | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} | \xED[\x80-\x9F][\x80-\xBF] | \xF0[\x90-\xBF][\x80-\xBF]{2} | [\xF1-\xF3][\x80-\xBF]{3} | \xF4[\x80-\x8F][\x80-\xBF]{2} )*$%xs', $string)) {
            return 'UTF-8';
        }

        //If you need to distinguish between UTF-8 and ISO-8859-1 encoding, list UTF-8 first in your encoding_list.
        //if you list ISO-8859-1 first, mb_detect_encoding() will always return ISO-8859-1.
        return mb_detect_encoding($string, array('UTF-8', 'ASCII', 'ISO-8859-1', 'JIS', 'EUC-JP', 'SJIS'));
    }
}

function ordinal($number)
{
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13)) {
        return $number. 'th';
    } else {
        return $number. $ends[$number % 10];
    }
}

function numeral_text_from_ordinal($number)
{
    switch ($number) {
        case 1:
        case 'first':
        case '1st':
            return 'first';
        break;
        case 2:
        case 'second':
        case '1st':
            return 'second';
        break;
        case 3:
        case 'third':
        case '3rd':
            return 'third';
        break;
        case 4:
        case 'fourth':
        case '4th':
            return 'fourth';
        break;
        case 'fifth':
        case '5th':
            return 'fifth';
        break;
        case 'sixth':
        case '6th':
            return 'sixth';
        break;
        case 'seventh':
        case '7th':
            return 'seventh';
        break;
        case 'eighth':
        case '8th':
            return 'eighth';
        break;
        case 'ninth':
        case '9th':
            return 'ninth';
        break;
        case 'tenth':
        case '10th':
            return 'tenth';
        break;
    }
}

function get_week_number_of_month($date, $rollover='sunday')
{
    $cut = substr($date, 0, 8);
    $daylen = 86400;

    $timestamp = strtotime($date);
    $first = strtotime($cut . "00");
    $elapsed = ($timestamp - $first) / $daylen;

    $weeks = 1;

    for ($i = 1; $i <= $elapsed; $i++) {
        $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
        $daytimestamp = strtotime($dayfind);

        $day = strtolower(date("l", $daytimestamp));

        if ($day == strtolower($rollover)) {
            $weeks ++;
        }
    }

    return $weeks;
}

function depth_picker($arr, $temp_string, &$collect)
{
    if ($temp_string != "") {
        $collect []= $temp_string;
    }
        
    //print_array($arr);
    
    for ($i=0; $i<sizeof($arr);$i++) {
        $arrcopy = $arr;
        $elem = array_splice($arrcopy, $i, 1); // removes and returns the i'th element
        if (sizeof($arrcopy) > 0) {
            depth_picker($arrcopy, $temp_string ." " . $elem[0], $collect);
        } else {
            $collect []= $temp_string. " " . $elem[0];
        }
    }
    
    return $collect;
}

function get_relative_time($ts)
{
    if (!ctype_digit($ts)) {
        $ts 		= strtotime($ts);
    }
    $diff 	= time() - $ts;
        
        
    
    if ($diff == 0) {
        return 'now';
    } elseif ($diff > 0) {
        $day_diff = floor($diff / 86400);
        if ($day_diff == 0) {
            if ($diff < 60) {
                return 'just now';
            }
            if ($diff < 120) {
                return '1 minute ago';
            }
            if ($diff < 3600) {
                return floor($diff / 60) . ' minutes ago';
            }
            if ($diff < 7200) {
                return '1 hour ago';
            }
            if ($diff < 86400) {
                return floor($diff / 3600) . ' hours ago';
            }
        }
        if ($day_diff == 1) {
            return 'Yesterday';
        }
        if ($day_diff < 7) {
            return $day_diff . ' days ago';
        }
        if ($day_diff < 31) {
            return ceil($day_diff / 7) . ' weeks ago';
        }
        if ($day_diff < 60) {
            return 'last month';
        }
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        echo '<br />'.date('w');
        echo '<br />'.$day_diff;
        if ($day_diff == 0) {
            if ($diff < 120) {
                return 'in a minute';
            }
            if ($diff < 3600) {
                return 'in ' . floor($diff / 60) . ' minutes';
            }
            if ($diff < 7200) {
                return 'in an hour';
            }
            if ($diff < 86400) {
                return 'in ' . floor($diff / 3600) . ' hours';
            }
        }
        if ($day_diff == 1) {
            return 'Tomorrow';
        }
        if ($day_diff < 6) {
            return date('l', $ts);
        }
        if ($day_diff < 7 + 7 - date('w')) {
            return 'next week';
        }
        if (ceil($day_diff / 7) < 4) {
            return 'in ' . ceil($day_diff / 7) . ' weeks';
        }
        if (date('n', $ts) == date('n') + 1) {
            return 'next month';
        }
        return date('F Y', $ts);
    }
}

function convert_seconds_to_human_time($secs)
{
    if (empty($secs)) {
        return '0 minutes';
    }
    if ($secs > 3600) {
        return (floor($secs / 3600)).':'.floor($secs / 60);
    } else {
        return floor($secs / 60).' minutes';
    }
    
    return 0;
}
    

if (! function_exists('format_name')) {
    function format_name($string)
    {
        $word_splitters 				= array(' ', '-', "O'", "L'", "D'", 'St.', 'Mc', 'Mac');
        $lowercase_exceptions 		= array('the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', "l'", "d'");
        $uppercase_exceptions 	= array('III', 'IV', 'VI', 'VII', 'VIII', 'IX');

        $string 								= strtolower($string);
        foreach ($word_splitters as $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $word) {
                if (in_array(strtoupper($word), $uppercase_exceptions)) {
                    $word = strtoupper($word);
                } else {
                    if (!in_array($word, $lowercase_exceptions)) {
                    }
                    $word = ucfirst($word);
                }

                $newwords[] = $word;
            }

            if (in_array(strtolower($delimiter), $lowercase_exceptions)) {
                $delimiter = strtolower($delimiter);
            }
            

            $string = join($delimiter, $newwords);
        
            $string = str_replace('"', '”', $string);
            $string = str_replace("'", "’", $string);
        }
    
    
        return trim($string);
    }
}

if (! function_exists('format_name_alt')) {
    function format_name_alt($str)
    {
        // name parts that should be lowercase in most cases
        $ok_to_be_lower = array('av','af','da','dal','de','del','der','di','la','le','van','der','den','vel','von');
        // name parts that should be lower even if at the beginning of a name
        $always_lower   = array('van', 'der');

        // Create an array from the parts of the string passed in
        $parts = explode(" ", mb_strtolower($str));

        foreach ($parts as $part) {
            (in_array($part, $ok_to_be_lower)) ? $rules[$part] = 'nocaps' : $rules[$part] = 'caps';
        }

        // Determine the first part in the string
        reset($rules);
        $first_part = key($rules);

        // Loop through and cap-or-dont-cap
        foreach ($rules as $part => $rule) {
            if ($rule == 'caps') {
                // ucfirst() words and also takes into account apostrophes and hyphens like this:
                // O'brien -> O'Brien || mary-kaye -> Mary-Kaye
                $part = str_replace('- ', '-', ucwords(str_replace('-', '- ', $part)));
                $c13n[] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', $part)));
            } elseif ($part == $first_part && !in_array($part, $always_lower)) {
                // If the first part of the string is ok_to_be_lower, cap it anyway
                $c13n[] = ucfirst($part);
            } else {
                $c13n[] = $part;
            }
        }

        $titleized = implode(' ', $c13n);

        return trim($titleized);
    }
}

if (! function_exists('minify_css')) {
    function minify_css($css)
    {
        // some of the following functions to minimize the css-output are directly taken
        // from the awesome CSS JS Booster: https://github.com/Schepp/CSS-JS-Booster
        // all credits to Christian Schaefer: http://twitter.com/derSchepp
        // remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // backup values within single or double quotes
        preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
        for ($i=0; $i < count($hit[1]); $i++) {
            $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
        }
        // remove traling semicolon of selector's last property
        $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
        // remove any whitespace between semicolon and property-name
        $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
        // remove any whitespace surrounding property-colon
        $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
        // remove any whitespace surrounding selector-comma
        $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
        // remove any whitespace surrounding opening parenthesis
        $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
        // remove any whitespace between numbers and units
        $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
        // shorten zero-values
        $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
        // constrain multiple whitespaces
        $css = preg_replace('/\p{Zs}+/ims', ' ', $css);
        // remove newlines
        $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
        // Restore backupped values within single or double quotes
        for ($i=0; $i < count($hit[1]); $i++) {
            $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
        }
        return $css;
    }
}

function get_url_parts()
{
    print_array($_SERVER['HTTP_HOST']);
    $r = array_shift((explode('.', $_SERVER['HTTP_HOST'])));
    print_array($r);
}

function does_file_exist($url)
{
    $url		= correct_img_path($url);
    $url		= str_replace('https://', '', $url);
    $url		= str_replace('http://', '', $url);
    $url		= ltrim($url, '//');
    $url_parts	= explode('/', $url);
    $path		= null;
    $file		= null;
    if (!empty($url_parts[1])) {
        for ($i=1; $i < count($url_parts); $i++) {
            $path .= DS.$url_parts[$i];
        }
        $path = rtrim($path, '/');

        if (!empty($path)) {
            $file	= FCPATH.$path;
            if (is_file($file)) {
                return true;
            }
        }
    }
    
    return false;
}

function hash_challenge($val)
{
    return strtolower(alpha_numeric_only($val));
}


function get_subdomain()
{
    if (!empty($_SERVER['HTTP_HOST'])) {
        $url_host		= $_SERVER['HTTP_HOST'];
        $arr_host		= explode('.', $url_host);
        $subdomain 		= $arr_host[0];
        return $subdomain;
    }
    
    return '';
}

function word_combos($words)
{
    if (!is_array($words)) {
        $words = explode(' ', $words);
    }
    if (count($words) <= 1) {
        $result = $words;
    } else {
        $result = array();
        for ($i = 0; $i < count($words); ++$i) {
            $firstword = $words[$i];
            $remainingwords = array();
            for ($j = 0; $j < count($words); ++$j) {
                if ($i <> $j) {
                    $remainingwords[] = $words[$j];
                }
            }
            $combos = word_combos($remainingwords);
            for ($j = 0; $j < count($combos); ++$j) {
                $result[] = $firstword . ' ' . $combos[$j];
            }
        }
    }
    return $result;
}


//Set localhost override session
if (is_localhost()) {
    if (empty($_SESSION['localhost'])) {
        $_SESSION['localhost'] = array('id_affiliate' => 0);
    }
}

//Set localhost override session
if (!function_exists('get_unix_time')) {
    function get_unix_time($date)
    {
        if (is_numeric($date)) {
            return $date;
        }
        
        return strtotime($date);
    }
}

if (!function_exists('get_req')) {
    function get_req($key, $default=null)
    {
        $posted = $_POST;
        $got	= $_GET;
        
        if (isset($got[$key])) {
            return $got[$key];
        }
        if (isset($posted[$key])) {
            return $posted[$key];
        }
        
        if (isset($default)) {
            return $default;
        }
        
        return null;
    }
}

if (!function_exists('get_req_dec')) {
    function get_req_dec($key, $default=null)
    {
        $posted = $_POST;
        $got	= $_GET;
        
        if (isset($got[$key])) {
            return url_dec($got[$key]);
        }
        if (isset($posted[$key])) {
            return url_dec($posted[$key]);
        }
        
        if (isset($default)) {
            return $default;
        }
        
        return null;
    }
}

if (!function_exists('format_array_vals')) {
    function format_array_vals($vars, $id_key)
    {
        if (!empty($vars) && !is_array($vars)) {
            $new 			= $vars;
            unset($vars);
            $vars 			= array();
            $vars[$id_key]	= $new;
            unset($new);
        }
        
        return $vars;
    }
}

if (!function_exists('convert_time_to_seconds')) {
    function convert_time_to_seconds($time)
    {
        if (stripos($time, ':') !== false) {
            $arr_time	= explode(':', $time);
            $time	= (numeric_only($arr_time[0]) * 3600 + numeric_only($arr_time[1]));
        }
        if (stripos($time, '.') !== false) {
            $arr_time	= explode('.', $time);
            $offset_min	= '0.'.numeric_only($arr_time[1]);
            $time	= ((numeric_only($arr_time[0]) * 3600) + floor(3600 * $offset_min));
        }
        return $time;
    }
}
if (!function_exists('get_week_of_month')) {
    function get_week_of_month($date)
    {
        
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        
        //Apply above formula.
        $output = intval(strftime("%U", $date)) - intval(strftime("%U", $firstOfMonth)) + 1;
        return $output;
    }
}

if (!function_exists('get_day_iteration_of_month')) {
    function get_day_iteration_of_month($date)
    {
        $dotw		= date('N', $date);
        $dotw_count	= 0;
        
        //Get the first day of the month.
        $date_start		= strtotime(date("Y-m-01", $date));
        $date_end		= strtotime('+1 Month', $date_start);
        
        
        for ($i = $date_start; $i < $date_end; $i = strtotime('+1 day', $i)) {
            if (date('N', $i) == $dotw) {
                $dotw_count++;
            }
            if ($i >= $date) {
                return $dotw_count;
            }
        }
    }
}

if (!function_exists('is_ajax_post')) {
    function is_ajax_post()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }
}

if (!function_exists('get_limiter_session_value')) {
    function get_limiter_session_value($name)
    {
        if (!empty(get_req('clear_limiters'))) {
            return null;
        }
        if (isset($_SESSION['view_limiter'][$name])) {
            return $_SESSION['view_limiter'][$name];
        }
        
        return null;
    }
}

if (!function_exists('is_from_cli')) {
    function is_from_cli()
    {
        if (isset($_SERVER["SHELL"])) {
            return true;
        }
        if (php_sapi_name() == 'cli') {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_demo_site')) {
    function is_demo_site()
    {
        if (!empty($_SERVER['ps_site'])) {
            switch (strtolower($_SERVER['ps_site'])) {
            case 'demo':
                return true;
            break;
        }
        }
        return false;
    }
}
if (!function_exists('relative_time')) {
    function relative_time($ts)
    {
        if (!ctype_digit($ts)) {
            $ts = strtotime($ts);
        }
        $diff = time() - $ts;
        if ($diff == 0) {
            return 'now';
        } elseif ($diff > 0) {
            $day_diff = floor($diff / 86400);
            if ($day_diff == 0) {
                if ($diff < 60) {
                    return 'just now';
                }
                if ($diff < 120) {
                    return '1 minute ago';
                }
                if ($diff < 3600) {
                    return floor($diff / 60) . ' minutes ago';
                }
                if ($diff < 7200) {
                    return '1 hour ago';
                }
                if ($diff < 86400) {
                    return floor($diff / 3600) . ' hours ago';
                }
            }
            if ($day_diff == 1) {
                return 'Yesterday';
            }
            if ($day_diff < 7) {
                return $day_diff . ' days ago';
            }
            if ($day_diff < 31) {
                return ceil($day_diff / 7) . ' weeks ago';
            }
            if ($day_diff < 60) {
                return 'last month';
            }
            return date('F Y', $ts);
        } else {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if ($day_diff == 0) {
                if ($diff < 120) {
                    return 'in a minute';
                }
                if ($diff < 3600) {
                    return 'in ' . floor($diff / 60) . ' minutes';
                }
                if ($diff < 7200) {
                    return 'in an hour';
                }
                if ($diff < 86400) {
                    return 'in ' . floor($diff / 3600) . ' hours';
                }
            }
            if ($day_diff == 1) {
                return 'Tomorrow';
            }
            if ($day_diff < 4) {
                return date('l', $ts);
            }
            if ($day_diff < 7 + (7 - date('w'))) {
                return 'next week';
            }
            if (ceil($day_diff / 7) < 4) {
                return 'in ' . ceil($day_diff / 7) . ' weeks';
            }
            if (date('n', $ts) == date('n') + 1) {
                return 'next month';
            }
            return date('F Y', $ts);
        }
    }
}

if (!function_exists('get_users_web_browser')) {
    function get_users_web_browser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/OPR/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } elseif (preg_match('/Edge/i', $u_agent)) {
            $bname = 'Edge';
            $ub = "Edge";
        } elseif (preg_match('/Trident/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version= $matches['version'][0];
            } else {
                $version= $matches['version'][1];
            }
        } else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {
            $version="?";
        }

        return array(
    'userAgent' => $u_agent,
    'name'      => $bname,
    'version'   => $version,
    'platform'  => $platform,
    'pattern'    => $pattern
  );
    }
}

if (!function_exists('get_remote_file_size')) {
    function get_remote_file_size($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_exec($ch);
        $filesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        if ($filesize) {
            return $filesize;
        }
    }
}

if (!function_exists('high_res_time')) {
    function high_res_time()
    {
        $time = microtime(true);
        return $time;
        $arr_time = explode(' ', $time);
        
        return (int) $arr_time[1] + $arr_time[0];
    }
}

if (!function_exists('possesify')) {
    function possesify($name)
    {
        if (strtolower(substr($name, -1)) == 's') {
            return $name."'";
        } else {
            return $name."'s";
        }
    }
}
if (! function_exists('ellipsify')) {
    function ellipsify($text, $length=30)
    {
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length) . '...';
        }
        
        return $text;
    }
}

if (! function_exists('in_arrayi')) {
    function in_arrayi($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
}
