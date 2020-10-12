<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('pluralise'))
{
    function pluralise($string, $count)
    {
        if ($count == 1) return $string;

        // special cases
        switch (strtolower(trim($string)))
        {
            case 'is':
                return 'are';
        }

        $idx = strlen ($string) - 1;
        if ($string[$idx] == 'y')  {
            $vowels = array('a', 'e', 'o', 'u'); // there are no words that end in "iy"
            if (!in_array($string[$idx-1], $vowels)) return substr($string, 0, $idx) . 'ies';
        }

        return $string . 's';
    }
}

if (!function_exists('wordify')) {
    function wordify($string) {
        return ucwords(str_replace('_', ' ', $string));
    }
}

if (!function_exists('dropdown'))
{
    function dropdown($dataset, $value = 'id', $text = 'name')
    {
        $ret = array();
        foreach ($dataset as $ds)
        {
            if (isset ($ds->$value) && isset ($ds->$text))
                $ret[$ds->$value] = $ds->$text;
        }

        return $ret;
    }
}

if (!function_exists('roundDown'))
{
	function roundDown($amount, $decimals = 4)
	{
		return floor ($amount * pow(10, $decimals)) / pow(10, $decimals);
	}
}

if (!function_exists('roundUp'))
{
	function roundUp($amount, $decimals = 4)
	{
		return ceil ($amount * pow(10, $decimals)) / pow(10, $decimals);
	}
}

if (!function_exists('spellNumber'))
{
	function spellNumber($number)
	{
		 $numbers = array(
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
         );
        return $numbers[$number];
	}
}

if (!function_exists('money'))
{
    function money($amount, $currency = '₦', $forceSign = FALSE, $maxDecimals = 7)
    {
        $decimals = 2;

        $amount = number_format($amount, 7, '.', ''); // that should be the max decimals ever

        $decimalValue = substr(strrchr((string)$amount, "."), 1);
        if ($decimalValue)
        {
            while ($decimalValue && $decimalValue{strlen($decimalValue) - 1} == '0')
                $decimalValue = substr($decimalValue, 0, -1);

            // Max of 5 decimals, minimum of 2
            $decimals = max(min($maxDecimals, strlen($decimalValue)), 2);
        }

        $formattedAmount = number_format(sprintf("%01.{$decimals}f", abs($amount)), $decimals);
        switch ($currency)
        {
            case 'USD':
            case '$':
                $formattedAmount = "$" . $formattedAmount;
                break;

            case 'EUR':
                $formattedAmount = "&euro;" . $formattedAmount;
                break;

            case '%':
                $formattedAmount = $formattedAmount . '%';
                break;

            default:
                $formattedAmount = $currency.$formattedAmount;
                break;
        }

        if ($amount < 0)
            return ($forceSign ? '<span class="red">' : '') . '-' . $formattedAmount . ($forceSign ? '</span>' : '');

        return ($forceSign ? '<span class="green">+' : '') . $formattedAmount . ($forceSign ? '</span>' : '');
    }
}

if (!function_exists('percent'))
{
    function percent($amount)
    {
        return money($amount, '', TRUE, 2);
    }
}

