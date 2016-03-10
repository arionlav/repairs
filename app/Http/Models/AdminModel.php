<?php
namespace App\Http\Models;

use App\Http\Models\AdminModels\AdminPostModel;
use App\Http\Models\AdminModels\AdminCommentsModel;
use App\Http\Models\AdminModels\AdminUsersModel;
use App\Http\Models\AdminModels\AdminBeautyModel;
use App\Http\Models\AdminModels\AdminCategoryModel;

/**
 * Class AdminModel collect all admin models
 *
 * @package App\Http\Models
 */
class AdminModel
{
    use AdminPostModel,
        AdminCommentsModel,
        AdminUsersModel,
        AdminBeautyModel,
        AdminCategoryModel;
}