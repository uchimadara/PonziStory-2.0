<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll() {
        return $this->db->select('ifnull(nullif(concat_ws(" ", u.first_name, u.last_name), " "), u.username) member, t.*', FALSE)
                ->from('testimonial t')
                ->join('users u', 'u.id = t.user_id')
                ->where('t.status', 'approved')
                ->order_by('date', 'desc')
                ->get()->result();
    }

    public function getSome() {
        return $this->db->select('ifnull(nullif(concat_ws(" ", u.first_name, u.last_name), " "), u.username) member, t.*', FALSE)
            ->from('testimonial t')
            ->join('users u', 'u.id = t.user_id')
            ->where('t.status', 'approved')
            ->order_by('date', 'desc')
            ->limit(50)
            ->get()->result();
    }

    function testiCount($key)
    {

        return $this->db->from('testimonial')
            ->where('user_id', $key)
            ->where('status','approved')
            ->get()->num_rows();
    }

    function role_exists($key)
    {

        return $this->db->select("count(*) c", FALSE)
            ->from('testimonial')
            ->where('user_id', $key)
            ->where('status !=','rejected')
            ->get()->row()->c;

    }



    public function getRandom($count) {

        //if (($total = $this->getCache('testimonial_count') === FALSE)) {
            $total = $this->db->select('count(*) c', FALSE)
                        ->from('testimonial')->where('status', 'approved')->get()->row()->c;
       //     $this->saveCache($total);
       // }

        $rand = -1;
        if ($total > ($count)) {
            $rand = mt_rand(0, $total - $count - 1);
        } elseif ($total > 0) {
            $rand = 0;
        }

        if ($rand >= 0) {
            return $this->db
                    ->select("ifnull(nullif(concat_ws(u.first_name, u.last_name), ' '), u.username) member, u.avatar, t.user_id, t.date, t.content", FALSE)
                    ->from('testimonial t')
                    ->join('users u', 'u.id = t.user_id')
                    ->where('t.status', 'approved')
                    ->limit($count, $rand)
                    ->get()->result();
        }

        return array();
    }

    public function check($userId) {
        return $this->db->from('testimonial')
                ->where('user_id', $userId)
                ->where('status !=', 'rejected')
                ->count_all_results();
    }

    public function get($id) {
        return $this->db->from('testimonial')
                ->where('id', $id)
                ->get()->row();
    }

    public function getByUser($id) {
        return $this->db->from('testimonial')
                ->where('user_id', $id)
                ->where('status !=', 'rejected')
                ->get()->row();
    }


    public function update($id, $data) {
        return $this->db->where('id', $id)->update('testimonial', $data);
    }
}