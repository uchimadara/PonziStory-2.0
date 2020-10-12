<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function exclude_module($exclude) {
        $this->db->where_not_in('module', $exclude);
        return $this;
    }
    public function getAll($module = NULL, $table='settings')
    {
        if ($module)
            $this->db->where('module', $module);

        return $this->db->from($table)->order_by('id')->get()->result();
    }

    public function add($data, $table='settings')
    {
        $data['date'] = $this->now;

        return $this->db->insert($table, $data);
    }

    public function update($id, $d, $table='settings')
    {
        $d['date'] = $this->now;
        $this->db->where('id', $id);
        $this->db->update($table, $d);

        return TRUE;
    }

    public function set($name, $value, $table='settings') {
        $data = array('date' => $this->now, 'value' => $value);
        $this->db->where('name', $name);
        $this->db->update($table, $data);

        return TRUE;
    }

    public function get($name, $module = NULL, $table='settings')
    {
        if ($module)
            $this->db->where('module', $module);

        $row = $this->db->from($table)
            ->where('name', $name)
            ->get()
            ->row();

        if ($row)
            return $row->value;

        return 'undefined';
    }

    public function getSetting($id, $table='settings')
    {
        return $this->db->from($table)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function getPage($uri) {
        if ($r = $this->db->from('page')->where('uri', $uri)->get()) {
            return $r->row();
        }
        return FALSE;
    }
    
    public function getModules(){
        return $this->db->select('module')->distinct('module')->get('settings')->result();
    }
}