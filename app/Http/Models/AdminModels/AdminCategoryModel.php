<?php
namespace App\Http\Models\AdminModels;

use Mockery\CountValidator\Exception;
use DB;
use Input;

/**
 * Trait AdminCategoryModel provide logic for work with categories from admin panel
 *
 * @package App\Http\Models\AdminModels
 */
trait AdminCategoryModel
{
    /**
     * Get category by id
     *
     * @param int $id Category id
     * @return array
     */
    public function getCategoryById($id)
    {
        return DB::table('category')
            ->where('id', $id)
            ->first();
    }

    /**
     * Get all parents categories
     *
     * @return array
     */
    public function getAllCategoryParents()
    {
        return DB::table('category_parent')
            ->get();
    }

    /**
     * Update category
     *
     * @param array $input Input values
     * @return bool
     */
    public function updateCategory($input)
    {
        return DB::table('category')
            ->where('id', $input['id'])
            ->update([
                'name'             => $input['name'],
                'main'             => $input['categoryParent'],
                'beauty_id'        => $input['beautyId'],
                'hide_horizontal'  => $input['horizontalMenu'],
                'meta_title'       => $input['metaTitle'],
                'meta_keyword'     => $input['metaKeywords'],
                'meta_description' => $input['metaDescription']
            ]);
    }

    /**
     * Insert new category
     *
     * @return bool
     */
    public function insertNewCategory()
    {
        $input = Input::get();

        return DB::table('category')
            ->insert([
                'name'             => $input['name'],
                'main'             => $input['categoryParent'],
                'hide_horizontal'  => $input['horizontalMenu'],
                'beauty_id'        => $input['beautyId'],
                'meta_title'       => $input['metaTitle'],
                'meta_keyword'     => $input['metaKeywords'],
                'meta_description' => $input['metaDescription']
            ]);
    }
}
