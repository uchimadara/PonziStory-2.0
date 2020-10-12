<?php

class db_Form extends MY_Model {
    var $_default_lim = 10;

    private $id;
    private $title;

    private $child_tables = array(
        //'users' => array ( 'users_photos'),
        'forms' => array('fields'),
    );

    private $join_tables = array(
        'monitor_listing' => array(
            array(
            'join_table' => 'listing_payment_method',
            'other_table' => 'payment_method'
            ),
        )
    );

    // following image types array corresponds to how controller/view handle image_types
    // image types (indexes) listed have an entry (field) in the main db table (_table)
    // other indexes (types) will be stored in the _photos directory
    // type '1' is the slide_show image which is always stored in the _photos table

    private $image_types = array();

    //singular to plural map
    private $_items = array();
    private $_item = '';
    private $_table;
    private $_columns;

    public function __construct() {
        parent::__construct();

        $this->_items = $this->config->item('items');
    }

    public function set_db($db_name) {
        $connect             = $this->config->item('db_connect');
        $connect['database'] = $db_name;
        if (!isset($connect['username'])) $connect['username'] = $db_name;
        $this->db = $this->load->database($connect, TRUE, TRUE);
    }

    public function set_table($table) {

        if (isset($this->_items[$table]))
            $this->_item = strtolower(str_replace(' ', '_', $this->_items[$table]));
        else
            $this->_item = $table;

        $this->_table = $table;
        $this->_columns = $this->db->list_fields($this->_table);
    }

    public function get_columns() {
        return $this->_columns;
    }

    function getFields($table) {
        return $this->db->list_fields($table);
    }

    function getFieldDataType($table, $field) {

        $fields = $this->db->field_data($table);

        foreach ($fields as $f) {
            if ($f == $field) return $field->type;
        }
        return '';
    }

    public function update_children($id, $d) {

        $parent_id_field = $this->_item.'_id';

        foreach ($this->child_tables[$this->_table] as $t) {

            log_message('debug', '<<bjb>> db_form::update_children('.$id.') - operating on table='.$t);

            $existing_ids = array();
            $keep_ids     = array();

            $this->db->select('id');
            $this->db->where($parent_id_field, $id);
            $r = $this->db->get($t)->result();
            if (!empty($r)) {
                foreach ($r as $c) {
                    $existing_ids[] = $c->id;
                }
            }

            if (!empty($d) && array_key_exists($t, $d)) {
                if (!empty($d[$t])) {
                    $keys = array_keys($d[$t][0]);
                    foreach ($d[$t] as $v) {

                        $data = array();
                        foreach ($keys as $k) {
                            $data[$k] = $v[$k];
                        }
                        //echo var_dump($d[$t]);

                        log_message('debug', '<<bjb>> update_children::data[id]='.$data['id']);

                        $c = $this->db->list_fields($t);
                        if (in_array('create_date', $c)) $data['create_date'] = $this->now;
                        if (in_array('created_on', $c)) $data['created_on'] = $this->now;
                        if (in_array('created', $c)) $data['created'] = $this->now;

                        $data[$parent_id_field] = $id;

                        if (array_key_exists('id', $data) && $data['id'] != -1) {
                            $keep_ids[] = $data['id'];
                        }

                        if (in_array($data['id'], $existing_ids)) {
                            $this->db->where('id', $data['id']);
                            $this->db->update($t, $data);
                        } else {
                            $this->db->insert($t, $data);
                        }
                    }
                }
            }
            // delete children that were not kept
            $delete_ids = array();
            foreach ($existing_ids as $eid) {
                if (!in_array($eid, $keep_ids)) $delete_ids[] = $eid;
            }
            if (!empty($delete_ids)) {
                $this->db->query("DELETE FROM $t WHERE id IN ('".implode("','", $delete_ids)."')");
            }
        }
    }


    public function update_join_tables($parent_id, $l) {
        //echo 'item='.$this->_item.'<br />';
        //echo var_dump($l);

        if (!array_key_exists($this->_table, $this->join_tables)) return;

        foreach ($this->join_tables[$this->_table] as $jt) {

            $parent_field = $this->_item.'_'.'id';

            $this->db->where($parent_field, $parent_id);
            $this->db->delete($jt['join_table']);

            if (!empty($l) && array_key_exists($jt['other_table'], $l)) {

                foreach ($l[$jt['other_table']] as $id) {
                    if ($id != '0') {
                        $data = array(
                            $parent_field         => $parent_id,
                            $jt['other_table'].'_id' => $id,
                        );

                        //log_message('debug', '<<BJB>> db_form::update_join_tables - parent_field='.$parent_field.' parent_id='.$parent_id.' '.$jt['other_table'].'_id ='.$id);

                        $this->db->insert($jt['join_table'], $data);
                    }
                }
            }
        }
    }

