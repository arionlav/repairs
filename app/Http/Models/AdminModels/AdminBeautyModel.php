<?php
namespace App\Http\Models\AdminModels;

use Mockery\CountValidator\Exception;
use DB;
use Input;

/**
 * Trait AdminBeautyModel provide logic for work with 'beauty' from admin panel
 *
 * @package App\Http\Models\AdminModels
 */
trait AdminBeautyModel
{
    /**
     * Get all 'beauty'
     *
     * @return array
     */
    public function getAllBeauty()
    {
        return DB::table('beauty')
            ->get();
    }

    /**
     * Change 'beauty' images
     *
     * @return true
     * @throw Exception
     */
    public function changeBeautyImages()
    {
        $input = Input::all();

        foreach ($input as $key => $fileInput) {
            if ($key == '_token') {
                continue;
            }
            $id = strtr($key, ['fileInput' => '']);
            if (Input::hasFile($key)) {
                $pathToUpload = 'resources/img/beauty';
                $fileName     = $id . '.jpg';

                if (is_dir(base_path() . '/' . $pathToUpload)) {
                    Input::file($key)
                        ->move(base_path() . '/' . $pathToUpload, $fileName);
                } else {
                    throw new Exception('Dir ' . $pathToUpload . ' not exists');
                }
            }
        }

        return true;
    }

    /**
     * Create group by 'group_beauty' array
     *
     * @param \StdClass $beauty
     * @return array
     */
    public function createBeautyPrettyArray($beauty)
    {
        $arr = [];
        foreach ($beauty as $b) {
            $arr[$b->group_beauty][] = $b;
        }

        return $arr;
    }

    /**
     * Get group 'beauty'
     *
     * @param int $id 'beauty' group id
     * @return array
     */
    public function getBeautyGroupById($id)
    {
        return DB::table('beauty')
            ->where('group_beauty', $id)
            ->get();
    }

    /**
     * Get all articles ids and headers
     *
     * @return array
     */
    public function getAllPosts()
    {
        return DB::table('posts')
            ->select('id', 'header')
            ->get();
    }

    /**
     * Update beauty
     *
     * @return true
     */
    public function updateBeauty()
    {
        $input = Input::get();
        if (! empty($input)) {
            foreach ($input as $key => $val) {
                if ($key == '_token') {
                    continue;
                }
                if (is_int($key)) {
                    DB::table('beauty')
                        ->where('id', $val)
                        ->update([
                            'header'      => $input['header' . $val],
                            'description' => $input['description' . $val],
                            'id_post'     => $input['post' . $val],
                            'number'      => $input['number' . $val]
                        ]);
                }
            }
        }

        return true;
    }

    /**
     * Insert new 'beauty'
     *
     * @return true
     * @throw Exception
     */
    public function insertNewBeauty()
    {
        $input       = Input::all();
        $insertArray = [];

        $groupBeauty = DB::table('beauty')
                           ->max('group_beauty') + 1;

        for ($i = 1; $i <= 5; $i++) {
            if (! empty($input)) {
                foreach ($input as $key => $val) {
                    if ($key === 'header' . $i) {
                        $insertArray['header'] = $val;
                        continue;
                    } elseif ($key === 'desc' . $i) {
                        $insertArray['description'] = $val;
                        continue;
                    } elseif ($key === 'num' . $i) {
                        $insertArray['number'] = $val;
                        continue;
                    } elseif ($key === 'post' . $i) {
                        $insertArray['post'] = $val;
                        continue;
                    } elseif ($key === 'post' . $i) {
                        $insertArray['post'] = $val;
                        continue;
                    }
                }
            }

            $id = DB::table('beauty')
                ->insertGetId([
                    'header'       => $insertArray['header'],
                    'description'  => $insertArray['description'],
                    'id_post'      => $insertArray['post'],
                    'number'       => $insertArray['number'],
                    'group_beauty' => $groupBeauty
                ]);

            if (Input::hasFile('img' . $i)) {
                $pathToUpload = 'resources/img/beauty';
                $fileName     = $id . '.jpg';

                if (is_dir(base_path() . '/' . $pathToUpload)) {
                    Input::file('img' . $i)
                        ->move(base_path() . '/' . $pathToUpload, $fileName);
                } else {
                    throw new Exception('Dir ' . $pathToUpload . ' not exists');
                }
            }
        }

        return true;
    }

    /**
     * Get all 'beauty' groups
     *
     * @return array
     */
    public function getBeautyGroups()
    {
        return DB::table('beauty')
            ->select('group_beauty')
            ->distinct()
            ->get();
    }
}