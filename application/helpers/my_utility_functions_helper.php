<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getAppSettings')) {

    function getAppSettings($p = array()) {
        $ci = & get_instance();
        $ci->load->model("settings_model");
        return $ci->settings_model->getCachedSettings($p);
    }

}

if (!function_exists('getSettingsItem')) {

    function getSettingsItem($item, $settings, $defaultValue = NULL) {
        foreach ($settings as $k => $v) {
            if ($k == "chatbot") {
                return isset($v[$item]) ? $v[$item] : $defaultValue;
            }
        }

        /* $ci =& get_instance();
          $ci->load->model("settings_model");
          return $ci->settings_model->getItem($item, $settings, $defaultValue); */
    }

}

if (!function_exists('getCompanyId')) {

    function getCompanyId($paramName = "company_id") {
        $ci = & get_instance();
        return $ci->my_session->getCompanyId($paramName);
    }

}

if (!function_exists('secToTime')) {

    function secToTime($time) {
        return floor($time / 3600) . ':' . gmdate("i:s", $time);
    }

}

if (!function_exists('get_timestamp_from_format')) {

    function get_timestamp_from_format($format, $datestr) {
        $dt = DateTime::createFromFormat($format, $datestr);
        if ($dt) {
            return $dt->getTimestamp();
        }
        return false;
    }

}

if (!function_exists('asset_url')) {

    function asset_url() {
        return base_url() . "assets/";
    }

}

if (!function_exists('theme_path')) {

    function theme_path() {
        return asset_url() . "themes/";
    }

}

if (!function_exists('encrypt_user_pass')) {

    function encrypt_user_pass($password) {
        return md5($password);
    }

}

if (!function_exists('set_smtp_config')) {

    function set_smtp_config(&$mailer) {
        $mailer->IsSMTP();
        $mailer->CharSet = 'UTF-8';
        $mailer->SMTPDebug = 0;
        $mailer->SMTPKeepAlive = true;

        $mailer->SMTPAuth = SMTP_AUTH;
        $mailer->Host = SMTP_HOST;
        $mailer->Port = SMTP_PORT;
        $mailer->Username = SMTP_USERNAME;
        $mailer->Password = SMTP_PASSWORD;

        /*
          $mailer->SMTPAuth = true;
          $mailer->SMTPSecure = 'ssl';
          $mailer->Host = 'smtp.mailgun.org';
          $mailer->Port = 465;
          $mailer->Username = "postmaster@travelshoptours.com";
          $mailer->Password = '8a4a14930f9aeedd885ef8dbfddee654';
         */
    }

}

if (!function_exists('get_site_currency')) {

    function get_site_currency() {
        return SITE_CURRENCY . " ";
    }

}

/**
 * userDateFormat
 * @param itn $timestamp
 * @param date $date
 * @return date 
 */
if (!function_exists('userDateFormat')) {

    function userDateFormat() {
        return "dd/mm/yyyy";
    }

}

if (!function_exists('convert_date_format')) {

    /**
     * convert date string to a given format
     * @param type $date_string
     * @param type $format
     * @return type
     */
    function convert_date_format($date_string, $format = "Y-m-d H:i:s") {
        return date($format, strtotime($date_string));
    }

}

/**
 * t function will translate the text used for localization
 * @param string $string
 * @return string 
 * */
if (!function_exists('l')) {

    function l($string) {
        $CI = & get_instance();
        $lang = $CI->lang->my_loaded_lang;

        if (isset($lang[$string]) && $lang[$string] != "") {
            return $lang[$string];
        }

        return $string;
    }

}

/**
 * Load the language
 */
if (!function_exists('load_my_lang')) {

    function load_my_lang() {
        $CI = & get_instance();

        $langName = strtolower($CI->my_session->language["code"]);
        $langFile = APPPATH . "language/{$langName}/{$langName}_lang.txt";
        if (file_exists($langFile)) {
            $CI->lang->my_loaded_lang = parse_ini_file($langFile);
        }

        //echo "{$_loaded_class_name} is loaded";
    }

}

if (!function_exists('my_json_output')) {

    /**
     * my_json_output
     * @param object/array $data object or array
     * @param boolean $die true/false
     */
    function my_json_output($data, $die = true) {
        echo json_encode($data);
        if ($die):
            die();
        endif;
    }

}

