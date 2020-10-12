<?php
/*************************
 * DB_LIST - PRODUCE A USER-SORTABLE, PAGED LISTING OF ANY QUERY DEFINED
 *
 */

class rmsList {

    var $_list_name = '';
    var $_table = '';
    var $_alias = '';
    var $_view_file = '';
    var $_table_class = '';
    var $_func = '';
    var $_columns = '';
    var $_select = '';
    var $_group = '';
    var $_order = 'id';
    var $_order_primary = '';
    var $_sort_dir = 'asc';
    var $_where = '';
    var $_group_by = '';
    var $_key_fields = array();
    var $_join = array();
    var $_listing = array();
    var $_results = array();
    var $_params = array();
    var $_request_params = '';
    var $_total = 0;
    var $_db_name = 'default';
    var $_report = '';
    var $_ci = NULL;
    var $db = NULL;
    var $_page = 1;
    var $_per_page = DEFAULT_ITEMS_PER_PAGE;
    var $_uri;
    var $_paging = NULL;
    var $_header_on = NULL;
    var $_search_form = NULL;

    public function __construct($path, $listName, $uri, $sort_dir = '', $order = '', $where = '') {

        $this->_ci = get_instance();

        $this->_list_name = $listName;
        $this->_uri       = $uri;

        $this->_ci->load->config($path.$listName);
        $listDef            = $this->_ci->config->item('list_'.$listName);
        $this->_table       = $listDef['table'];
        $this->_alias       = isset($listDef['alias']) ? $listDef['alias'] : $this->_table;
        $this->_view_file = isset($listDef['view_file']) ? $listDef['view_file'] : '';
        $this->_fields      = $listDef['fields'];
        $this->_key_fields  = isset($listDef['keyfields']) ? $listDef['keyfields'] : '';
        $this->_group_by = isset($listDef['group']) ? $listDef['group'] : '';
        $this->_table_class = $listDef['table_class'];
        $this->_order       = ($order) ? $order : $listDef['order'];
        $this->_sort_dir    = ($sort_dir) ? $sort_dir : (isset($listDef['sort_dir']) ? $listDef['sort_dir'] : 'asc');
        $this->_paging = isset($listDef['paging']) ? $listDef['paging'] : TRUE;
        $this->_header_on = isset($listDef['header_on']) ? $listDef['header_on'] : TRUE;
        $this->_search_form = isset($listDef['search_form']) ? $listDef['search_form'] : FALSE;
        $this->_order_primary = isset($listDef['order_primary']) ? $listDef['order_primary'].', ' : '';

        if (array_key_exists('join', $listDef)) $this->_join = $listDef['join'];
        if (array_key_exists('where', $listDef)) $this->_where = $listDef['where'];

        if ($where) $this->set_where($where);

        $this->db       = $this->_ci->load->database($this->_db_name, TRUE, TRUE);
        $this->_columns = $this->db->list_fields($this->_table);
    }

    public function set_db($db_name) {
        $this->_db_name = $db_name;
        $this->db       = $this->_ci->load->database($db_name, TRUE, TRUE);
    }

    public function listName() {
        return $this->_list_name;
    }

    public function total() {
        return $this->_total;
    }

    public function listUrl() {
        return site_url($this->_uri.$this->_order."/".$this->_sort_dir).'/'.$this->_page.'/'.$this->_per_page.'/';
    }

    /*********
     * FUNCTION OVERRIDDEN WHEN POST-PROCESS REQUIRED
     *
     */

    public function process(&$data) {
    }

    /*********
     * SIMPLE GET
     * assembles and runs query; calculates total and generates listing
     *
     */

    public function get() {

        $this->_loadQuery();

        $this->_results = $this->db->get($this->_table)->result_array();

        $this->_total = count($this->_results);

        //log_message('debug', '<<bjb>> db_list::_get() - SQL: '.$this->db->last_query());

        $this->_loadQuery();

        $this->db->limit($this->_total, 0);

        $this->_listing = $this->db->get($this->_table)->result_array();

        return $this->_listing;
    }

