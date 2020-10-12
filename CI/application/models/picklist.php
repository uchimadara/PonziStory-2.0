<?php

class PickList extends CI_Model {

    private $select = array();
    private $roles_lists = array();

    public function __construct() {
        parent::__construct();
//        $r = $this->db->get('acl_roles')->result();
//        foreach ($r as $role) {
//            $this->roles_lists[] = $role->name.'_list';
//        }
    }

//==========
// Return a single value from the list.
// if it doesn't exist, returns the value passed in
//
    public function select_value($list, $sel) {

        $sel = ''.$sel;

        if ($sel == '')
            return '';

        if (isset($this->select[$list])) {
            if (isset($this->select[$list][$sel]))
                return $this->select[$list][$sel];
            else
                return $sel;
        }

        $l = $this->select_values($list);

        if (array_key_exists($sel, $l))
            return $l[$sel];
        if (array_key_exists(intval($sel), $l))
            return $l[intval($sel)];

        return $sel;
    }

//==========
// Return the count of values from the list.
//
    public function select_value_count($list) {

        if (!isset($this->select[$list])) {
            $l = $this->select_values($list);
        }
        return count($this->select[$list]);
    }

//==========
// Builds a select list and returns it.
//
    public function select_values($list, $firstEmpty = FALSE) {

        //log_message('debug', '<<bjb>> picklist::select_values:: list='.$list);

        if (is_array($list)) {
            trace_back('picklist::select_values');
            return array();
        }

        // RETURN LIST IF ALREADY LOADED

        if (isset($this->select[$list]))
            return $this->select[$list];

        if ($firstEmpty == TRUE) {
            $this->select[$list] = array('' => '');
        } else {
            $this->select[$list] = array();
        }
        $table_links = array(
            'settings_module_list'   => array(
                'table'    => 'settings',
                'key'      => 'module',
                'value'    => 'distinct(module)',
                'order_by' => 'module',
            ),
//            'email_template_list'    => array(
//                'table'       => 'email_templates',
//                'value'       => 'name',
//                'order_by'    => 'name',
//                'not_deleted' => TRUE,
//                'where'       => array('status' => 'on'),
//            ),
            'user_list'              => array(
                'table'       => 'users',
                'value'       => 'username',
                'order_by'    => 'username',
                'where' => array('id >' => '0'),
                'not_deleted' => TRUE,
            ),
            'listing_list'           => array(
                'table'    => 'monitor_listing',
                'value'    => 'name',
                'order_by' => 'name',
                'where'    => array('status' => 'Active')
            ),
            'expense_category_list'  => array(
                'table'    => 'expense_category',
                'value'    => 'name',
                'order_by' => 'name',
            ),
            'currency_list' => array(
                'table'    => 'currency',
                'key'      => 'code',
                'value'    => 'name',
                'order_by' => 'id',
            ),
            'membership_list'        => array(
                'table'       => 'memberships',
                'value'       => 'name',
                'order_by'    => 'price',
                'not_deleted' => TRUE,
            ),
            'method_list'            => array(
                'table'    => 'payment_method',
                'value'    => 'name',
                'order_by' => 'name',
                'where'    => array('enabled' => 1, 'code !=' => 'eb'),
            ),
            'payment_code_list'      => array(
                'table'    => 'payment_method',
                'value'    => 'name',
                'key'      => 'code',
                'order_by' => 'sorting',
                'where'    => array('enabled' => 1),
            ),
            'payment_code_id_list'   => array(
                'table'    => 'payment_method',
                'value'    => 'code',
                'key'      => 'id',
                'order_by' => 'sorting',
            ),
            'purchase_item_list' => array(
                'table' => 'purchase_item',
                'value' => 'title',
                'key' => 'code',
                'order_by' => 'code'
            ),
            'user_method_list'       => array(
                'table'    => 'payment_method',
                'value'    => 'name',
                'order_by' => 'name',
                'where'    => array('enabled' => 1),
            ),
            'forum_group_list'       => array(
                'table'    => 'forum_group',
                'value'    => 'name',
                'order_by' => 'name',
                'where'    => array('active' => 1),
            ),
            'new_listing_topic_list' => array(
                'table'    => 'forum_topic',
                'value'    => 'name',
                'order_by' => 'name',
                'where'    => array("category_id IN (SELECT id FROM forum_category WHERE name='New Listings')" => ''),
            ),
            'country_list'           => array(
                'table'    => 'country',
                'value'    => 'country_name',
                'order_by' => 'country_name',
            ),
            'cms_menu_parent_list'   => array(
                'table'    => 'cms_menu',
                'value'    => 'name',
                'order_by' => 'place',
                'where'    => array('parent_id' => '0')
            ),
            'cms_slider_list'        => array(
                'table'    => 'cms_menu',
                'value'    => 'name',
                'key'      => 'url',
                'order_by' => 'name',
                'where'    => array('place' => 'guest')
            ),
            'support_category_list'  => array(
                'table' => 'support_category',
                'value' => 'name',
                'where' => array('active' => 1),
            ),
        );

        $enum_lists = array(
            'email_template_categories' => array(
                'table' => 'email_templates',
                'field' => 'category'
            ),
            'social_network_list'         => array(
                'table' => 'user_social_network',
                'field' => 'name'
            ),
            'platform_list'               => array(
                'table' => 'monitor_listing',
                'field' => 'platform'
            ),
            'ref_id_type_list'            => array(
                'table' => 'monitor_listing',
                'field' => 'ref_id_type'
            ),
            'account_level_list'          => array(
                'table' => 'users',
                'field' => 'account_level'
            ),
            'listing_status_list'         => array(
                'table' => 'monitor_listing',
                'field' => 'status'
            ),
            'duration_unit_list'          => array(
                'table' => 'monitor_plan',
                'field' => 'duration_unit'
            ),
            'support_priority_list'       => array(
                'table' => 'support_ticket',
                'field' => 'priority'
            ),
            'purchase_item_category_list' => array(
                'table' => 'purchase_item',
                'field' => 'category'
            ),
            'product_type_list' => array(
                'table' => 'product',
                'field' => 'file_type'
            ),
            'cms_menu_list'               => array(
                'table' => 'cms_menu',
                'field' => 'place'
            ),
            'ad_placement_type'           => array(
                'table' => 'ad_placement',
                'field' => 'type'
            ),
            'ad_placement_size'           => array(
                'table' => 'ad_placement',
                'field' => 'size'
            ),
            'ad_placement_group'          => array(
                'table' => 'ad_placement',
                'field' => 'group'
            ),
            'pt_banner_size'              => array(
                'table' => 'pt_banner',
                'field' => 'size'
            ),
            'banner_size_list'            => array(
                'table' => 'banner',
                'field' => 'size'
            )

        );

        if (array_key_exists($list, $enum_lists)) {
            $this->select[$list] = $this->getEnumValues($enum_lists[$list]['table'], $enum_lists[$list]['field']);
        } elseif (array_key_exists($list, $table_links)) {

            $this->db->select($table_links[$list]['value'].' as value', FALSE);
            $key = (array_key_exists('key', $table_links[$list])) ? $table_links[$list]['key'] : 'id';
            $this->db->select($table_links[$list]['table'].'.'.$key.' as `key`');

            if (array_key_exists('order_by', $table_links[$list])) {
                $this->db->order_by($table_links[$list]['order_by']);
            } else {
                $this->db->order_by($table_links[$list]['value']);
            }

            if (array_key_exists('where', $table_links[$list])) {
                foreach ($table_links[$list]['where'] as $k => $v) {
                    if ($v != '')
                        $this->db->where($k, $v);
                    else
                        $this->db->where($k, NULL, FALSE);
                }
            }
            if (array_key_exists('not_deleted', $table_links[$list]))
                $this->db->where('deleted', 0);

            $s = $this->db->get($table_links[$list]['table'])->result_array();

            for ($i = 0; $i < count($s); $i++) {
                if (!empty($s[$i]['value']))
                    $this->select[$list][$s[$i]['key']] = $s[$i]['value'];
            }
            // log_message('debug', '<<bjb>>picklist::select_values - query='.$this->db->last_query());
        } else {
            $this->get_select_list($list);
        }

        return $this->select[$list];
    }