if (!function_exists('log_last_query')) {

    /**
     * log_last_query
     * @param array/mixed $q passby reference
     * @return mixed array or the parameter itself
     */
    function log_last_query(&$q) {
        if (defined('SHOW_QUERY_IN_JSON') && SHOW_QUERY_IN_JSON) {
            $CI = & get_instance();
            $q[] = $CI->db->last_query();
        }
        return $q;
    }

}

if (!function_exists('ci_add_css')) {

    function ci_add_css($filename, $weight = 0) {
        $CI = & get_instance();
        $CI->app_css[$filename] = $filename;
    }

}

if (!function_exists('ci_add_js')) {

    function ci_add_js($filename, $weight = 0) {
        $CI = & get_instance();
        $CI->app_js[$filename] = $filename;
    }

}

if (!function_exists('render_css')) {

    function render_css() {
        $CI = & get_instance();
        if (!isset($CI->app_css)) {
            return "";
        }

        $css = "";
        foreach ($CI->app_css as $k => $v):
            $css .= '<link href="' . $k . '" rel="stylesheet" charset="utf-8">' . "\n";
        endforeach;
        return $css;
    }

}

if (!function_exists('render_js')) {

    function render_js() {
        $CI = & get_instance();
        if (!isset($CI->app_js)) {
            return "";
        }

        $time = time();
        $cacheSuffix = (ENVIRONMENT == "development") ? "?" . $time : "";

        $js = "";
        foreach ($CI->app_js as $k => $v):
            $js .= '<script src="' . $k . $cacheSuffix . '"></script>' . "\n";
        endforeach;
        return $js;
    }

}

if (!function_exists('ci_check_permission')) {

    function ci_check_permission($permissionString, $userLevel = array()) {
        $CI = & get_instance();
        return $CI->my_session->checkPermission($permissionString, $userLevel);
    }

}

if (!function_exists('app_check_usergroup')) {

    /**
     * Checks if the specified user is from the specified user group
     * @param type $userId
     * @param type $userGroup
     * @param type $company_id
     * @return boolean
     * @throws Exception
     */
    function app_check_usergroup($userId, $userGroup, $company_id = null) {
        if (!is_array($userId)) {
            $userId = array($userId);
        }

        if (!$userGroup || (is_array($userGroup) && count($userGroup) <= 0)) {
            throw new Exception("Please provide a user group in " . __FUNCTION__);
        }

        if (!is_array($userGroup)) {
            $userGroup = array($userGroup);
        }

        $CI = & get_instance();
        $CI->db->select("U.*,UG.*", false)
                ->from(TBL_USERS . " U")
                ->join(TBL_USER_GROUP . " UG", "UG.group_id = U.group_id")
                ->where_in("U.user_id", $userId);
        if ((int) $company_id > 0):
            $CI->db->where("U.company_id", $company_id);
        endif;

        $result = $CI->db->get();

        if ($result->num_rows() > 0) {
            foreach ($result->result() as $r) {
                if (in_array($r->machine_name, $userGroup)) {
                    return $r;
                }
            }
        }
        return false;
    }

}

if (!function_exists('d')) {

    function d($data, $die = true) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";

        if ($die) {
            die();
        }
    }

}

if (!function_exists('my_xmlapi_output')) {

    function my_xmlapi_output($array, $print = true) {
        if (isset($array['success']) && is_bool($array['success'])) {
            $array['success'] = ($array['success'] == true) ? 1 : 0;
        }

        $ci = & get_instance();
        $ci->load->library("my_functions");
        $ci->my_functions->prep_xml_api_data($array);

        // creating object of SimpleXMLElement
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><result></result>");

        // function call to convert array to xml
        array_to_xml($array, $xml);

        if ($print) {
            header('Content-Type: text/xml');
            print $xml->asXML();
            die();
        }

        return $xml->asXML();
    }

}

if (!function_exists('array_to_xml')) {

    /**
     * array to xml
     * @param array $array
     * @param simplexmlobject $xml
     */
    function array_to_xml($array, &$xml) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    array_to_xml($value, $subnode);
                } else {
                    $subnode = $xml->addChild("item");
                    array_to_xml($value, $subnode);
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

}

