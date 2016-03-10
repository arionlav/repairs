<?php
namespace App\Http\Models;

use DB;
use Auth;

/**
 * Class PostsModel provide logic for work with articles
 *
 * @package App\Http\Models
 */
class PostsModel
{
    /**
     * @static
     * @var array Array with answers on comment, if they are
     */
    protected static $answers = [];
    /**
     * @static
     * @var array comments, who are the answer and it is already show
     */
    protected static $doNotWriteAnswers = [];
    /**
     * @static
     * @var int The number of answers on current comment
     */
    protected static $countAnswers;

    /**
     * @var array Array with articles
     */
    public $posts = [];


    /**
     * Populate $this->posts of all posts from database
     *
     * @param string $page the word from config.var.wordInPagination concat the number of page
     */
    public function getAllPosts($page)
    {
        $page = $this->cleanPagePaginate($page);

        $posts = DB::table('posts')
            ->orderBy('date', 'desc')
            ->get();

        $this->posts = new Paginator($posts, config('var.postsOnPage'), url('/'), $page);

        $this->getCountComment();
    }

    /**
     * Populate $this->posts with posts from selected category
     *
     * @param int    $id   Category id
     * @param string $page The word from config.var.wordInPagination concat the number of page
     * @param string $categoryName
     */
    public function getCategory($id, $page, $categoryName)
    {
        $page = $this->cleanPagePaginate($page);

        $posts = DB::table('posts')
            ->where('category', '=', $id)
            ->orderBy('date', 'desc')
            ->get();

        $this->posts = new Paginator($posts, config('var.postsOnPage'),
            url('category' . $id . '/' . $categoryName . '/'), $page);

        $this->getCountComment();
    }

    /**
     * Get info about category by id
     *
     * @param int $id Category id
     * @return object stdClass
     */
    public function getCategoryInfo($id)
    {
        $categoryInfo = DB::table('category')
            ->where('id', '=', $id)
            ->first();

        return $categoryInfo;
    }

    /**
     * Add comments to the $this->posts property
     * with the number of comments for all posts in $this->posts
     */
    protected function getCountComment()
    {
        $i = 0;
        foreach ($this->posts as $post) {
            $comments = DB::table('comments')
                ->where('id_post', $post->id)
                ->get();

            $this->posts[$i]->comments = count($comments);
            $i++;
        }
    }

    /**
     * Get post from database
     *
     * @param int $id Article id
     * @return array
     */
    public function getPost($id)
    {
        $post = $this->getPostFiles($id);

        $post['db'] = $this->getPostFromDb($id);

        $post['comments'] = $this->getComments($post['db']);

        if (Auth::check()) {
            $user = Auth::user();
            $this->incrementReviewToUser($user->id);
        }

        return $post;
    }

