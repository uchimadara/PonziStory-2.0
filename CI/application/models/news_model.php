<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function by_slug($slug){
        return $this->db->where('slug', $slug)->get('news')->row();
    }
    
    public function mark_read($id, $userId) {
        if ($userId && $id) {
            $sql = "INSERT INTO news_read (news_id, user_id,date) values ($id, $userId, now()) ON DUPLICATE KEY UPDATE user_id = $userId";
            $this->db->query($sql);

           // $this->db->insert('news_read', array('news_id' =>$id, 'user_id' => $userId));
        }
        return TRUE;
    }
    
    public function isUnread($userId) {
           $now = (int)now();
        if ($userId) {
            $sql = 'SELECT news.*, news_id FROM news
        LEFT JOIN news_read ON news_read.news_id = news.id AND news_read.user_id = '.$userId.'
        WHERE news_id IS NULL AND news.date > '.$now.' 
                ORDER BY date DESC LIMIT 1';

            $q = $this->db->query($sql)->row();

            if ($q) return array($q->id, $q->title, $q->slug, $q->content);
        }

        return FALSE;
    }

    public function slug($id) {
        $this->load->helper('text');

        $news = $this->db->from('news')->where('id', $id)->get()->row();

        //$slug = trim(convert_accented_characters($news->title));
        $slug = url_title($news->title);
        // replace non letter or digits by -
        //$slug = preg_replace('~[^\pL\d]+~u', '-', $slug);

        // transliterate
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // trim
        $slug = trim($slug, '-');

        // remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);


        if ($slug == '')
            $slug = uniqid();

        $exists = $this->db->from('news')
                ->where('slug', $slug)
                ->count_all_results() > 0;

        if ($exists)
            $slug .= '-'.uniqid();

        $this->db->where('id', $id)->update('news', array('slug' => $slug));

        return TRUE;
    }

    public function getAll()
    {
        return $this->db->from('news')
            ->where('code <>', 'eb')
            ->get()
            ->result();
    }

    public function getSome($count = 3)
    {
        return $this->db->from('news')
            ->order_by('id', 'desc')
            ->limit($count)
            ->get()
            ->result();
    }


}