    public function exists($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        return $this->db->get($this->_table)->count_all_results() > 0;
    }

    public function retrieve($id, $getDeleted = FALSE) {

        $this->db->where('id', $id);
        if ($getDeleted === FALSE) {
            if (in_array('deleted', $this->_columns)) $this->db->where('deleted', 0);
        }
        $d = $this->db->get($this->_table)->row_array();

        //echo $this->db->last_query();

        if (!empty($d)) {
            return $d;
        }
        return FALSE;
    }

    public function retrieve_by_user_id($uid, $getDeleted = FALSE) {
        $d = array();
        $this->db->where('users_id', $uid);
        if ($getDeleted === FALSE) {
            if (in_array('deleted', $this->_columns)) $this->db->where('deleted', 0);
        }

        $d = $this->db->get($this->_table)->row_array();

        //echo $this->db->last_query();

        if (!empty($d)) {
            $this->_append_data($d);
            return $d;
        }
        return FALSE;
    }

    public function _append_data(&$d) {
        // get data of children
        $parent_id_field = $this->_item.'_id';
        if (array_key_exists($this->_table, $this->child_tables)) {
            foreach ($this->child_tables[$this->_table] as $t) {
                $this->db->where($parent_id_field, $d['id']);
                $d[$t.'_list'] = $this->db->get($t)->result_array();
            }
        }

        // get data list from join tables
        if (array_key_exists($this->_table, $this->join_tables)) {
            foreach ($this->join_tables[$this->_table] as $jt) {

                $this->db->select($jt['other_table'].'.*');

                $this->db->join($jt['join_table'], $jt['join_table'].'.'.$jt['other_table'].'_id = '.$jt['other_table'].'.id');
                $this->db->where($jt['join_table'].'.'.$parent_id_field, $d['id']);
                $d[$jt['other_table'].'_list'] = $this->db->get($jt['other_table'])->result_array();
                //echo var_dump($d[$jt['other_table'].'_list']);
            }
        }
        return;
    }

    /**
     * Function used to get sub-form (list_item) data from post
     */

    // the following function will create an array starting from 0 that is ready for insert into database
    // independent of the indicies of the post data (which can be missing due to delete actions)
    // all the post arrays in each function should all be exactly the same size.

    function get_list_from_post($list, $data) {
        $r = array();
        //echo print_r($this->_children[$this->data['table']]);
        //echo 'get_list_from_post('.$list.')<br />';
        //echo var_dump($this->data['form_data']);

        foreach ($this->_form_def[$list]['subform'] as $f) {
            if (array_key_exists('field_name', $f) and !empty($_POST[$this->_form_def[$list]['list_prefix'].$f['field_name']])) {
                //echo '<br />processing... '.$this->data['form_data'][$list]['list_prefix'].$f['field_name'].'<br />';
                //echo var_dump($_POST[$this->data['form_data'][$list]['list_prefix'].$f['field_name']]);
                $i = 0;
                foreach ($data[$this->_form_def[$list]['list_prefix'].$f['field_name']] as $v) {
                    $r[$i++][$f['field_name']] = $v;
                }
                $i = 0;
                foreach ($data[$this->_form_def[$list]['list_prefix'].'id'] as $v) {
                    $r[$i++]['id'] = $v;
                }
            }
        }
        return $r;
    }