if (!function_exists('generatePagination'))
{
    function generatePagination($url, $count, $page, $perpage, $showPages = FALSE)
    {
        if (strpos($url, "%d") === FALSE)
            $url .= '/%d/%d';

        $pages = ceil ($count / $perpage);

        if ($pages > 15) // Showing style 1, 2, 3, 4, ... , 8, 9, <10>, 11, 12, ... , 57, 58, 59
        {
            // Beginning Initial pagination, from 1 to 4
            $paginate = array();
            $end_begin  = min(4, $pages);
            for($i=1; $i<=$end_begin; $i++)
            {
                $pageUrl = sprintf($url, $i, $perpage);
                $paginate[] = ($i == $page) ? '<strong>' . $i . '</strong>' :
                        '<a href="' . $pageUrl . '" class="pagination">' . $i . '</a>';
            }

            // Middle
            $init_middle = max ($end_begin+1, $page-2);
            $end_middle = min($page+2, $pages);

            if($page > $end_begin + 2)
                $paginate[] = ' ... '; //To include '...' between numbers

            for ($i = $init_middle; $i <= $end_middle; $i++)
            {

                $pageUrl = sprintf($url, $i, $perpage);
                $paginate[] = ($i == $page) ? '<strong>' . $i . '</strong>' :
                        '<a href="' . $pageUrl . '" class="pagination">' . $i . '</a>';
            }

            //Ending
            $init_ending = max ($end_middle + 1, $pages-2);
            if ($init_ending != $end_middle+1)
                $paginate[] = ' ... '; //To include '...' between numbers

            for ($i = $init_ending; $i <= $pages; $i++)
            {
                $pageUrl = sprintf($url, $i, $perpage);
                $paginate[] = '<a href="' . $pageUrl . '" class="pagination">' . $i . '</a>';
            }

            $paging = implode(', ',$paginate);
        }
        else  //Showing style 1, 2, 3, 4, <5>, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15
        {
            $paging = '';
            for ($i = 0; $i < $pages; $i++)
            {
                $start = $i * $perpage + 1;
                $end   = min ($start + $perpage - 1, $count);
                $index = $i + 1;

                $pageUrl = sprintf($url, $index, $perpage);

                if ($showPages)
                    $paging .= ($paging ? ', ' : '') . (($i + 1) == $page ? "<strong>$page</strong>" : '<a href="' . $pageUrl . '" class="pagination">' . ($i + 1) . '</a>');
                else $paging .= ($paging ? ', ' : '') . (($i + 1) == $page ? "<strong>$start-$end</strong>" : '<a href="' . $pageUrl . '" class="pagination">' . $start . '-' . $end . '</a>');
            }
        }

        return $paging;
    }
}

if (!function_exists('dateToDays'))
{
    function dateToDays($date)
    {
        return ceil((now() - $date) / 86400);
    }
}

if (!function_exists('ellipsis'))
{
    function ellipsis($string, $length, $stopanywhere = FALSE)
    {
        if (strlen($string) > $length)
        {
            $string = substr($string, 0, ($length - 1));
            if ($stopanywhere)
            {
                $string .= '&hellip;';
            }
            else
            {
                $string = substr($string, 0, strrpos($string,' ')) . '&hellip;';
            }
        }

        return $string;
    }
}

//This function is used to format decimal numbers to the right
if (!function_exists('deleteZeros'))
{
    function deleteZeros($number)
    {
        $number = number_format($number, 5);
        //format of number
        $num = explode(".",$number);
        $long = strlen($num[1]);
        $dec = 5;
        for ($i=($long-1); $i>=0 ;$i--)
        {
            if(($num[1]{$i}) == 0)
                $dec--;
            else
                break;
        }
        $num = implode(".",$num);

        $dec = ($dec<=2) ? 2 : $dec;
        return number_format($num,$dec,'.',',');
    }
}

function elapsedTime($origin, $stopTime = NULL, $ago = FALSE)
{
    $fromNow = ($stopTime == NULL);

    if (!$stopTime)
        $stopTime = now();

    $offset = $stopTime - $origin;

    if ($offset < 30) // Less than 30 seconds
    {
        if ($fromNow)
            return 'just now';
        else
        {
            return $offset > 0 ? $offset . ' ' . pluralise('sec', $offset) : 'instantly';
        }
    }

    $ago = ($ago) ? ' ago' : '';

    if ($offset < 3600) // Less than 1 hour
    {
        $count = ceil($offset / 60);
        return $count . ' ' . pluralise('min', $count).$ago;
    }

    if ($offset < 86400) // Less than 1 day
    {
        $count = ceil($offset / 3600);
        return $count . ' ' . pluralise('hour', $count).$ago;
    }

    // in days
    $count = ceil($offset / 86400);
    return $count . ' ' . pluralise('day', $count).$ago;
}

function elapsed_hm($origin) {
    $stopTime = now();
    $offset = $stopTime - $origin;

    if ($offset < 60)
    {
        return 'just now';
    }


    $count = floor($offset/3600);
    $hours = str_pad($count, 2, '0', STR_PAD_LEFT);

    $count = ceil(($offset % 3600)/60);
    $minutes = str_pad($count, 2, '0', STR_PAD_LEFT);

    return $hours.':'.$minutes;
}

