<?php
namespace App\Http\Models;

use DB;
use Auth;
use Mockery\CountValidator\Exception;

/**
 * Class GeneralModel contain methods for general using
 *
 * @package App\Http\Models
 */
class GeneralModel
{
    /**
     * Get articles with max likes
     *
     * @return array
     */
    public static function getPopularPosts()
    {
        $popularPosts = DB::table('posts')
            ->select(DB::raw('id, header, max(likes) as likes'))
            ->groupBy('id')
            ->orderBy('likes', 'desc')
            ->take(config('var.countPopularPosts'))
            ->get();

        return $popularPosts;
    }

    /**
     * Get fresh articles
     *
     * @return array
     */
    public static function getFreshPosts()
    {
        $freshPosts = DB::table('posts')
            ->select(DB::raw('id, header, max(date) as date'))
            ->groupBy('id')
            ->orderBy('date', 'desc')
            ->take(config('var.countPopularPosts'))
            ->get();

        return $freshPosts;
    }

    /**
     * Get array for beauty header
     *
     * @param int $categoryId Category id
     * @return array
     */
    public static function getBeauty($categoryId)
    {
        $groupBeauty = DB::table('category')
            ->where('id', $categoryId)
            ->value('beauty_id');

        $beautyArray = DB::table('beauty')
            ->where('beauty.group_beauty', '=', $groupBeauty)
            ->get();

        if (count($beautyArray) !== 5 and $categoryId == 1) {
            throw new Exception('Beauty #1 is wrong');
        } elseif (count($beautyArray) !== 5) {
            $beautyArray = GeneralModel::getBeauty(1);
        }

        return $beautyArray;
    }

    /**
     * Get url to selected article
     *
     * @param int    $id Article id
     * @param string $header
     * @return string
     */
    public static function getPostUrl($id, $header)
    {
        return $url = url('post' . $id . '/'
                          . strtolower(static::translit($header)));
    }

    /**
     * Get the number of likes for article
     *
     * @static
     * @param int $idPost Article id
     * @return array
     */
    public static function getLikes($idPost)
    {
        return DB::table('posts')
            ->select([
                'header',
                'likes'
            ])
            ->where('id', $idPost)
            ->first();
    }

    /**
     * Get date in wc3 format
     *
     * @param $enterDate
     * @return bool|string
     */
    public static function getTimeDatetime($enterDate)
    {
        $timeUnix = strtotime($enterDate);

        return date('Y-m-d\TH:i:s', $timeUnix);
    }

    /**
     * Convert the date timestamp from database to russian format
     * Example: '16 октября в 14:30'
     * or '16 октября 2015 в 14:30'
     * or '16 октября 2015'
     *
     * @param string $enterDate
     * @param bool   $withYear If it is true - return date with year
     * @param bool   $justDate Return just date
     * @return string
     */
    public static function getRussianDate($enterDate, $withYear = false, $justDate = false)
    {
        $timeUnix = strtotime($enterDate);
        $month    = date('m', $timeUnix);
        $m        = 'Января';
        switch ($month) {
            case 1:
                $m = 'Января';
                break;
            case 2:
                $m = 'Февраля';
                break;
            case 3:
                $m = 'Марта';
                break;
            case 4:
                $m = 'Апреля';
                break;
            case 5:
                $m = 'Мая';
                break;
            case 6:
                $m = 'Июня';
                break;
            case 7:
                $m = 'Июля';
                break;
            case 8:
                $m = 'Августа';
                break;
            case 9:
                $m = 'Сентября';
                break;
            case 10:
                $m = 'Октября';
                break;
            case 11:
                $m = 'Ноября';
                break;
            case 12:
                $m = 'Декабря';
                break;
        }

        $day  = date('j', $timeUnix);
        $time = date('H:i', $timeUnix);

        if ($withYear === false) {
            $date = $day . '&nbsp;' . $m . ' в ' . $time;
        } else {
            ($justDate === true)
                ? $date = $day . '&nbsp;' . $m . '&nbsp;' . date('Y', $timeUnix)
                : $date = $day . '&nbsp;' . $m . '&nbsp;\'' . date('y', $timeUnix) . ' в ' . $time;
        }

        return $date;
    }

    /**
     * Change cyrillic to latin symbol
     *
     * @static
     * @param string $str
     * @return string
     */
    public static function translit($str)
    {
        $converter = [
            'а'   => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё'   => 'jo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k',
            'л'   => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с'   => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'c',
            'ч'   => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э'   => 'eh', 'ю' => 'yu', 'я' => 'ya', 'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г'   => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'JO', 'Ж' => 'Zh', 'З' => 'Z',
            'И'   => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О'   => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф'   => 'F', 'Х' => 'Kh', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shh',
            'Ь'   => '', 'Ы' => 'Y', 'Ъ' => '', 'Э' => 'Eh', 'Ю' => 'Yu', 'Я' => 'Ya',
            ' - ' => '-', ',' => '', '.' => '', '/' => '', '\\' => '', '+' => '', ':' => '',
            ';'   => '', '_' => '-', ' ' => '-', '\'' => '', '"' => '', '@' => '', '#' => '',
            '<'   => '', '>' => '', '?' => '', '!' => '', '№' => 'n', '$' => '', '%' => '', '&' => '',
            '*'   => '', '(' => '', ')' => '', ' – ' => '-'
        ];

        $string = strtr($str, $converter);

        return $string;
    }

    /**
     * Get all months in 'number of month' => 'name month' pairs
     *
     * @return array
     */
    public static function getAllMonth()
    {
        return [
            1  => 'Января',
            2  => 'Февраля',
            3  => 'Марта',
            4  => 'Апреля',
            5  => 'Мая',
            6  => 'Июня',
            7  => 'Июля',
            8  => 'Августа',
            9  => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'
        ];
    }

    /**
     * Get age with word 'год', 'года' or 'лет'
     *
     * @param string $born Date of birthday
     * @return string
     */
    public static function getAge($born)
    {
        $dateBorn = new \DateTime($born);
        $dateNow  = new \DateTime(date('Y-m-d'));

        $difference = $dateBorn->diff($dateNow);

        $t1   = $difference->y % 10;
        $t2   = $difference->y % 100;
        $word = $t1 == 1 && $t2 != 11 ? " год" : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? " года" : " лет");

        return $difference->y . $word;
    }
}
