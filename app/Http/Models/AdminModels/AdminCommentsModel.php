<?php
namespace App\Http\Models\AdminModels;

use Mockery\CountValidator\Exception;
use DB;

/**
 * Trait AdminCommentsModel provide logic for work with comments from admin panel
 *
 * @package App\Http\Models\AdminModels
 */
trait AdminCommentsModel
{
    /**
     * @var array Array with answers on current comment
     */
    public $answers = [];

    /**
     * Get all categories
     *
     * @return array
     */
    public function getAllCategories()
    {
        return DB::table('category')
            ->join('category_parent', 'category.main', '=', 'category_parent.id')
            ->select('category.*', 'category_parent.name as parentName')
            ->get();
    }

    /**
     * Get all comments
     *
     * @return array
     */
    public function getAllComments()
    {
        return DB::table('comments')
            ->join('posts', 'comments.id_post', '=', 'posts.id')
            ->join('users', 'comments.id_user', '=', 'users.id')
            ->select(
                'comments.*',
                'posts.id as postId',
                'posts.header as postHeader',
                'users.name as userName'
            )
            ->get();
    }

    /**
     * Get selected comment by id
     *
     * @param int $id Comment id
     * @return \StdClass
     */
    public function getCommentById($id)
    {
        return DB::table('comments')
            ->where('id', $id)
            ->first();
    }

    /**
     * Update comment
     *
     * @param array $input
     * @return bool
     */
    public function updateComment($input)
    {
        if (isset($input['imgDel'])) {
            $img = null;

            if (isset($input['img'])) {
                (config('var.pathToRoot') !== '')
                    ? $linkToImg = strtr($input['img'], [config('var.pathToRoot') => ''])
                    : $linkToImg = $input['img'];

                $path = base_path() . $linkToImg;

                if (is_file($path)) {
                    unlink($path);
                }
            }
        } else {
            (isset($input['img'])) ? $img = $input['img'] : $img = null;
        }

        return DB::table('comments')
            ->where('id', $input['id'])
            ->update([
                'text'  => $input['text'],
                'likes' => $input['likes'],
                'img'   => $img
            ]);
    }

    /**
     * Delete comment by id
     *
     * @param int $id Comment id
     * @return true
     * @throw Exception
     */
    public function deleteComment($id)
    {
        $comments = $this->getAllComments();

        $comment = $this->getCommentById($id);

        $this->answers = [];
        $this->getAnswers($comments, $id);

        $this->answers[] = $id * 1;

        $postComments = $this->updatePostCommentsArray($comment->id_post);

        if (! $this->updatePostComment($postComments, $comment->id_post)) {
            throw new Exception('Update post comment field fail');
        }

        if (! $this->deleteCommentImages()) {
            throw new Exception('Update post comment field are fail');
        }

        if (! $this->deleteCommentFormDb()) {
            throw new Exception('We can\'t delete comment with id ' . $id . ' and his answers. Check Database.');
        }

        return true;
    }

    /**
     * Recursive function. Get all child answers on comment
     * Populate $this->answers array
     *
     * @param array $comments All comments from database
     * @param int   $id       Comment id
     */
    protected function getAnswers($comments, $id)
    {
        if (! empty($comments)) {
            foreach ($comments as $comment) {
                if ($comment->answer_to == $id) {
                    $this->answers[] = $comment->id;

                    $this->getAnswers($comments, $comment->id);
                }
            }
        }
    }

    /**
     * Get comment by id
     *
     * @param int $id Comment id
     * @return string Serialize string with all comments for current article
     */
    protected function getPostCommentsById($id)
    {
        return DB::table('posts')
            ->where('id', $id)
            ->value('comments');
    }

    /**
     * Delete comments from database where id is in $this->answers
     *
     * @return bool
     */
    protected function deleteCommentFormDb()
    {
        return DB::table('comments')
            ->whereIn('id', $this->answers)
            ->delete();
    }

    /**
     * Delete comments from array
     * with all comments for current article where id is in $this->answers
     *
     * @param int $idPost
     * @return array
     * @throw Exception
     */
    protected function updatePostCommentsArray($idPost)
    {
        $postComments = unserialize($this->getPostCommentsById($idPost));

        if (! empty($this->answers) && ! empty($postComments)) {
            foreach ($this->answers as $answer) {
                foreach ($postComments as $key => $pc) {
                    if ($pc == $answer) {
                        unset($postComments[$key]);
                    }
                }
            }
        } else {
            throw new Exception('Array $this->answers or $postComments are empty. This is error');
        }

        return $postComments;
    }

    /**
     * Delete images, if it is, for comments, where id is in $this->answers
     *
     * @return true
     */
    protected function deleteCommentImages()
    {
        $commentsForCurrentPost = $this->getCommentsByIdArray($this->answers);

        if (! empty($commentsForCurrentPost)) {
            foreach ($commentsForCurrentPost as $commentForCurrentPost) {
                if (! is_null($commentForCurrentPost->img)) {
                    (config('var.pathToRoot') !== '')
                        ? $linkToImg = strtr($commentForCurrentPost->img, [config('var.pathToRoot') => ''])
                        : $linkToImg = $commentForCurrentPost->img;

                    $path = base_path() . $linkToImg;

                    if (is_file($path)) {
                        unlink($path);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Update posts.comments field in database with new order comments (after deleting)
     *
     * @param array $postComments
     * @param int   $idPost
     * @return bool
     */
    protected function updatePostComment($postComments, $idPost)
    {
        (empty($postComments))
            ? $commentsOrder = null
            : $commentsOrder = serialize($postComments);

        return DB::table('posts')
            ->where('id', $idPost)
            ->update(['comments' => $commentsOrder]);
    }

    /**
     * Get comments where id is in param $answers
     *
     * @param array $answers
     * @return array
     */
    protected function getCommentsByIdArray($answers)
    {
        return DB::table('comments')
            ->whereIn('id', $answers)
            ->get();
    }
}