    /*********
     * PARTIAL GET
     * assembles and runs query; calculates total and generates listing
     * runs queries with limits to avoid a getListing call
     */

    public function getPartial($page = 1, $perPage = DEFAULT_ITEMS_PER_PAGE) {

        $this->_page     = $page;
        $this->_per_page = $perPage;

        $this->_loadQuery(TRUE);

        $this->db->from($this->_table);
        //echo $this->db->get_compiled_select();
        $this->_total = $this->db->count_all_results();

        $this->_loadQuery();

        $start = ($page - 1)*$perPage;

        $this->db->limit($perPage, $start);

        $this->_listing = $this->db->get($this->_table)->result_array();

      //echo $this->db->last_query().'<br/>';

        log_message('debug', 'RMSList::getPartial SQL = '.$this->db->last_query());

        return $this;
    }

    /*********
     * LOAD QUERY
     * assembles query;
     */

    function _loadQuery($counting = FALSE) {

        foreach ($this->_fields as $f) {
            if( isset($f['sql_value']) ) {
                $fname = $f['sql_value'];
            } else {
                $fname = $f['field_name'];
                if (array_key_exists('table_name', $f)) {
                    if ($f['table_name'] != '') $fname = $f['table_name'].'.'.$fname;
                } else {
                    $fname = $this->_table.'.'.$fname;
                }
                if (array_key_exists('alias', $f)) {
                    $fname = $fname.' as '.$f['alias'];
                }
            }

            $this->db->select($fname, FALSE);
        }

        foreach ($this->_join as $j) {
            if (count($j) == 3) {
                $this->db->join($j[0], $j[1], $j[2]);
            } else {
                $this->db->join($j[0], $j[1]);
            }
        }

        if (!empty($this->_where)) $this->db->where($this->_where, NULL, FALSE);
        if (in_array('deleted', $this->_columns)) {
            $this->db->where($this->_table.'.deleted', 0);
        }

        if (!$counting) {
            $this->db->order_by($this->_order_primary.$this->_order, $this->_sort_dir);
        }

        if ($this->_group_by != '') $this->db->group_by($this->_group_by);
    }

    /*********
     * MULTI GET
     * runs 2 pre-written queries and merges results; calculates total and generates listing
     *
     */

    protected function _get_multi($query1, $query2) {

        $this->_results = $this->db
                ->query($query1.' UNION '.$query2.' ORDER BY '.$this->_order.' '.$this->_sort_dir)
                ->result_array();
        $this->_total   = count($this->_results);
        $this->_get_listing();
    }

    /*********
     * GET LISTING
     * generates paginated listing array from results
     *
     */

    protected function _get_listing() {

        $start = ($this->_page - 1)*$this->_per_page;

        $max = min($start + $this->_per_page, $this->_total);

        for ($i = $start; $i < $max; $i++) {
            $this->_listing[] = $this->_results[$i];
        }
    }


    /*********
     * SET SEARCH PARAMS
     * build up the where clause based on table, user roles, and filter params.
     *
     */

    public function set_where(&$params) {

        if ($this->_where == '') $this->_where = ' 1 = 1 ';
        if ($params) {

            $where     = '';
            $key_where = '';

            foreach ($params as $k => $v) {

                if ($v != '') {
                    if ($k == 'keywords') { // if not keywords then each field to search is in the array

                        foreach ($this->_key_fields as $k) {
                            if ($key_where != '') $key_where .= ' OR ';
                            $key_where .= " ( $k = '".urldecode($params['keywords'])."' ";
                            if (strpos($k, 'identifier') === FALSE && strpos($k, 'reference') === FALSE) {
                                $key_where .= " OR $k LIKE '%".urldecode($params['keywords'])."%' ";
                            }
                            $key_where .= ")";
                        }
                    } elseif (strpos($k, 'begin') !== FALSE) { // begin date
                        $field = substr($k, 6);
                        $where .= " AND {$this->_alias}.$field >= $v ";
                    } elseif (strpos($k, 'end') !== FALSE) { // end date
                        $field = substr($k, 4);
                        $where .= " AND {$this->_alias}.$field <= $v ";
                    } else { // check each key field for the value passed.
                        if ($k == 'user_id') $k = "{$this->_alias}.$k"; // kludge to special case a common search
                        $k = str_replace('_x_', '.', $k);
                        $where .= " AND $k = '".urldecode($v)."' ";
                    }
                }
            }

            if ($where || $key_where) {
                $this->_where .= ' '.(($where) ? $where : '').(($key_where) ? 'AND ( '.$key_where.' ) ' : '');
                $this->_request_params = '?'.http_build_query($params);
            }
        }
        return $this;
    }