    /*     * ********
     * @param $list
     */

    function getEnumValues($table, $field) {
        $type = $this->db->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->row(0)->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach (explode(',', $matches[1]) as $value) {
            $value        = trim($value, "'");
            $enum[$value] = $value;
        }
        return $enum;
    }

//==========
// This is the "default" static configuration list builder.
// Looks for list in $config_params first and if it doesn't exist, looks in the static values coded below.
//
    function get_select_list($list) {

        switch ($list) {
            case 'mass_email_setting_list':
                $this->select[$list] = array(
//                    EMAIL_NEWS         => "System News",
//                    EMAIL_NEW_FEATURES => "New Feature Email",
                    EMAIL_ALL          => "Email All Users"
                );
                break;

            case 'member_group_list':
                $this->select[$list] = array(
                    'lifetime'     => "Lifetime Members",
                    'shareholders' => "Shareholders",
                );
                break;

            case 'email_template_list':
                $this->select[$list] = array(
                    'default' => 'default',
                    ''        => 'none',
                );
                break;
            case 'config_category_list':
                $this->select[$list] = array(
                    'system' => 'system',
                    'mail'   => 'mail',
                    'hidden' => 'hidden'
                );
                break;

            case 'form_attribute_list':
                $this->select[$list] = array(
                    "label"       => "label",
                    "type"        => "type",
                    "size"        => "size",
                    "maxlength"   => "max length",
                    //"select_list" => "select list",
                    "required"    => "required",
                    'label_width' => "label width",
                    'field_width' => "field width"
                );
                break;
            case 'form_attribute_types':
                $this->select[$list] = array(
                    "label"       => "text",
                    "type"        => "select_field_type_list",
                    "size"        => "int",
                    "maxlength"   => "int",
                    //"select_list" => "select list",
                    "required"    => "select_yes_no_list",
                    'label_width' => "int",
                    'field_width' => "int"
                );
                break;
            case 'field_type_list':
                $this->select[$list] = array(
                    "text"      => "text",
                    "int"       => "integer",
                    "select"    => "dropdown",
                    'float'     => 'floating point',
                    "phone"     => "phone",
                    "currency"  => "currency",
                    "date"      => "date",
                    "date_time" => "date/time",
                );
                break;
            case 'account_type_list':
                $this->select[$list] = array(
                    'trial'   => 'trial',
                    'pro'     => 'pro',
                    'premium' => 'premium'
                );
                break;

            case 'account_status_list':
                $this->select[$list] = array(
                    'free'    => 'Free',
                    'paid'    => 'Paid',
                    'premium' => 'Premium'
                );
                break;

            case 'user_status_list':
                $this->select[$list] = array(
                    'active'   => 'active',
                    'inactive' => 'Inactive',
                );
                break;

            case 'status_list':
                $this->select[$list] = array(
                    '1' => 'active',
                    '0' => 'Inactive',
                );
                break;

            case 'language_list':
                $this->select[$list] = array(
                    'spanish' => 'Spanish',
                    'chinese' => 'Chinese',
                    'mong'    => 'Mong',
                );
                break;

            case 'transaction_type':
                $this->select[$list] = array(
                    'RCB'        => 'RCB',
                    'membership' => 'Membership'
                );
                break;

            case 'ticket_categories':
                $this->select[$list] = array(
                    'bug'         => 'Something is not working right',
                    'help'        => 'I need help using the system',
                    'enhancement' => 'Request a new feature'
                );
                break;

            case 'payment_options':
                $this->select[$list] = array(
                    'Personal Check'  => 'Personal Check',
                    'Certified Funds' => 'Certified Funds',
                    'Credit Card'     => 'Credit Card',
                    'ACH Draft'       => 'ACH Draft',
                    'Cash'            => 'Cash',
                );
                break;

            case 'yes_no_list':
                $this->select[$list] = array(
                    'yes' => "Yes",
                    'no'  => 'No',
                );
                break;

            case 'yes_no_int':
                $this->select[$list] = array(
                    '0' => "No",
                    '1' => 'Yes',
                );
                break;

            case 'on_off':
                $this->select[$list] = array(
                    'on'  => "On",
                    'off' => 'Off',
                );
                break;

            case 'cc_exp_year_list':
                $y = date('Y');
                for ($i = 0; $i < 10; $i++) {
                    $y                                       = intval($y) + $i;
                    $this->select['cc_exp_year_list'][''.$y] = ''.$y;
                }
                break;

            case 'cc_type_list':
                $this->select[$list] = array(
                    "Visa"        => "Visa",
                    "Master Card" => "Master Card",
                    //"American Express" => "American Express",
                    "Discover"    => "Discover",
                );
                break;

            case 'gender_list':
                $this->select[$list] = array(
                    'male'   => 'Male',
                    'female' => 'Female',
                );
                break;

            case 'date_list':
                for ($i = 1; $i <= 31; $i++) {
                    $this->select['date_list'][''.$i] = ''.$i;
                }
                break;

            case 'year_list':
                $y   = intval(date('Y'));
                $end = $y - 90;
                for ($i = $y; $i >= $end; $i--) {
                    $this->select['year_list'][''.$i] = $i;
                }
                break;

            case 'action_list':
                $this->select[$list] = array(
                    'view' => 'View',
                    'edit' => 'Edit',
                );
                break;

            case 'action_category_list':
                $this->select[$list] = array(
                    'menu' => 'Menu',
                    'tab'  => 'Tab',
                    'form' => 'Form',
                    'doc'  => 'Document'
                );
                break;

            case 'state_list':
                $this->select[$list] = array(
                    'AK' => 'AK',
                    'AL' => 'AL',
                    'AR' => 'AR',
                    'AZ' => 'AZ',
                    'CA' => 'CA',
                    'CO' => 'CO',
                    'CT' => 'CT',
                    'DE' => 'DE',
                    'FL' => 'FL',
                    'GA' => 'GA',
                    'HI' => 'HI',
                    'IA' => 'IA',
                    'ID' => 'ID',
                    'IL' => 'IL',
                    'IN' => 'IN',
                    'KS' => 'KS',
                    'KY' => 'KY',
                    'LA' => 'LA',
                    'MA' => 'MA',
                    'ME' => 'ME',
                    'MD' => 'MD',
                    'MI' => 'MI',
                    'MN' => 'MN',
                    'MS' => 'MS',
                    'MO' => 'MO',
                    'MT' => 'MT',
                    'NE' => 'NE',
                    'NV' => 'NV',
                    'NH' => 'NH',
                    'NJ' => 'NJ',
                    'NM' => 'NM',
                    'NY' => 'NY',
                    'NC' => 'NC',
                    'ND' => 'ND',
                    'OH' => 'OH',
                    'OK' => 'OK',
                    'OR' => 'OR',
                    'PA' => 'PA',
                    'RI' => 'RI',
                    'SC' => 'SC',
                    'SD' => 'SD',
                    'TN' => 'TN',
                    'TX' => 'TX',
                    'UT' => 'UT',
                    'VT' => 'VT',
                    'VA' => 'VA',
                    'WA' => 'WA',
                    'WV' => 'WV',
                    'DC' => 'DC',
                    'WI' => 'WI',
                    'WY' => 'WY',
                );
                break;

            case 'hours':
                $this->select[$list] = array(
                    '01' => '1',
                    '02' => '2',
                    '03' => '3',
                    '04' => '4',
                    '05' => '5',
                    '06' => '6',
                    '07' => '7',
                    '08' => '8',
                    '09' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                );
                break;

            case 'minutes':
                $this->select[$list] = array(
                    '00' => '00',
                    '05' => '05',
                    '10' => '10',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                    '55' => '55',
                );
                break;

            case 'meridian':
                $this->select[$list] = array(
                    '0'  => 'AM',
                    '12' => 'PM'
                );
                break;

            case 'hours_list':
                $this->select[$list] = array(
                    '06:00:00' => '6am',
                    '07:00:00' => '7am',
                    '08:00:00' => '8am',
                    '09:00:00' => '9am',
                    '10:00:00' => '10am',
                    '11:00:00' => '11am',
                    '12:00:00' => '12pm',
                    '13:00:00' => '1pm',
                    '14:00:00' => '2pm',
                    '15:00:00' => '3pm',
                    '16:00:00' => '4pm',
                    '17:00:00' => '5pm',
                    '18:00:00' => '6pm',
                );
                break;

            case 'month_list':
                $this->select[$list] = array(
                    "01" => "January",
                    "02" => "February",
                    "03" => "March",
                    "04" => "April",
                    "05" => "May",
                    "06" => "June",
                    "07" => "July",
                    "08" => "August",
                    "09" => "September",
                    "10" => "October",
                    "11" => "November",
                    "12" => "December",
                );
                break;

            case 'month_no_list':
                $this->select[$list] = array(
                    "01" => "01",
                    "02" => "02",
                    "03" => "03",
                    "04" => "04",
                    "05" => "05",
                    "06" => "06",
                    "07" => "07",
                    "08" => "08",
                    "09" => "09",
                    "10" => "10",
                    "11" => "11",
                    "12" => "12",
                );
                break;

            case 'css_icon_list':
                $this->select[$list] = array(
                    ''                   => '',
                    'sa-side-home'       => 'sa-side-home',
                    'sa-side-updates'    => 'sa-side-updates',
                    'sa-side-typography' => 'sa-side-typography',
                    'sa-side-cashier'    => 'sa-side-cashier',
                    'sa-side-shares'     => 'sa-side-shares',
                    'sa-side-user'       => 'sa-side-user',
                    'sa-side-widget'     => 'sa-side-widget',
                    'sa-side-table'      => 'sa-side-table',
                    'sa-side-form'       => 'sa-side-form',
                    'sa-side-ui'         => 'sa-side-ui',
                    'sa-side-folder'     => 'sa-side-folder',
                    'sa-side-calendar'   => 'sa-side-calendar',
                    'sa-side-page'       => 'sa-side-page',
                    'sa-side-chart'      => 'sa-side-chart',
                    'sa-side-photos'     => 'sa-side-photos',
                    'sa-side-spam'       => 'sa-side-spam',
                    'sa-side-list-view'  => 'sa-side-list-view',
                    'sa-side-news'       => 'sa-side-news'
                );
                break;
        }
    }
}

?>
