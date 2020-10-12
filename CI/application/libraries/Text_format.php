<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Text_format
{
    protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();

        $this->ci->load->helper('smiley');
        $this->ci->load->library('table');
	}

    public function show_text_formated($str)
    {
        $bold_in  = array('[b]', '[/b]');
        $bold_out = array('<b>', '</b>');

        $italic_in  = array('[i]', '[/i]');
        $italic_out = array('<i>', '</i>');

        $underline_in  = array('[u]', '[/u]');
        $underline_out = array('<u>', '</u>');

        $image_in  = array('[img]', '[/img]');
        $image_out = array('<img src="', '" />');

        $url_in  = array('[url', '[/url', ']');
        $url_out = array('<a href', '</a', '>');

        $str = str_replace($bold_in, $bold_out, $str);
        $str = str_replace($italic_in, $italic_out, $str);
        $str = str_replace($underline_in, $underline_out, $str);
        $str = str_replace($image_in, $image_out, $str);
        $str = str_replace($url_in, $url_out, $str);

        return $str;
    }

    // AddSmileys
    public function add_smileys($base_url, $msgId, $colNumber)
    {
        $image_array  = get_clickable_smileys($base_url, $msgId);
        $col_array    = $this->ci->table->make_columns($image_array, $colNumber);
        $smiley_table = $this->ci->table->generate($col_array);

        return $smiley_table;
    }

    /**
     * CodeIgniter BBCode Helpers
     *
     * @package  CodeIgniter
     * @subpackage Helpers
     * @category Helpers
     * @author  Philip Sturgeon
     * @changes  MpaK http://mrak7.com
     * @link  http://codeigniter.com/wiki/BBCode_Helper/
     */

// ------------------------------------------------------------------------

    /**
     * parse_bbcode
     *
     * Converts BBCode style tags into basic HTML
     *
     * @access public
     * @param string unparsed string
     * @param int max image width
     * @return string
     */

    function parse_bbcode($str = '', $max_images = 0) {
// Max image size eh? Better shrink that pic!
        if ($max_images > 0):
            $str_max = "style=685fdf61c833dc53d197f0a2d1a04148f90f0d4aquot;max-width:".$max_images."px; width: [removed]this.width > ".$max_images." ? ".$max_images.": true);685fdf61c833dc53d197f0a2d1a04148f90f0d4aquot;";
        endif;

        $find = array(
            "'\[table\](.*?)\[/table\]'is",
            "'\[tr\](.*?)\[/tr\]'is",
            "'\[td\](.*?)\[/td\]'is",
            "'\[b\](.*?)\[/b\]'is",
            "'\[i\](.*?)\[/i\]'is",
            "'\[u\](.*?)\[/u\]'is",
            "'\[s\](.*?)\[/s\]'is",
            "'\[ul\](.*?)\[/ul\]'is",
            "'\[list\](.*?)\[/list\]'is",
            "'\[ol\](.*?)\[/ol\]'is",
            "'\[li\](.*?)\[/li\]'is",
            "'\[\*\](.*?)\[/\*\]'is",
            "'\[img\](.*?)\[/img\]'is",
            "'\[quote\](.*?)\[/quote\]'is",
            "'\[url\](.*?)\[/url\]'is",
            "'\[url=(.*?)\](.*?)\[/url\]'is",
            "'\[link\](.*?)\[/link\]'is",
            "'\[link=(.*?)\](.*?)\[/link\]'is",
            "'\[font=(.*?)\](.*?)\[/font\]'is",
            "'\[color=(\#[0-9A-F]{6})\](.*?)\[/color\]'is",
            "'\[size=(.*?)\](.*?)\[/size\]'is"
        );

        $replace = array(
            '<table>\1</table>',
            '<tr>\1</tr>',
            '<td>\1</td>',
            '<strong>\1</strong>',
            '<em>\1</em>',
            '<span class="underline">\1</span>',
            '<s>\1</s>',
            '<ul>\1</ul>',
            '<ul>\1</ul>',
            '<ol>\1</ol>',
            '<li>\1</li>',
            '<li>\1</li>',
            '<img src="\1" alt="" />',
            '<span class="quoteHeader"></span><span class="msgQuote">\1</span>',
            '<a href="\1">\1</a>',
            '<a href="\1">\2</a>',
            '<a href="\1">\1</a>',
            '<a href="\1">\2</a>',
            '<span style="font-family:\1">\2</span>',
            '<span style="color:\1">\2</span>',
            '<font size="\1">\2</font>',
        );

        return preg_replace($find, $replace, $str);

}

    public function convert_links($str, $popup=TRUE) {

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><body>'.$str.'</body></html>');
        foreach ($doc->getElementsByTagName('a') as $item) {

            $href = $item->getAttribute('href');
            if ($item->hasAttribute('class')) {
                $class = $item->getAttribute('class');
                if ($class == 'popupImg') continue;
            } else {
                $item->setAttribute('class', 'underline');
            }

            $item->setAttribute('href', base_url().'forum/redirect/'.base64_encode($href));
            if ($popup) $item->setAttribute('target', 'blank_');
        }
        $html  = $doc->saveHTML(); //$doc->getElementsByTagName('body')->item(0)->nodeValue;
        preg_match('/<body>(.*)<\/body>/s', $html, $matches);

        return $matches[1];

    }
}

