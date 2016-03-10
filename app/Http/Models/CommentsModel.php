<?
namespace App\Http\Models;

use DB;
use URL;
use Input;
use Validator;
use Mail;

/**
 * Class CommentsModel provide logic for insert comments
 *
 * @package App\Http\Models
 */
class CommentsModel
{
    /**
     * @var array Validation rules for comment form
     */
    protected $validationRules = [
        'file' => 'mimes:jpeg,jpg,bmp,png|max:2000',
        'text' => 'required|max:1000'
    ];

    /**
     * @var array Messages for validation at sending comment
     */
    protected $messages = [
        'required'   => 'Нельзя отправить пустой комментарий',
        'text.max'   => 'Слишком длинный комментарий, можно максимум :max символов',
        'file.max'   => 'Слишком большой файл, можно максимум :max Kb',
        'file.mimes' => 'Можно отправлять только каринки jpeg, jpg, bmp, png'
    ];

    /**
     * @var string Path to upload images
     */
    protected $pathToUploadImages = 'resources/comment_img';

    /**
     * @var array Array with input values
     */
    protected $input = [];

    /**
     * @var array Array with comments order from database
     */
    protected $commentsOrder = [];

    /**
     * @var array Slice of array with comments ids after the comment, to witch we answer
     */
    protected $commentsOrderSlice = [];

    /**
     * @var int Index in array, where we want to insert new comment
     */
    protected $idWhereInsert;

    /**
     * @var int Id for comment, after which must be insert new comment
     */
    protected $insertAfterCommentId;

    /**
     * @var string Serialized string with array comments order from database
     */
    protected $serializeCommentArray;

    /**
     * @var int Id for new comment
     */
    protected $idNewComment;

    /**
     * @var int Index of parent comment
     */
    protected $indexParent;

    /**
     * @var array Array with comments from database, which follow at the parent comment
     */
    protected $commentsFromDbSlice = [];

    /**
     * @var int The number of spaces for parent comment
     */
    protected $gapParent;

    /**
     * @var array Array with 'id' => 'gap' pairs for comments, which follow at the parent comment
     */
    protected $idGapPairsComments = [];

    /**
     * Check input values
     *
     * @see $this->validationRules Validation rules
     * @see $this->messages Error messages
     * @return \Illuminate\Validation\Validator
     */
    public function checkValidation()
    {
        $input = Input::all();

        $validator = Validator::make($input, $this->validationRules, $this->messages);

        return $validator;
    }

