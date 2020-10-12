<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModules($exclude = null) {
        if ($exclude) $this->db->where_not_in('place', $exclude);

        $r =  $this->db->select('distinct place', false)->from('cms_menu')->get()->result();

        $result = array();
        foreach ($r as  $row) {
            $result[] = $row->place;
        }

        return $result;
    }

    public function getAll($module = NULL, $table='cms_menu')
    {
        $data = array();
        if ($module)
            $this->db->where('place', $module);

        $parent = $this->db->from($table)->where('parent_id', 0)
                           ->order_by('position', 'asc')->get()->result();
        foreach($parent as $p){
            $p->children = $this->db->from($table)->where('parent_id', $p->id)
                           ->order_by('position', 'asc')->get()->result();
            $data[] = $p;
        }
        return $data;
    }

    public function insert($data, $table='cms_menu')
    {
        if (empty($data['parent_id'])) $data['parent_id'] = 0;
        return $this->db->insert($table, $data);
    }

    public function update($id, $d, $table='cms_menu')
    {
        $data  = array();
        $columns =$this->db->list_fields($table);

        foreach($columns as $v){
          if(isset($d[$v])){
            //log_message ('debug', 'db_form::update data['.$v.'] = '.$this->input->post($v, TRUE));
            $data[$v] = $d[$v];
          }
        }

        $this->db->where('id', $id);
        $this->db->update($table, $data);

        return TRUE;
    }

    public function getMenu($id, $table='cms_menu')
    {
        return $this->db->from($table)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function updatePosition($place, $parentId){
        $i = 0;
        foreach ($_POST['item'] as $value) {
            $this->db->update('cms_menu', array('position' => $i), array('place'=> $place, 'parent_id' => $parentId, 'id' => $value));
            echo $this->db->last_query();
            $i++;
        }
    }

    public function delete($id){
        return $this->db->where('id', $id)->delete('cms_menu');
    }
}