<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $now;
    protected $cacheOverride = FALSE;
    protected $cacheTimeout = NULL;
    protected $cacheKey = NULL;

    protected $_params;
    protected $_request_params;

    protected $userPage;

    function __construct()
    {
        parent::__construct();

        $this->now = now();
    }

    public function setUserPage($id) {
        $this->userPage = $id;
    }

    public function filterByUserId($userId)
    {
        $this->db->where('user_id', $userId);

        return $this;
    }

    public function fromDate($date)
    {
        $this->db->where('date >= ', $date);

        return $this;
    }

    function getEnumValues($table, $field)
    {
        $type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach( explode(',', $matches[1]) as $value )
        {
            $value = trim( $value, "'" );
            $enum[$value] = $value;
        }
        return $enum;
    }

    /*******************
     * @name update
     * @desc will pick only those fields that match for insert on a given table (could be changed self::table and/or $this->id)
     * useful for posted data that may contain data for multiple tables.
     * @param $table
     * @param $id
     * @param $d
     */
    public function update($table, $id, $d) {
        $data    = array();
        $columns = $this->db->list_fields($table);

        foreach ($columns as $v) {
            if (isset($d[$v])) {
                //log_message ('debug', 'db_form::update data['.$v.'] = '.$this->input->post($v, TRUE));
                $data[$v] = $d[$v];
            }
        }
        if (in_array('updated', $columns) ) $data['updated'] = $this->now;

        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    public function delete_row($table, $id) {
        $this->db->where('id', $id)->delete($table);
    }

    /*************************
     * @param $cache
     * @param $startDate
     * @return bool
     */
    public function getCache($cache, $startDate = NULL, $duration= NULL) {

        if ($this->cacheOverride) return FALSE;

        if (is_null($startDate)) $startDate = $this->now;

        $this->cacheKey = cacheKey("{$cache}");
        $this->cacheTimeout = ($duration) ? $duration : ((date('Y-m-d', $startDate) == date('Y-m-d', $this->now)) ? CACHE_THIRTY_SECONDS : CACHE_ONE_DAY*30);

        $result         = NULL;
        $result         = $this->cache->get($this->cacheKey);

        return ($result) ? $result : FALSE;
    }

    public function saveCache(&$result) {
        if (!$this->cacheOverride)
            $this->cache->save($this->cacheKey, $result, $this->cacheTimeout);
    }

    /*********
     * Generic list paging and sorting methods
     *
     */
    /*********
     * SET SEARCH PARAMS
     * build up the where clause based on table, user roles, and filter params.
     *
     */

    public function set_search_params($table, &$params) {

        $this->_params = $params;
        $this->_request_params = array();

        $thequery = '';

        if (isset($params['username']) && $params['username'] != '') {
            $thequery .= " $table.username LIKE ".$this->db->escape(urldecode($params['username']).'%')." AND ";
            $this->_request_params[] = 'username='.$params['first_name'];
        }
        if (isset($params['status']) && !empty($params['status'])) {
            if (is_array($params['status'])) {
                foreach ($params['status'] as $d) {
                    $this->_request_params[] = 'status[]='.$d;
                }
                $thequery .= " $table.status IN ('".implode("','", $params['status'])."') AND ";
            } else {
                $this->_request_params[] = 'status='.$params['status'];
                $thequery .= "$table.status = '".$params['status']."' AND ";
            }
        }

        if (isset($params['category']) && !empty($params['category'])) {
            if (is_array($params['category'])) {
                foreach ($params['category'] as $d) {
                    $this->_request_params[] = 'category[]='.$d;
                }
                $thequery .= " $table.category IN ('".implode("','", $params['category'])."') AND ";
            } else {
                $this->_request_params[] = 'category='.$params['category'];
                $thequery .= "$table.category = '".$params['category']."' AND ";
            }
        }

        if (isset($params['type']) && $params['type'] != '') {
            $this->_request_params[] = 'type='.$params['type'];
            $thequery .= " $table.type = '".$params['type']."' AND ";
        }

        if (isset($params['date_start']) && $params['date_start'] != '') {
            $this->_request_params[] = 'date_start='.$params['date_start'];
            $thequery .= " $table.date_entered >= '".$params['date_start']." 00:00:00' AND ";
        }
        if (isset($params['date_end']) && $params['date_end'] != '') {
            $this->_request_params[] = 'date_end='.$params['date_end'];
            $thequery .= " $table.date_entered <= '".$params['date_end']." 23:59:59' AND ";
        }

        if (isset($params['dategroup']) && $params['dategroup'] != '') {
            $now                     = date('Y-m-d');
            $this->_request_params[] = 'dategroup='.$params['dategroup'];
            switch ($params['dategroup']) {
                case "day":
                    $thequery .= " $table.date_entered LIKE '$now%' AND ";
                    break;
                case "week":
                    $thequery .= " $table.date_entered>='".date('Y')."-".date('m')."-".(date('d') - (date('w') - 1))." 00:00:00' AND $table.date_entered<='".date('Y')."-".date('m')."-".(date('d') + (5 - date('w')))." 23:59:59' AND $table.status='Enrollment Activated' AND ";
                    break;
                case "month":
                    $thequery .= " $table.date_entered>='".date('Y')."-".date('m')."-01 00:00:00' AND $table.date_entered<='".date('Y')."-".date('m')."-".date('t')." 23:59:59' AND $table.status='Enrollment Activated' AND ";
                    break;
                default:
                    break;
            }
        }

        if (!empty($this->_where)) {
            if (is_array($this->_where)) {

                $keys = array_keys($this->_where);

                if ($keys[0] != 'keywords') { // if not keywords then each field is in the array

                    $where        = $this->_where;
                    $this->_where = '';
                    foreach ($where as $k => $v) {
                        //$this->db->where($this->_table.'.'.$k, $v);
                        //$this->db->or_where($this->_table.'.'.$k.' LIKE "%'.$v.'%"', NULL, false);

                        //$this->db->where($k.' LIKE "%'.$v.'%"', NULL, false);
                        //$this->_where .= " ({$this->_table}.$k LIKE '%$v%' OR {$this->_table}.$k = '$v') AND ";

                        if ($this->_where != '') $this->_where .= ' AND ';
                        $this->_where .= " ($k LIKE '%".urldecode($v)."%') ";
                    }
                } else { // parse keywords into key fields

                    $keywords   = explode(' ', $this->_where['keywords']);
                    $key_fields = $this->config->item('key_fields');

                    $this->_where = '';

                    foreach ($key_fields[$this->_table] as $k) {
                        foreach ($keywords as $v) {
                            if ($this->_where != '') {
                                $this->_where .= ' OR ';
                                //$this->db->or_where($this->_table.'.'.$k, $v);
                            } //else {
                            //	$this->db->where($this->_table.'.'.$k, $v);
                            //}
                            //$this->db->or_where($this->_table.'.'.$k.' LIKE "%'.$v.'%"', NULL, false);

                            $this->_where .= " ({$this->_table}.$k LIKE '%$v%' OR {$this->_table}.$k = '$v') ";
                        }
                    }
                    //$this->_where .= ' AND ';
                }
            } else {
                //if ($this->_where != '') $this->_where .= ' AND ';
            }
        } else {
            $this->_where = ''; // make assignment here in case it's an empty array
        }

        if (substr($thequery, -4) == 'AND ') $thequery = substr($thequery, 0, strlen($thequery) - 4);

        $this->_where .= $thequery;

        return implode("&", $this->_request_params);
    }
}