    /**
     * Check input file, if it was uploaded
     *
     * @return bool
     */
    public function checkFileValidation()
    {
        if (Input::hasFile('file')) {
            if (! Input::file('file')
                ->isValid()
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Logic for sending comment on the site
     *
     * @return int Id for new comment
     */
    public function sendComment()
    {
        $this->input = Input::get();

        $this->serializeCommentArray = $this->getSerializeCommentArray();

        if (! is_null($this->serializeCommentArray)) {
            $this->addCommentToExisting();
        } else {
            $this->firstComment();
        }

        $this->idNewComment = $this->insertComment();

        $this->changeCommentsOrderArray();

        $this->updatePostsTable();

        return $this->idNewComment;
    }

    /**
     * Logic for sending second+ comment
     * Check if it is an answer on some comment or simple second+ comment
     */
    protected function addCommentToExisting()
    {
        $this->commentsOrder = unserialize($this->serializeCommentArray);
        if ($this->input['answerTo']) {
            $this->answerOnSomeComment();
        } else {
            $this->simpleNotFirstComment();
        }
    }

    /**
     * Logic for answer on some comment
     * Determine:
     * - count spaces from left side - $this->input['gap'],
     * - position in comment list - $this->idWhereInsert
     * - link to image, if it is - $this->input['img']
     */
    protected function answerOnSomeComment()
    {
        $this->indexParent = $this->getIdParentComment();

        // get part of $this->commentsOrder array from index of parent element and down
        // All previous comments we does'n need to inspect
        $this->commentsOrderSlice = array_slice($this->commentsOrder, $this->indexParent);

        $this->commentsFromDbSlice = $this->commentsFromDbSlice();

        $this->gapParent = $this->getGapParent();

        $this->input['img'] = $this->commentImagesHandler();

        $this->input['gap'] = $this->gapParent + 1;

        $this->idGapPairsComments = $this->createIdGapPairsCommentArray();

        // get position to insert new comment
        $this->insertAfterCommentId = null;

        if (! empty($this->idGapPairsComments)) {
            $this->insertAfterCommentId = $this->getInsertAfterCommentId();
        }

        $this->idWhereInsert = $this->getIdWhereInsertComment();
    }

    /**
     * Logic for simple second+ comment
     * Insert comment to the end of comments list (set $this->insertAfterCommentId to 'null')
     * Count spaces = 0, because it is not answer to some comment (set $this->input['gap'] to '0')
     */
    protected function simpleNotFirstComment()
    {
        $this->input['img']         = $this->commentImagesHandler();
        $this->input['gap']         = 0;
        $this->insertAfterCommentId = null;
    }

    /**
     * Logic for first comment to the article
     * Insert comment to the empty array with comments order (set $this->insertAfterCommentId to 'null')
     * Count spaces = 0, because it is not answer to some comment (set $this->input['gap'] to '0')
     */
    protected function firstComment()
    {
        $this->input['img']         = $this->commentImagesHandler();
        $this->input['gap']         = 0;
        $this->insertAfterCommentId = null;
        $this->commentsOrder        = [];
    }

    /**
     * Insert new comment id in array $this->commentsOrder
     * with comments order for inserting in database
     */
    protected function changeCommentsOrderArray()
    {
        if (is_null($this->insertAfterCommentId)) {
            array_push($this->commentsOrder, $this->idNewComment);
        } else {
            array_splice($this->commentsOrder, $this->idWhereInsert, 0, $this->idNewComment);
        }
    }

    /**
     * Insert comment in database
     *
     * @return int Id for new comment
     */
    protected function insertComment()
    {
        return DB::table('comments')
            ->insertGetId([
                'text'      => htmlspecialchars($this->input['text']),
                'id_post'   => $this->input['idPost'],
                'id_user'   => $this->input['idUser'],
                'answer_to' => $this->input['answerTo'],
                'img'       => $this->input['img'],
                'gap'       => $this->input['gap']
            ]);
    }

    /**
     * Update table 'posts' with new comments order
     *
     * @return bool
     */
    protected function updatePostsTable()
    {
        return DB::table('posts')
            ->where('id', $this->input['idPost'])
            ->update([
                'comments' => serialize($this->commentsOrder)
            ]);
    }

    /**
     * Get array with comments from database where id in $this->commentsOrderSlice
     *
     * @return array
     */
    protected function commentsFromDbSlice()
    {
        $commentsFromDbSlice = DB::table('comments')
            ->select('id', 'gap')
            ->whereIn('id', $this->commentsOrderSlice)
            ->get();

        return $commentsFromDbSlice;
    }

    /**
     * Get parent comment index in $this->commentsOrder array
     * If index does not find, comment will be insert as last comment
     *
     * @return int with id
     */
    protected function getIdParentComment()
    {
        $indexParent = count($this->commentsOrder) - 1;
        if (! empty($this->commentsOrder)) {
            $commentsOrder = array_values($this->commentsOrder);
            foreach ($commentsOrder as $key => $val) {
                if ($val == $this->input['answerTo']) {
                    $indexParent = $key;
                }
            }
        }

        return $indexParent;
    }

    /**
     * Get string with serialized comments order array for current post
     *
     * @return string|null Null - if comments is the first
     */
    protected function getSerializeCommentArray()
    {
        $serializeCommentArray = DB::table('posts')
            ->where('id', $this->input['idPost'])
            ->value('comments');

        return $serializeCommentArray;
    }

    /**
     * Get spaces for parent comment. By default set '0'
     *
     * @return int The number of spaces for parent comment
     */
    protected function getGapParent()
    {
        $gapParent = 0;
        if (! empty($this->commentsFromDbSlice)) {
            foreach ($this->commentsFromDbSlice as $val) {
                if ($val->id == $this->input['answerTo']) {
                    $gapParent = $val->gap;
                }
            }
        }

        return $gapParent;
    }

    /**
     * Create array with 'id post' => 'spaces for this post' pairs
     * in which need check the number of spaces in comments below parent comment for right order
     *
     * @return array
     */
    protected function createIdGapPairsCommentArray()
    {
        $idGapPairsComments = [];
        if (! empty($this->commentsFromDbSlice)) {
            foreach ($this->commentsFromDbSlice as $commentDb) {
                $idGapPairsComments[$commentDb->id] = $commentDb->gap;
            }
        }

        return $idGapPairsComments;
    }

    /**
     * Get comment id, before (or after, if we need insert the last comment) which must be insert new comment
     * If return value is 'null', comment will be insert to the last
     *
     * @return null|int
     */
    protected function getInsertAfterCommentId()
    {
        $insertAfterCommentId = null;

        $i = 0;
        if (! empty($this->commentsOrderSlice)) {
            foreach ($this->commentsOrderSlice as $key => $val) {
                $i++;
                if ($key == 0) {
                    continue;
                }
                if ($this->idGapPairsComments[$val] < $this->input['gap']) {
                    $insertAfterCommentId = $val;
                    break;
                }
                $insertAfterCommentId = $val;
            }
        }

        // if it is the last comment, may be we need insert comment after the last comment
        // it will be, where number of spaces in last comment === our spaces for new comment
        if (count($this->commentsOrderSlice) == $i) {
            if (! is_null($insertAfterCommentId)) {
                if ($this->idGapPairsComments[$insertAfterCommentId] == $this->input['gap']) {
                    $insertAfterCommentId = null;
                }
            }
        }

        if (
            $insertAfterCommentId == $this->commentsOrderSlice[count($this->commentsOrderSlice) - 1]
            and count($this->commentsOrderSlice) < 2
        ) {
            $insertAfterCommentId = null;
        }

        return $insertAfterCommentId;
    }

    /**
     * Get index of array element, where we must insert our comment
     *
     * @return int with id
     */
    protected function getIdWhereInsertComment()
    {
        $idWhereInsert = 0;
        if (! is_null($this->insertAfterCommentId)) {
            foreach ($this->commentsOrder as $key => $val) {
                if ($val == $this->insertAfterCommentId) {
                    $idWhereInsert = $key;
                }
            }
        }

        return $idWhereInsert;
    }

    /**
     * Move uploaded file in the directory
     * If image has not been uploaded, return 'null'
     *
     * @return null|string with path to image file
     */
    protected function commentImagesHandler()
    {
        $img = null;
        if (Input::hasFile('file')) {
            $this->pathToUploadImages = 'resources/comment_img';

            $extension = Input::file('file')
                ->getClientOriginalExtension();
            $fileName  = $this->input['idPost'] . '--' . $this->input['idUser'] . '--'
                         . date('Y-m-d--H-i') . '.' . $extension;

            if (is_dir(base_path() . '/' . $this->pathToUploadImages)) {
                Input::file('file')
                    ->move($this->pathToUploadImages, $fileName);
            } else {
                return $img;
            }

            $img = config('var.pathToRoot') . '/' . $this->pathToUploadImages . '/' . $fileName;
        }

        return $img;
    }

    /**
     * Send message for user, if it is answer on some comment
     *
     * @param int $id for new comment
     */
    public function sendMessageForUser($id)
    {
        $input = Input::get();

        if ($input['answerTo']) {
            $comment = DB::table('comments')
                ->where('id', $input['answerTo'])
                ->first();

            $user = DB::table('users')
                ->where('id', $comment->id_user)
                ->first();

            if ($user->accept_comments_mail) {
                Mail::send('emails.sendComments', ['id' => $id, 'idPost' => $input['idPost']],
                    function ($message) use ($user) {
                        $message->to($user->email, $user->name)
                            ->subject('На Ваш комментарий ответили на сайте remont-mega.ru');
                    });
            }
        }
    }
}