    public function set_search_params(&$params) {

        $this->_params = $params;

        $this->_request_params = array();
        $thequery              = '';

        $table = $this->_alias;

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


        if (isset($params['date_start']) && $params['date_start'] != '') {
            $this->_request_params[] = 'date_start='.$params['date_start'];
            $thequery .= " $table.apply_date >= '".$params['date_start']." AND ";
        }
        if (isset($params['date_end']) && $params['date_end'] != '') {
            $this->_request_params[] = 'date_end='.$params['date_end'];
            $thequery .= " $table.apply_date <= '".$params['date_end']." AND ";
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

        if ($this->_table == 'users') {
            if (isset($params['role']) && !empty($params['role'])) {
                if (is_array($params['role'])) {
                    foreach ($params['role'] as $d) {
                        $this->_request_params[] = 'role[]='.$d;
                    }
                    $thequery .= " user_id IN (SELECT distinct user_id from acl_roles_users WHERE role_id IN ('".implode("','", $params['role'])."')) AND ";
                } else {
                    $this->_request_params[] = 'role='.$params['role'];
                    $thequery .= " user_id IN (SELECT distinct user_id from acl_roles_users WHERE role_id ='".$params['role']."' AND ";
                }
            }
        }


        if (substr($thequery, -4) == 'AND ') $thequery = substr($thequery, 0, strlen($thequery) - 4);

        if ($thequery != '' && $this->_where != '') $this->_where .= ' AND ';
        $this->_where .= $thequery;

        return implode("&", $this->_request_params);
    }

    function pagination() {

        if ($this->_total > 0) {
            $url   = site_url($this->_uri.$this->_order."/".$this->_sort_dir).'/%d/%d/'.$this->_request_params;
            $pages = ceil($this->_total/$this->_per_page);

            $paging = '<div class="pagination"><div class="paging"><div class="pageLinks">';
            if ($this->_page > 1) {
                $link = sprintf($url, $this->_page - 1, $this->_per_page);
                $paging .= '<span class="page-nav ui-corner-left">';
                $paging .= '<a onclick="colSort(this, \''.$link.'\');" href="javascript:void(0);">&lt;&lt;PREV</a>&nbsp;</span>';
            }

            $paging .= 'PAGE <select class="page-select" onchange="colSortPageSelect(this, \''.$url.'\', '.$this->_per_page.')">';
            for ($i = 1; $i <= $pages; $i++) {
                $paging .= '<option '.($i == $this->_page ? 'selected="selected"' : '').'>'.$i.'</option>';
            }
            $paging .= '</select>';

            if ($this->_page < $pages) {
                $link = sprintf($url, $this->_page + 1, $this->_per_page);
                $paging .= '<span class="page-nav ui-corner-left">';
                $paging .= '&nbsp;<a onclick="colSort(this, \''.$link.'\');" href="javascript:void(0);">NEXT &gt;&gt;</a></span>';
            }

            $paging .= '</div></div><div class="perPage">'.$this->perPageLinks().'</div></div>';
        } else {
            $paging = '';
        }


        return $paging;
    }

    public function perPageLinks() {

        $url = site_url($this->_uri.$this->_order."/".$this->_sort_dir).'/'.$this->_page.'/%d/'.$this->_request_params;

        $links = '<div class="perPageLinks">Per Page: <select class="page-select" onchange="colSortPerPageSelect(this, \''.$url.'\')">';
        for ($i = ITEMS_PER_PAGE_MIN; $i <= ITEMS_PER_PAGE_MAX; $i += PER_PAGE_INTERVAL) {
            $links .= '<option '.($i == $this->_per_page ? 'selected="selected"' : '').'>'.$i.'</option>';
        }
        /*for ($i = ITEMS_PER_PAGE_MIN; $i <= ITEMS_PER_PAGE_MAX; $i += PER_PAGE_INTERVAL) {

            if ($i == $this->_per_page) {
                $links .= "<span>$i</span>";
            } else {
                $links .= '<a  onclick="colSort(this, \''.sprintf($url, $i).'\');" href="javascript:void(0);">'.$i.'</a>';
            }
        }*/
        $links .= '</select></div>';
        return $links;
    }

    function render() {

        if (empty($this->_listing)) {

            $html = '<div class="noItems">No items to display.</div>';
        } elseif ($this->_view_file) {

            $data['listing']      = & $this->_listing;
            $data['fields']       = & $this->_fields;
            $data['header_row']   = ($this->_header_on) ? $this->_header_row() : '';
            if ($this->_paging) {
                $data['pagination']   = $this->pagination();
                $data['perPageLinks'] = $this->perPageLinks();
            }

            $html = $this->_ci->load->view($this->_view_file, $data, TRUE);
        } else {

            // log_message ('debug', "<<bjb>> RMSList::render {$this->_list_name}");
            $html = '<table class="'.$this->_table_class.'">';

            if ($this->_header_on) $html .= $this->_header_row();

            $cur_field = '';

            foreach ($this->_listing as $row) {

                if (is_object($row)) $row = (array)$row;

                $html .= '<tr>';

                $fieldNum = -1;
                foreach ($this->_fields as $t) {
                    $fieldNum++;

                    if (array_key_exists('hidden', $t)) continue;

                    if( isset($t['sql_field']) ) {
                        $field = $row[$t['sql_field']];
                    } elseif (isset($t['alias'])) {

                        $field = $row[$t['alias']];
                    } else {
                        $field = $row[$t['field_name']];
                    }


                    //log_message('debug', '<<bjb>> render_list: field_name='.$t['field_name'].' value='.$field);
                    $field_count = ($this->_page - 1)*$this->_per_page;
                    if (array_key_exists('format', $t)) {
                        switch ($t['format']) {

                            case 'field_counter':
                                if ($cur_field != $row[$t['field_name']]) {
                                    $field_count = 1;
                                    $cur_field   = $row[$t['field_name']];
                                } else {
                                    $field_count++;
                                }
                                $field = $field_count;
                                break;

                            case 'currency':
                                if (floatval($field) < 0) {
                                    $field = '<span class="red">'.money($field).'</span>';
                                } elseif (floatval($field) > 0) {

                                    $field = '<span class="green">'.money($field).'</span>';
                                } else {
                                    $field = money($field);
                                }
                                break;

                            case 'pad':
                                $field = '#'.str_pad($field, $t['pad_length'], $t['pad_char'], STR_PAD_LEFT);
                                break;

                            case 'wordify':
                                $field = ucwords(str_replace('_', ' ', $field));
                                break;

                            case 'date':
                                if (empty($field) || $field == 0)
                                    $field = '';
                                else{
                                    $date_format = (isset($t['date_format'])) ? $t['date_format'] : DEFAULT_DATE_FORMAT;
                                    $field       = date($date_format, $field);
                                }
                                break;

                            case 'time':
                                if (empty($field) || $field == 0)
                                    $field = '';
                                else {
                                    $date_format = (isset($t['date_format'])) ? $t['date_format'] : DEFAULT_TIME_FORMAT;
                                    $field       = date($date_format, $field);
                                }
                                break;

                            case 'elapsed_time':
                                if (empty($field) || $field == 0)
                                    $field = '';
                                else
                                    $field = elapsedTime($field, NULL, TRUE);
                                break;

                            case 'countdown':
                                if (empty($field) || $field == 0) {
                                    $field = '';
                                } elseif ($field < now()) {
                                    $field = '<span class="red">Arrears</span>';
                                } else {
                                    $field = displayCountDown($field - now(), TRUE, TRUE);
                                }
                                break;

                            case 'datetime':
                                if (empty($field) || $field == 0) {
                                    $field = '';
                                } else {
                                    $date_format = (isset($t['date_format'])) ? $t['date_format'] : DEFAULT_DATETIME_FORMAT;
                                    $field = date($date_format, $field);
                                }
                                break;

                            case 'yesno':
                                $field = ($field == 0) ? 'no' : 'yes';
                                break;

                            case 'int':
                                $field = number_format($field);
                                break;

                            case 'percent':
                                $field = roundDown($field, 2).'%';
                                break;

                            case 'ip':
                                $field = long2ip($field);
                                break;


                            case 'shorten':
                                $length = array_key_exists('length', $t) ? $t['length'] : 75;
                                $field = ellipsis($field, $length, TRUE);
                                break;

                            case 'select':

                                $field = $this->_ci->picklist->select_value($t['select_list'], $field);
                                break;

                            case 'select_ajax':

                                if (array_key_exists('form_action', $t)) {
                                    if (array_key_exists('form_key', $t)) {
                                        $link = sprintf($t['form_action'], $row[$t['form_key']]);
                                    } else {
                                        $link = $t['form_action'];
                                    }
                                }
                                $call = '';
                                if( array_key_exists('call_after', $t) ) {
                                    $call = ', '.$t['call_after'];
                                }
                                $extra = 'onfocus="savePreviousValue(this)" onchange="javascript:selectAjax(\'' . $t['field_name'] . $row['id'] . '\''.$call.', this)"';
                                $field = form_open($link, 'name="' . $t['field_name'] . $row['id'] . '"') .
                                        form_dropdown($t['field_name'], $this->_ci->picklist->select_values($t['select_list']), $field, 'class="form-control" ' . $extra) .
                                        form_close();
                                break;

                            case 'checkbox_ajax':

                                if (array_key_exists('form_action', $t)) {
                                    if (array_key_exists('form_key', $t)) {
                                        $link = sprintf($t['form_action'], $row[$t['form_key']]);
                                    } else {
                                        $link = $t['form_action'];
                                    }
                                }
                                $extra = 'onchange="javascript:selectAjax(\'' . $t['field_name'] . $row['id'] . '\')"';
                                $field = form_open($link, 'name="' . $t['field_name'] . $row['id'] . '"') .
                                        form_hidden($t['field_name'], $row['id']).
                                        form_checkbox('status', '1', ($field != NULL ? TRUE : FALSE), 'class="rmsCheckbox" ' . $extra) .
                                        form_close();
                                break;

                            case 'image':
                                if ($field) {

                                    $w = (isset($t['img_width'])) ? ' width="'.$t['img_width'].'"' : '';
                                    $h = (isset($t['img_height'])) ? ' height="'.$t['img_height'].'"' : '';
                                    $c = (isset($t['class'])) ? ' class="'.$t['class'].'"' : '';
                                    if (isset($t['img_key'])) {
                                        $img = sprintf($t['src'], str_replace('+', '-plus', strtolower($row[$t['img_key']])));
                                    } else {
                                        $img = $t['src'];
                                    }
                                    $field = '<img src="'.$img.'" '.$w.$h.$c.' />';
                                    if (isset($t['popup'])) {
                                        $field = '<a class="popupImg" href="'.$img.'">'.$field.'</a>';
                                    }
                                }

                                break;

                            case 'button':

                                $field = '<input class="btn btn-alt" type="submit" size="'.$t['size'].'" maxlength="'.$t['maxlength'].'" ';
                                if (array_key_exists('extra', $t)) $field .= $t['extra'];
                                $field .= ' value="'.$t['field_value'].'" />';
                                break;

                            case 'icon':
                                if (!empty($field)) {
                                    if (isset($t['class'])) {
                                        $field = '<span class="'.$t['class'].' '.$field.'"></span>';
                                    }
                                    if (isset($t['icon'])) {

                                        $field = '<i class="'.$t['icon'].'"></i >';
                                    }
                                } else {
                                    $field = '';
                                }
                                break;

                            case 'checkbox':

                                $field = '<input type="checkbox"
                                                             name="'.$t['field_name'].'"
                                                             value="1"
                                                             onchange="javascript:saveRadioOrCheckbox(\''.$t['field_name'].'\',this.checked,\''.$t['table'].'\',\''.$row['id'].'\',\'updateField\');"
                                                             checked="true" />';
                                break;
                            case 'input':

                                $field = '<input class="form-control input-sm" type="text" name="'.$t['field_name'].'['.$row['id'].']" size="'.$t['size'].'" maxlength="'.$t['maxlength'].'" ';
                                if (array_key_exists('extra', $t)) $field .= $t['extra'];
                                $field .= ' value="" />';
                                break;

                            default:
                                //$field .= $field;
                                break;
                        }
                    }

                    $tip = '';
                    if (array_key_exists('hover_fields', $t)) {
                        foreach ($t['hover_fields'] as $f) {
                            if ($tip != '') $tip .= '<br />';
                            $tip .= '<b>'.ucwords(str_replace('_', ' ', $f)).':</b> ';
                            if (strpos($f, 'date')) {
                                if (!empty($row[$f]) && $row[$f] != '0000-00-00 00:00:00' && $row[$f] != '0000-00-00')
                                    $tip .= date('d-M-y g:ia', strtotime($row[$f]));
                                else
                                    $tip .= 'N/A';
                            } else {
                                $tip .= $row[$f];
                            }
                        }
                        $tip = ' onmouseover="Tip(\''.$tip.'\');" onmouseout="UnTip();"';
                    }

                    if (array_key_exists('href', $t)) {
                        if (is_array($t['href_key'])) {
                            $args = array();
                            foreach ($t['href_key'] as $k) {
                                $args[] = $row[$k];
                            }
                            $link = '<a href="'.vsprintf($t['href'], $args).'"';
                        } else {
                            $link = '<a href="'.sprintf($t['href'], $row[$t['href_key']]).'"';
                        }
                        if (array_key_exists('title', $t)) {
                            $link .= ' title="'.$t['title'].'"';
                        }
                        if (array_key_exists('class', $t)) {
                            $link .= ' class="'.$t['class'].'"';
                        }
                        if (array_key_exists('extra', $t)) {
                            $link .= $t['extra'];
                        }
                        if (array_key_exists('href_value', $t)){
                            $field = $link.' '.$tip.'>'.$t['href_value'].'</a>';
                        }
                        else {
                            $field = $link.' '.$tip.'>'.$field.'</a>';
                        }

                    } elseif ($tip != '') {
                        $field = '<a href="javascript:void(0);" '.$tip.'>'.$field.'</a>';
                    } elseif (array_key_exists('onclick', $t)) {

                        $link = '<a href="javascript:void(0);"';
                        if (array_key_exists('title', $t)) {
                            $link .= ' title="'.$t['title'].'"';
                        }
                        if (array_key_exists('class', $t)) {
                            $link .= ' class="'.$t['class'].'"';
                        }
                        if (is_array($t['onclick_key'])) {
                            $args = array();
                            foreach ($t['onclick_key'] as $k) {
                                $args[] = $row[$k];
                            }
                            $link .= ' onclick="'.vsprintf($t['onclick'], $args).'"';
                        } else {
                            $link .= ' onclick="'.sprintf($t['onclick'], $row[$t['onclick_key']]).'"';
                        }

                        $field = $link.' '.$tip.'>'.$field.'</a>';
                    }

                    if (array_key_exists('attachment', $t)) {
                        if (!empty($row['filename'])) {
                            $plus      = (array_key_exists('attachment_plus', $t)) ? $t['attachment_plus'] : '';
                            $file_link = "<a
                            href='".base_url()."download/attachment/".$row['id'].$plus."'
                            class='file-link'
                            onmouseover=\"Tip('".$row['filename']."')\" onmouseout=\"UnTip()\">&nbsp;</a>";
                            $field     = $file_link.$field;
                        }
                    }
                    if (array_key_exists('document', $t)) {
                        if (!empty($row['filename'])) {
                            $plus      = (array_key_exists('attachment_plus', $t)) ? $t['attachment_plus'] : '';
                            $file_link = "<a
                            href='".base_url()."download/document/".$row['id'].$plus."'
                            class='file-link'
                            onmouseover=\"Tip('".$row['filename']."')\" onmouseout=\"UnTip()\">&nbsp;</a>";
                            $field     = $file_link.$field;
                        }
                    }
                    if (isset($t['conditional_color'])) {
                        if (is_array($t['conditional_color'])) {
                            if (array_key_exists($field, $t['conditional_color'])) {
                                $field = '<span style="color:'.$t['conditional_color'][$field].'">'.$field.'</span>';
                            }
                        }
                    }
                    $tdClass = '';
                    if (isset($t['align'])) {
                        $tdClass = 'class="'.$t['align'].'"';
                    }
                    if (isset($t['width'])) {
                        $tdClass = ' style="width:'.$t['width'].';"';
                    }
                    $header = $this->_fields[$fieldNum]['label'];

                    $html .= '<td data-th="'.$header.'"  '.$tdClass.'>'.$field.'</td>';
                }
            }
            $html .= '</tr>';

            if (!empty($totals)) $html .= totals_row($this->_fields, $totals);

            $html .= '</table>';
        }
        if ($this->_paging) $html = $html.$this->pagination();
        return '<div class="listContainer rms-sortable">'.$html.'</div>';
    }

    /*****************
     * Function header_row()
     * @descpription renders the click-to-sort column headers.
     * @return string
     */
    protected function _header_row() {

//log_message ('debug', "<<bjb>> header_row: cur_sort=$cur_sort sort_dir=$sort_dir");

        $url = site_url($this->_uri."%s/%s/".$this->_page."/".$this->_per_page."/".$this->_request_params);


        $hr = "<tr>";
        foreach ($this->_fields as $header) {

            if (array_key_exists('hidden', $header)) continue;

            $tdClass = '';
            if (isset($header['align'])) {
                $tdClass .= 'class="'.$header['align'].'"';
            }
            if (isset($header['width'])) {
                $tdClass = ' style="width:'.$header['width'].';"';
            }
            $hr .= "<th $tdClass>";

            if (!array_key_exists('nosort', $header)) {


                if ($this->_order == $header['field_name']) {
                    //log_message ('debug', "<<bjb>> header_row: Currently Sorted - cur_sort=$cur_sort sort_dir=$sort_dir");
                    $hr .= '<img src ="/images/adminpanel/lists/sort_'.strtolower($this->_sort_dir).'.gif" alt="" />&nbsp;';
                    $rowl_sort_dir = ($this->_sort_dir == 'asc') ? 'desc' : 'asc';
                } else {
                    $rowl_sort_dir = 'asc';
                }

                if (array_key_exists('format', $header) && $header['format'] == 'image') {
                    $hr .= $header['label'];
                } else {
                  //  echo 'URL='.$url;
                    $link = sprintf($url, $header['field_name'], $rowl_sort_dir);
                    $hr .= '<a href="javascript:void(0);"';
                    $hr .= 'onclick="colSort(this, \''.$link.'\');">'.$header['label']."</a>";
                }
            } else {
                $hr .= $header['label'];
            }
            $hr .= "</th>";
        }


        $hr .= '</tr>';
        return $hr;
    }

    function totals_row($defs, $totals) {


//log_message ('debug', "<<bjb>> header_row: cur_sort=$cur_sort sort_dir=$sort_dir");

        $tr = "<tr class='reporttotalrow'><td>TOTALS</td>";
        foreach ($defs as $rowl) {
            if (array_key_exists($rowl['field_name'], $totals)) {
                $tr .= " <td class='reporttotal'>{$totals[$rowl['field_name']]}</td>";
            }
        }

        $tr .= '</tr>';
        return $tr;
    }

    public function search_form() {

        $data = array(
            'form' => $this->_search_form,
            'name' => $this->_list_name,
            'url' => $this->listUrl(),
        );
        return $this->_ci->load->view('partial/search_form', $data, TRUE);
    }
}

?>