function eta($deadline)
{
    $offset = $deadline - now();

    if ($offset < 30) // Less than 60 seconds
        return $offset . ' ' . pluralise('sec', $offset);

    if ($offset < 3600) // Less than 1 hour
    {
        $count = ceil($offset / 60);
        return $count . ' ' . pluralise('min', $count);
    }

    if ($offset < 86400) // Less than 1 day
    {
        $count = ceil($offset / 3600);
        return $count . ' ' . pluralise('hour', $count);
    }

    // in days
    $count = ceil($offset / 86400);
    return $count . ' ' . pluralise('day', $count);
}

function displayCountDown($offset, $stringified = FALSE, $minified = FALSE)
{
    $days = floor($offset / (24 * 3600));
    $offset -= $days * 24 * 3600;

    $hours = floor($offset / 3600);
    $offset -= $hours * 3600;

    $minutes = floor($offset / 60);
    $offset -= $minutes * 60;

    $seconds = $offset;

    if ($stringified)
    {
        $result = NULL;
        if ($days) $result .= $days . ($minified ? 'd' : ' ' . pluralise('day', $days));
        if ($hours || $result) $result .= ($result ? ', ' : '') . $hours . ($minified ? 'h' : ' ' . pluralise('hour', $hours));
        if ($minutes || $result) $result .= ($result ? ', ' : '') . $minutes . ($minified ? 'm' : ' ' . pluralise('minute', $minutes));
        if ($seconds || $result) $result .= ($result ? ', ' : '') . $seconds . ($minified ? 's' : ' ' . pluralise('second', $seconds));

        return $result;
    }

    $hours  += $days * 24; // convert what is a number of days into number of hours

    $hours   = str_pad($hours, 2, '0', STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

    return $hours . ':' . $minutes . ':' . $seconds;
}

function prettify($data)
{
    $result = '';
    foreach ($data as $k=>$v)
    {
        $k = str_replace ('_', ' ', $k);
        if ($v != '')
            $result .= ($result ? '<br/>' : '') . '<u>' . ucwords($k) . '</u>: ' . $v;
    }

    return $result;
}

function renderErrors($errorArray)
{
    $result = '';
    foreach ($errorArray as $error)
        $result .= "<li>$error</li>";

    return "The following errors have been found:<ul>$result</ul>";
}

function stringifyBill($data, $operation, $type)
{
    if (isset($data[$operation]) && isset($data[$operation][$type]))
    {
        $billData = $data[$operation][$type];
        $percent  = (isset($billData->percent) && $billData->percent > 0) ? $billData->percent . '%'        : NULL;
        $fixed    = (isset($billData->fixed) && $billData->fixed > 0)     ? money($billData->fixed)         : NULL;
        $max      = (isset($billData->max) && $billData->max > 0)         ? 'MAX: ' . money($billData->max) : NULL;

        $string = NULL;
        if ($percent) $string .= $percent;
        if ($fixed)   $string .= ($string ? ' + ' : '') . $fixed;
        if ($max)     $string .= ($string ? ' ' : '') . "($max)";

        if ($string) return $string;
    }

    return '<span class="inactive">none</span>';
}

function calculateGoal($cost, $revenues)
{
    if ($revenues < 0)
        return 0;

    if ($cost == 0) $cost = 0.01;

    return roundUp($revenues / (($cost * 3.63) / 100), 2);
}

function ordinal($num, $html = FALSE)
{
    $test = abs($num) % 10;
    $ext = ((abs($num) %100 < 21 && abs($num) %100 > 4) ? 'th' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));

    if ($html) $ext = '<sup>'.$ext.'</sup>';
    return $num . $ext;
}

function cacheKey($key)
{
    return KEY_PREFIX . $key;
}

function highlight_strict($str, $phrase, $tag_open = '<span class="highlight">', $tag_close = '</span>')
{
    if ($str == '')
        return '';

    if ($phrase != '')
        return preg_replace('/('.preg_quote($phrase, '/').'\w*)/i', $tag_open."\\1".$tag_close, $str);

    return $str;
}