if (!function_exists('_log_entity')) {

    /**
     * Log an entity event
     * @param text $entity_name
     * @param text $message
     * @param array $data
     * @param text $log_type
     * @return arrray (success => true/false. q = > query)
     */
    function _log_entity($entity_id, $entity_name, $pkColumn, $log_type = "saved", $message = null, $data = null) {
        $ci = & get_instance();
        $ci->load->model(array("log_model"));

        $result = $ci->log_model->log_entity($ci->my_session->userId, $entity_id, $entity_name, $pkColumn, $log_type, $message, $data);
        return $result;
    }

}

if (!function_exists('getEntityInfo')) {

    function getEntityInfo($entity_id, $entity_name, $pkColumn) {
        $ci = & get_instance();

        $ci->db->select("*")
                ->from($entity_name)
                ->where("{$pkColumn}", $entity_id);

        return $ci->db->get()->row_array();
    }

}

if (!function_exists('get_enum_values')) {

    function get_enum_values($table, $field) {
        $ci = & get_instance();
        $query = "SHOW COLUMNS FROM " . $table . " LIKE '$field'";
        $row = $ci->db->query("SHOW COLUMNS FROM " . $table . " LIKE '$field'")->row()->Type;
        $regex = "/'(.*?)'/";
        preg_match_all($regex, $row, $enum_array);
        $enum_fields = $enum_array[1];
        $enums = array();
        foreach ($enum_fields as $key => $value) {
            $enums[$value] = $value;
        }
        return $enums;
    }

}

if (!function_exists('get_billing_month')) {

    function get_billing_month($month) {
        $month = ltrim($month, "0");
        $prefix = (strlen(trim($month)) >= 2) ? "" : "0";
        return $prefix . $month;
    }

}

if (!function_exists('mask_number')) {

    function mask_number($mask, $number) {
        if((int)$number <= 0):
            return false;;
        endif;
        return substr($number, 0, $mask) . str_repeat("*", strlen($number) - ($mask + 4)) . substr($number, ($mask - 10));
    }

}

if (!function_exists('json_display_html')) {

    function json_display_html($data) {
        if (!empty($data)):
            $arr = json_decode($data, true);
            foreach ($arr as $key => $value) {
                if (isset($value) && is_array($value)):
                    foreach ($value as $key => $val2) {
                        if (isset($val2) && is_array($val2)):
                            foreach ($val2 as $key => $val3):
                                echo $key . " : " . $val3 . "<br />";
                            endforeach;
                        else:
                            echo $key . " : " . $val2 . "<br />";
                        endif;
                    }

                else:
                    echo $key . " : " . $value . "<br />";
                endif;
            }
        endif;
    }

}

if (!function_exists("readable_random_string")) {

    function readable_random_string($length = 6) {
        $string = '';
        $vowels = array("a", "e", "i", "o", "u");
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );

        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++) {
            $string .= $consonants[rand(0, 19)];
            $string .= $vowels[rand(0, 4)];
        }

        return $string;
    }

}

if (!function_exists('app_send_email')) {

    /**
     * Send Email From System
     * @param array $eData :: email, fullName, subject, body
     * @return array
     */
    function app_send_email($eData) {
        $ci = & get_instance();
        $ci->load->model("mailer_model");

        $mailData["to"] = $eData['to'];
        $mailData["subject"] = $eData['subject'];
        $mailData["body"] = $eData['body'];

        if (isset($eData['cc'])):
            $mailData['cc'] = $eData['cc'];
        endif;

        if (isset($eData['bcc'])):
            $mailData['bcc'] = $eData['bcc'];
        endif;

        $res = $ci->mailer_model->sendMail($mailData);

        if (!$res["success"]) {
            $json = array(
                "success" => false,
                "msg" => $res['msg'],
            );
            return $json;
        }

        return array(
            "success" => true,
            'log' => $res,
            'mailLog' => $mailData
        );
    }

}

if (!function_exists('app_send_sms')) {

    /**
     * Send SMS From System
     * @param string $mobileNumber
     * @param string $smsContent
     * @return array
     */
    function app_send_sms($mobileNumber, $smsContent = NULL) {
        $ci = & get_instance();
        $ci->load->library("sms_service");

        $params = array(
            'mobileNo' => $mobileNumber,
            'message' => $smsContent
        );
        $smsResult = $ci->sms_service->smsService($params);
        if ($smsResult['success']) {
            return array(
                'success' => true,
                'log' => $smsResult
            );
        }

        return array(
            'success' => false,
            "body" => $smsResult['msg'],
            "log" => $smsResult
        );
    }

}