    // if data is empty loads from post ~ facilitates having more than one table's data on a form
    public function update($id, $d) {

        $data = array();
        foreach ($this->_columns as $v) {
            if (array_key_exists($v, $d)) {
                //log_message ('debug', 'db_form::update data['.$v.'] = '.$d[$v]);
                $data[$v] = html_entity_decode($d[$v]);
            }
        }

        $this->id = $id;
        //echo var_dump($data);
        if (!empty($data)) {
            if (in_array('date_modified', $this->_columns)) $data['date_modified'] = $this->now;
            if (in_array('updated', $this->_columns)) $data['updated'] = $this->now;
            if (in_array('updated_by', $this->_columns)) $data['updated_by'] = $this->session->userdata('user_id');
            $this->db->where('id', $this->id);
            $this->db->update($this->_table, $data);
            //log_message ('debug', 'AdminForm::update sql = '.$this->db->last_query());
        }

        $child_data = array();
        if (array_key_exists($this->_table, $this->child_tables)) {
            foreach ($this->child_tables[$this->_table] as $child) {
                //echo 'getting list data for '.$child.'<br />';
                $child_data[$child] = $this->get_list_from_post($child, $d);
            }
            //echo var_dump($this->_child_data);
            $this->update_children($id, $child_data);
        }

        $join_data = array();
        if (array_key_exists($this->_table, $this->join_tables)) {
            foreach ($this->join_tables[$this->_table] as $jt) {
                $list = $jt['other_table'].'_list';
                if (!empty($d[$list])) {
                    $join_data[$jt['other_table']] = $d[$list];
                } else {
                    $join_data[$jt['other_table']] = array();
                }
            }

            $this->update_join_tables($id, $join_data);
        }

    }

    public function create($d) {
        $data = array();
        foreach ($this->_columns as $v) {
        if (array_key_exists($v, $d)) {
                $data[$v] = html_entity_decode($d[$v]);
            }
        }

        if (in_array('date_entered', $this->_columns)) $data['date_entered'] = $this->now;
        if (in_array('created_on', $this->_columns)) $data['created_on'] = $this->now;
        if (in_array('created', $this->_columns)) $data['created'] = $this->now;
        if (in_array('date_modified', $this->_columns)) $data['date_modified'] = $this->now;
        if (in_array('created_by', $this->_columns)) $data['created_by'] = $this->session->userdata('user_id');
        if (in_array('updated_by', $this->_columns)) $data['updated_by'] = $this->session->userdata('user_id');

        $this->db->insert($this->_table, $data);

        log_message('debug', "<<bjb>> db_form::create() - SQL = ".$this->db->last_query());

        $this->id = $this->db->insert_id();

        $join_data = array();
        if (array_key_exists($this->_table, $this->join_tables)) {
            foreach ($this->join_tables[$this->_table] as $jt) {
                $list = $jt['other_table'].'_list';
                if (!empty($d[$list])) {
                    $join_data[$jt['other_table']] = $d[$list];
                } else {
                    $join_data[$jt['other_table']] = array();
                }
            }

            $this->update_join_tables($this->id, $join_data);
        }

        return $this->id;
    }

    public function get_data($id, $d) {
        foreach ($d as $field) {
            if (in_array($field, $this->_columns)) $this->db->select($field, FALSE);
        }

        $this->db->where('id', $id);

        $row = $this->db->get($this->_table)->row_array();

        if (empty($row)) return FALSE;

        return $row;
    }

    public function delete($id_array) {

        if (in_array('deleted', $this->_columns)) {

            $this->db->where('id IN '."('".implode("','", $id_array)."')", NULL, FALSE);
            $this->db->update($this->_table, array('deleted' => 1));

        } else {
            $this->purge($id_array);
        }

    }

    public function purge($id_array) {

        $ids = "('".implode("','", $id_array)."')";

        $this->db->where('id IN '.$ids);
        $this->db->delete($this->_table);

        $parent_field = $this->_item.'_'.'id';

        if (array_key_exists($this->_table, $this->child_tables)) {
            foreach ($this->join_tables[$this->_table] as $t) {
                $this->db->where($parent_field.' IN '.$ids, NULL, FALSE);
                $this->db->delete($t['join_table']);
            }
        }

        if (array_key_exists($this->_table, $this->join_tables)) {
            $parent_field = $this->_item.'_'.'id';
            foreach ($this->join_tables[$this->_table] as $t) {
                $this->db->where($parent_field.' IN '.$ids, NULL, FALSE);
                $this->db->delete($t['join_table']);
            }
        }
    }

    /**
     * getForm
     *
     * @abstract returns an array used by form_field() to create form elements
     *
     * @access    public
     * @param    table    table_name for which form to do
     * @param
     * @return    form array
     */

    public function get_form($t = "") {
        if ($t == '') $t = $this->_table;
        $this->db->where('table_name', $t);
        $this->db->order_by('order');
        $fields = $this->db->get('forms')->result_array();

        $form = array();
        if (!empty($fields)) {
            foreach ($fields as $f) {
                $form[$f['field_name']]['field_name'] = $f['field_name'];
                $form[$f['field_name']][$f['key']]    = $f['value'];
            }
        }
        return $form;
    }
}