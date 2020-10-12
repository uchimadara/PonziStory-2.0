<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function by_slug($slug){
        return $this->db->where('slug', $slug)->get('blog')->row();
    }

    public function mark_read($id, $userId) {
        if ($userId && $id) {
            $sql = "INSERT INTO news_read (blog_id, user_id) values ($id, $userId) ON DUPLICATE KEY UPDATE user_id = $userId";
            $this->db->query($sql);

            // $this->db->insert('news_read', array('blog_id' =>$id, 'user_id' => $userId));
        }
        return TRUE;
    }

    public function isUnread($userId) {
        if ($userId) {
            $sql = 'SELECT blog.*, blog_id FROM blog
        LEFT JOIN news_read ON news_read.blog_id = blog.id AND news_read.user_id = '.$userId.'
        WHERE blog_id IS NULL
                ORDER BY date DESC LIMIT 1';

            $q = $this->db->query($sql)->row();

            if ($q) return array($q->id, $q->title, $q->slug, $q->content);
        }

        return FALSE;
    }

    public function slug($id) {
        $this->load->helper('text');

        $news = $this->db->from('blog')->where('id', $id)->get()->row();

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

        $exists = $this->db->from('blog')
                ->where('slug', $slug)
                ->count_all_results() > 0;

        if ($exists)
            $slug .= '-'.uniqid();

        $this->db->where('id', $id)->update('blog', array('slug' => $slug));

        return TRUE;
    }

    public function getAll()
    {
        return $this->db->select('*')
            ->from('blog')
            ->get()
            ->result();
    }

    public function getSome($count = 3)
    {
        return $this->db->from('blog')
            ->order_by('id', 'desc')
            ->limit($count)
            ->get()
            ->result();
    }


}