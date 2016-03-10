<?php
namespace App\Http\Controllers;

use App\Http\Models\PostsModel;
use App\Http\Models\UserModel;
use Illuminate\Support\Facades\Input;

/**
 * Class PostsController is responsible for handling index page,
 * pages by category, keywords and increment likes
 *
 * @package App\Http\Controllers
 */
class PostsController extends Controller
{
    /**
     * Set model class in $this->model variable
     */
    public function __construct()
    {
        $this->model = new PostsModel();
    }

    /**
     * Index page on the site
     * Take all articles and show to users
     *
     * @param int $page The number of pagination page
     * @return \Illuminate\Http\Response
     */
    public function index($page = 1)
    {
        $this->model->getAllPosts($page);

        return view('index', [
            'posts'       => $this->model->posts,
            'showWelcome' => null
        ]);
    }

    /**
     * Show full article
     *
     * @param int $postId Article id
     * @return \Illuminate\Http\Response
     */
    public function currentPost($postId)
    {
        $this->model->incrementReview($postId);

        $post = $this->model->getPost($postId);

        $samePosts = $this->model->getSamePost($post['db']->keywords, $postId);

        $postDb = $post['db'];

        return view('posts.fullText', [
            'postId'    => $postId,
            'post'      => $post,
            'samePosts' => $samePosts,
            'postDb'    => $postDb
        ]);
    }

    /**
     * Show articles by keywords
     *
     * @param string $key
     * @param int    $page The number of pagination page
     * @return \Illuminate\Http\Response
     */
    public function key($key, $page = 1)
    {
        $this->model->getPostsByKey($key, $page);

        return view('index', [
            'posts' => $this->model->posts
        ]);
    }

    /**
     * Show articles by category
     *
     * @param int    $id           Category id
     * @param string $categoryName Category name
     * @param int    $page         The number of pagination page
     * @return \Illuminate\Http\Response
     */
    public function category($id, $categoryName, $page = 1)
    {
        $this->model->getCategory($id, $page, $categoryName);

        $categoryInfo = $this->model->getCategoryInfo($id);

        return view('index', [
            'posts'        => $this->model->posts,
            'categoryInfo' => $categoryInfo,
            'idCategory'   => $id
        ]);
    }

    /**
     * Increment like
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function likes()
    {
        $input = Input::only('id', 'likeFor');

        $result = $this->model->addLike($input['id'], $input['likeFor']);

        return response()->json($result);
    }

    /**
     * Public page with user information
     *
     * @param int $id User id
     * @return \Illuminate\Http\Response
     */
    public function getUser($id)
    {
        $model = new UserModel();
        $user  = $model->getuser($id);

        if (is_null($user)) {
            abort(404);
        }

        return view('user.user', ['user' => $user]);
    }
}