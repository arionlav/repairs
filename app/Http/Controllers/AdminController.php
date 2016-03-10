<?php
namespace App\Http\Controllers;

use App\Http\Models\AdminModel;
use App\Http\Models\GeneralModel;
use Gate;
use Response;
use Input;

/**
 * Class AdminController is responsible for handling admin panel requests
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @var AdminModel
     */
    protected $model;

    /**
     * Check privileges and set model class in $this->model variable
     */
    public function __construct()
    {
        if (Gate::denies('isAdmin', config('var.lowestEnterRole'))) {
            abort(404);
        }

        $this->model = new AdminModel();
    }


    /**
     * The start page of admin panel
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('admin.index');
    }

    /**
     * Page for creating article
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        $allCategories = $this->model->getAllCategories();

        return view('admin.create', [
            'allCategories' => $allCategories
        ]);
    }

    /**
     * In this route is preview mode and insert/update mode
     *
     * @return \Illuminate\Http\Response
     */
    public function postInsert()
    {
        $input = Input::get();

        if ($post = $this->model->checkPreviewMode($input)) {
            $postDb = $post['db'];

            return view('posts.fullText', [
                'post'      => $post,
                'postDb'    => $postDb,
                'samePosts' => []
            ]);
        }

        $id = $this->model->runInsertUpdate($input);

        return redirect()->to(GeneralModel::getPostUrl($id, $input['header']));
    }

    /**
     * Page, where we choose article for modify
     *
     * @return \Illuminate\Http\Response
     */
    public function getModify()
    {
        $posts = $this->model->getAllPostLinks();

        return view('admin.modifyIndex', [
            'posts' => $posts
        ]);
    }

    /**
     * Page with form for modify article
     *
     * @param int $id Article id
     * @return \Illuminate\Http\Response
     */
    public function getModifyArticle($id)
    {
        $post          = $this->model->getPostById($id);
        $allCategories = $this->model->getAllCategories();

        return view('admin.create', [
            'post'          => $post,
            'id'            => $id,
            'allCategories' => $allCategories,
            'updateDate'    => true
        ]);
    }

    /**
     * Page, where we choose article for deleting
     *
     * @return \Illuminate\Http\Response
     */
    public function getDelete()
    {
        $posts = $this->model->getAllPostLinks();

        return view('admin.deleteIndex', [
            'posts' => $posts
        ]);
    }

    /**
     * Delete selected articles
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete()
    {
        $this->model->deleteSelectedPosts();

        return redirect()->to('admin/delete');
    }

    /**
     * Page with all comments
     *
     * @param int $dataTablesPage Page for return on current dataTables page
     * @return \Illuminate\Http\Response
     */
    public function getComments($dataTablesPage = 1)
    {
        $comments = $this->model->getAllComments();

        return view('admin.comments', [
            'comments'       => $comments,
            'dataTablesPage' => $dataTablesPage
        ]);
    }

    /**
     * Show form with comment info for update
     *
     * @param int $dataTablesPage Page for return on current dataTables page
     * @param int $id             Comment id
     * @return \Illuminate\Http\Response
     */
    public function getCommentsModify($dataTablesPage, $id)
    {
        $comment = $this->model->getCommentById($id);

        return view('admin.commentsModify', [
            'comment'        => $comment,
            'dataTablesPage' => $dataTablesPage
        ]);
    }

    /**
     * Update comment
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCommentsModify()
    {
        $input = Input::get();

        $this->model->updateComment($input);

        return redirect()->to('admin/comments/' . $input['dataTablesPage']);
    }

    /**
     * Delete comment
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCommentDelete($id)
    {
        $this->model->deleteComment($id);

        return response()->json($this->model->answers);
    }

    /**
     * Page with all users
     *
     * @param int $dataTablesPage Page for return on current dataTables page
     * @return \Illuminate\Http\Response
     */
    public function getUsers($dataTablesPage = 1)
    {
        $users = $this->model->getAllUsers();

        $comments = $this->model->getCountComments();

        return view('admin.users', [
            'users'          => $users,
            'comments'       => $comments,
            'dataTablesPage' => $dataTablesPage
        ]);
    }

    /**
     * Show form with user info for update
     *
     * @param int $dataTablesPage Page for return on current dataTables page
     * @param int $id             User id
     * @return \Illuminate\Http\Response
     */
    public function getUserModify($dataTablesPage, $id)
    {
        $user = $this->model->getUserById($id);

        return view('admin.userModify', [
            'user'           => $user,
            'dataTablesPage' => $dataTablesPage
        ]);
    }

    /**
     * Update user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserModify()
    {
        $input = Input::get();

        $this->model->updateUser($input);

        return redirect()->to('admin/users/' . $input['dataTablesPage']);
    }

    /**
     * Delete user
     *
     * @param int $id User id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUserDelete($id)
    {
        $this->model->deleteAvatar($id);

        $this->model->deleteUsersComments($id);

        if ($result = $this->model->deleteUserFromDb($id)) {
            $result = 'Удалили пользователя с id ' . $id;
        } else {
            $result = 'Не удалось удалить из базы данных';
        }

        return response()->json($result);
    }

    /**
     * Page with all 'beauty'
     *
     * @return \Illuminate\Http\Response
     */
    public function getBeauty()
    {
        $beauty = $this->model->getAllBeauty();

        $beautyPrettyArray = $this->model->createBeautyPrettyArray($beauty);

        return view('admin.beauty', [
            'beautyPrettyArray' => $beautyPrettyArray
        ]);
    }

    /**
     * Change images for 'beauty'
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBeautyChangeImage()
    {
        $this->model->changeBeautyImages();

        return redirect()->to('admin/beauty');
    }

    /**
     * Show form with 'beauty' info for modify
     *
     * @param int $id 'beauty' id
     * @return \Illuminate\Http\Response
     */
    public function getBeautyModify($id)
    {
        $posts = $this->model->getAllPosts();

        $beautyGroup = $this->model->getBeautyGroupById($id);

        return view('admin.beautyModify', [
            'beautyGroup' => $beautyGroup,
            'posts'       => $posts
        ]);
    }

    /**
     * Update 'beauty'
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBeautyModify()
    {
        $this->model->updateBeauty();

        return redirect()->to('admin/beauty');
    }

    /**
     * Page with form for creating a 'beauty'
     *
     * @return \Illuminate\Http\Response
     */
    public function getBeautyCreate()
    {
        $posts = $this->model->getAllPosts();

        return view('admin.beautyCreate', [
            'posts' => $posts
        ]);
    }

    /**
     * Create a 'beauty'
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBeautyCreate()
    {
        $this->model->insertNewBeauty();

        return redirect()->to('admin/beauty');
    }

    /**
     * Page with all categories
     *
     * @param int $dataTablesPage Page for return on current dataTables page
     * @return \Illuminate\Http\Response
     */
    public function getCategories($dataTablesPage = 1)
    {
        $categories = $this->model->getAllCategories();

        $beauty = $this->model->getBeautyGroups();

        return view('admin.categories', [
            'categories'     => $categories,
            'beauty'         => $beauty,
            'dataTablesPage' => $dataTablesPage
        ]);
    }

    /**
     * Show form for modify selected category
     *
     * @param int $dataTablesPage Page for return on current dataTables page
     * @param int $id             Category id
     * @return \Illuminate\Http\Response
     */
    public function getCategoryModify($dataTablesPage, $id)
    {
        $category = $this->model->getCategoryById($id);

        $beauty = $this->model->getBeautyGroups();

        $categoriesParent = $this->model->getAllCategoryParents();

        return view('admin.categoryModify', [
            'category'         => $category,
            'dataTablesPage'   => $dataTablesPage,
            'beauty'           => $beauty,
            'categoriesParent' => $categoriesParent
        ]);
    }

    /**
     * Update category
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCategoryModify()
    {
        $input = Input::get();

        $this->model->updateCategory($input);

        return redirect()->to('admin/categories/' . $input['dataTablesPage']);
    }

    /**
     * Page with form for creating new category
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryCreate()
    {
        $beauty = $this->model->getBeautyGroups();

        $categoriesParent = $this->model->getAllCategoryParents();

        return view('admin.categoryCreate', [
            'beauty'           => $beauty,
            'categoriesParent' => $categoriesParent
        ]);
    }

    /**
     * Create new category
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCategoryCreate()
    {
        $this->model->insertNewCategory();

        return redirect()->to('admin/categories');
    }
}