    /**
     * Get full text and images for article
     *
     * @param int $id Article id
     * @return array|404
     */
    protected function getPostFiles($id)
    {
        $post = [];
        $path = base_path() . '/resources/posts/' . $id;
        if (is_dir($path)) {
            $iterator      = new \DirectoryIterator($path);
            $pathToPostDir = config('var.pathToRoot') . '/resources/posts/' . $id . '/';

            while ($iterator->valid()) {
                $file = $iterator->current();
                if (
                    $file->getExtension() == 'jpg' or
                    $file->getExtension() == 'jpeg' or
                    $file->getExtension() == 'png'
                ) {
                    (strpos($file->getFilename(), 'main') !== false)
                        ? $post['img']['main'] = $pathToPostDir . $file->getFilename()
                        : $post['img'][] = $pathToPostDir . $file->getFilename();
                }
                $iterator->next();
            }

            $path .= '/text.post';
            if (is_file($path)) {
                $post['text'] = file_get_contents($path);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }

        return $post;
    }

    /**
     * Get article by id
     *
     * @param int $id Article id
     * @return \stdClass
     */
    protected function getPostFromDb($id)
    {
        return DB::table('posts')
            ->join('category', 'category.id', '=', 'posts.category')
            ->join('category_parent', 'category_parent.id', '=', 'category.main')
            ->select(
                'posts.*',
                'category.name as categoryName',
                'category_parent.name as categoryParent'
            )
            ->where('posts.id', $id)
            ->first();
    }

    /**
     * Get comments for article
     *
     * @param \StdClass $fromDb
     * @return array
     */
    protected function getComments($fromDb)
    {
        if (! is_null($fromDb->comments)) {
            $commentsIndexes = unserialize($fromDb->comments);

            $comments = DB::table('comments')
                ->join('users', 'users.id', '=', 'comments.id_user')
                ->select(
                    'comments.*',
                    'users.name'
                )
                ->whereIn('comments.id', $commentsIndexes)
                ->get();

            $commentsFinal = [];
            if (! empty($commentsIndexes)) {
                foreach ($commentsIndexes as $commentsIndex) {
                    foreach ($comments as $c) {
                        if ($commentsIndex == $c->id) {
                            $commentsFinal[] = $c;
                        }
                    }
                }
            }

            return $commentsFinal;
        } else {
            return [];
        }
    }

    /**
     * Increment review
     *
     * @param int $postId Article id
     * @return bool
     */
    public function incrementReview($postId)
    {
        return DB::table('posts')
            ->where('id', $postId)
            ->increment('review');
    }

    /**
     * Increment review for user
     *
     * @param int $id User id
     * @return bool
     */
    protected function incrementReviewToUser($id)
    {
        return DB::table('users')
            ->where('id', $id)
            ->increment('count_review');
    }

    /**
     * Get the same articles, look like keywords
     *
     * @param string $keywordsDb Keywords from database
     * @param int    $postId     Article id
     * @return array
     */
    public function getSamePost($keywordsDb, $postId)
    {
        $keywords  = array_reverse(explode(',', $keywordsDb));
        $samePosts = [];
        $skipId    = [$postId];

        $howMuchNeedSamePosts = config('var.countSamePosts');

        $i = $howMuchNeedSamePosts;
        if (! empty($keywords)) {
            foreach ($keywords as $kw) {
                if ($i > $howMuchNeedSamePosts) {
                    break;
                }
                if (! empty($samePosts)) {
                    foreach ($samePosts as $samePostsByKey) {
                        if (! empty($samePostsByKey)) {
                            foreach ($samePostsByKey as $sp) {
                                $skipId[] = $sp->id;
                            }
                        }
                    }
                }
                $kw = trim($kw);

                $samePosts[$kw] = DB::table('posts')
                    ->where('keywords', 'like', '%' . $kw . '%')
                    ->whereNotIn('id', $skipId)
                    ->take($i)
                    ->get();

                $i -= count($samePosts[$kw]);
            }
        }

        return $samePosts;
    }

    /**
     * Get string with keywords from database, write under comma
     *
     * @static
     * @param string $keywordsDb String with keywords from database
     * @return string String with links for each keyword
     */
    public static function getKeyWords($keywordsDb)
    {
        $i           = 0;
        $strKeywords = '';
        $keywords    = explode(',', $keywordsDb);
        if (! empty($keywords)) {
            foreach ($keywords as $key) {
                $keyGoodLook = trim(mb_strtoupper($key, 'UTF-8'));
                ($i != 0)
                    ? $coma = ', '
                    : $coma = '';
                $strKeywords .= $coma . '<a href="' . url('key/' . mb_strtolower($keyGoodLook, 'UTF-8'))
                                . '" title="' . $keyGoodLook . '" rel="nofollow">' . $keyGoodLook . '</a>';
                $i++;
            }
        }

        return $strKeywords;
    }

    /**
     * Get main image
     *
     * @static
     * @param int $id Article id
     * @return string link to main image
     */
    public static function getMainImg($id)
    {
        $link      = "/resources/posts/" . $id . "/main.jpg";
        $linkToImg = config('var.pathToRoot') . $link;
        if (! is_file(base_path() . '/' . $link)) {
            $linkToImg = config('var.pathToRoot') . "/resources/posts/main.jpg";
        }

        return $linkToImg;
    }

    /**
     * Increment likes in database for article
     *
     * @param int    $id      Article id
     * @param string $likeFor This is like for article or comment
     * @return bool
     */
    public function addLike($id, $likeFor)
    {
        if ($likeFor == 'article') {
            $this->addLikePostForUser($id);

            return DB::table('posts')
                ->where('id', $id)
                ->increment('likes');
        } elseif ($likeFor == 'comment') {
            return DB::table('comments')
                ->where('id', $id)
                ->increment('likes');
        } else {
            return false;
        }
    }

    /**
     * Populate property $this->posts by articles which have keywords like $key
     *
     * @param string $key  keyword
     * @param string $page the word from config.var.wordInPagination concat number of page
     */
    public function getPostsByKey($key, $page)
    {
        $page = $this->cleanPagePaginate($page);

        $posts = DB::table('posts')
            ->where('keywords', 'like', '%' . $key . '%')
            ->orderBy('date', 'desc')
            ->get();

        $this->posts = new Paginator($posts, config('var.postsOnPage'), url('key/' . $key . '/'), $page);

        $this->getCountComment();
    }

    /**
     * Get search result
     *
     * @param string $request Request from user
     * @param string $page    The word from config.var.wordInPagination concat number of page
     */
    public function getSearchResult($request, $page)
    {
        $page = $this->cleanPagePaginate($page);

        $posts = DB::table('posts')
            ->where('header', 'like', '%' . $request . '%')
            ->orWhere('list', 'like', '%' . $request . '%')
            ->orderBy('date', 'desc')
            ->get();

        $this->posts = new Paginator($posts, config('var.postsOnPage'), url('search/'), $page);

        $this->getCountComment();
    }

    /**
     * Get number of current pagination page
     * without the word from config('var.wordInPagination')
     *
     * @param string $page The word from config.var.wordInPagination concat number of page
     * @return string Number of current pagination page
     */
    protected function cleanPagePaginate($page)
    {
        if ($page !== 1 && strpos($page, config('var.wordInPagination')) === false) {
            abort(404);
        }

        return strtr($page, [config('var.wordInPagination') => '']);
    }

    /**
     * Add article to user profile
     *
     * @param int $id Article id
     * @return bool
     */
    protected function addLikePostForUser($id)
    {
        if (Auth::check()) {
            $userId    = Auth::user()->id;
            $likePosts = DB::table('users')
                ->where('id', $userId)
                ->value('like_posts');
            if (! is_null($likePosts)) {
                $likePosts = unserialize($likePosts);
                if (! empty($likePosts)) {
                    foreach ($likePosts as $lp) {
                        if ($lp == $id) {
                            return true;
                        }
                    }
                }
                $likePosts[]  = $id;
                $likePostsNew = serialize($likePosts);
            } else {
                $likePosts[]  = $id;
                $likePostsNew = serialize($likePosts);
            }

            return DB::table('users')
                ->where('id', $userId)
                ->update(['like_posts' => $likePostsNew]);
        }

        return true;
    }

    /**
     * Get liked posts for user
     *
     * @param string $page The word from config.var.wordInPagination concat number of page
     */
    public function getLikePosts($page)
    {
        $page = $this->cleanPagePaginate($page);

        $userId = Auth::user()->id;

        $likePosts = DB::table('users')
            ->where('id', $userId)
            ->value('like_posts');

        $posts = DB::table('posts')
            ->whereIn('id', unserialize($likePosts))
            ->get();

        $this->posts = new Paginator($posts, config('var.postsOnPage'), url('account/like-posts/'), $page);

        $this->getCountComment();
    }
}
