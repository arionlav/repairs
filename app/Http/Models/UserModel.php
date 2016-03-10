<?php
namespace App\Http\Models;

use DB;
use Mockery\CountValidator\Exception;
use URL;
use Input;
use Redirect;
use Validator;
use Auth;
use Mail;

/**
 * Class PostsModel provide logic for user account page
 *
 * @package App\Http\Models
 */
class UserModel
{
    /**
     * @var array Validation rules for account form
     */
    protected $validationRules = [
        'file'     => 'mimes:jpeg,jpg,bmp,png|max:500',
        'name'     => 'required|max:50',
        'city'     => 'max:40',
        'interest' => 'max:450',
        'aboutMe'  => 'max:700'
    ];

    /**
     * @var array Messages for validation at updating account
     */
    protected $messages = [
        'required'   => 'Поле должно быть заполненным',
        'name.max'   => 'Слишком длинное имя, можно максимум :max символов',
        'max'        => 'Слишком много символов, можно максимум :max',
        'file.max'   => 'Слишком большой файл, можно максимум :max Kb',
        'file.mimes' => 'Можно загрузить только каринки jpeg, jpg, bmp, png'
    ];

    /**
     * @var array Order for displaying list of users in message's page
     */
    public $usersOrder = [];

    /**
     * Get validate input values
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validateInputs()
    {
        $input = Input::all();

        return Validator::make($input, $this->validationRules, $this->messages);
    }

    /**
     * Logic for handling avatar image
     *
     * @param \App\User $user
     * @return mixed
     */
    public function updateImage($user)
    {
        if (is_null(Input::get('deleteAvatar'))) {
            if (Input::hasFile('file')) {
                if (Input::file('file')
                    ->isValid()
                ) {
                    $path     = 'resources/users';
                    $fileName = $user->id . '.jpg';

                    Input::file('file')
                        ->move($path, $fileName);
                } else {
                    return false;
                }
            }
        } else {
            unlink('resources/users/' . $user->id . '.jpg');
        }

        return true;
    }

    /**
     * Update text field
     *
     * @param \App\User $user
     * @return bool
     */
    public function updateTextField($user)
    {
        $input = Input::get();

        $updateArray = [
            'name'     => $input['name'],
            'city'     => $input['city'],
            'pol'      => $input['pol'],
            'interest' => $input['interest'],
            'about_me' => $input['aboutMe'],
            'born'     => $this->createBornDate($input)
        ];

        (isset($input['acceptPrivateMail']))
            ? $updateArray['accept_private_mail'] = 1
            : $updateArray['accept_private_mail'] = 0;

        (isset($input['acceptCommentsMail']))
            ? $updateArray['accept_comments_mail'] = 1
            : $updateArray['accept_comments_mail'] = 0;

        (isset($input['acceptRssMail']))
            ? $updateArray['accept_rss_mail'] = 1
            : $updateArray['accept_rss_mail'] = 0;

        return DB::table('users')
            ->where('id', $user->id)
            ->update($updateArray);
    }

    /**
     * Get user by id
     *
     * @param int $id User id
     * @return \StdClass
     */
    public function getuser($id)
    {
        return DB::table('users')
            ->where('id', $id)
            ->first();
    }

    /**
     * Create date for inserting in database
     *
     * @param array $input
     * @return string
     */
    protected function createBornDate($input)
    {
        if (strpos($input['bornYear'], '--') !== false
            || strpos($input['bornMonth'], '--') !== false
            || strpos($input['bornDay'], '--') !== false
        ) {
            return '';
        }

        return $input['bornYear'] . '-' . $input['bornMonth'] . '-' . $input['bornDay'];
    }

    /**
     * Get all messages for user
     *
     * @param int $id User id
     * @return array
     */
    public function getMessages($id)
    {
        return DB::table('messages')
            ->where('for_user', $id)
            ->orWhere('from_user', $id)
            ->get();
    }

    /**
     * Get all user ids who sent messages for me
     *
     * @param array $messages Array with all messages for user
     * @param int   $userId   User id
     * @return array
     */
    protected function messagesByUsers($messages, $userId)
    {
        $result = [];
        foreach ($messages as $message) {
            if (isset($result[$message->from_user])) {
                $result[$message->from_user]++;
            } else {
                $result[$message->from_user] = 1;
            }
            if ($message->from_user != $userId) {
                $this->usersOrder[] = $message->from_user;
            }
        }

        $this->usersOrder = array_unique(array_reverse($this->usersOrder));

        return $result;
    }

    /**
     * Get users by ids
     *
     * @param array $messages Array with all messages for user
     * @param int   $userId   User id
     * @return array
     */
    public function getUsersByIds($messages, $userId)
    {
        $messagesByUser = $this->messagesByUsers($messages, $userId);

        if (array_key_exists($userId, $messagesByUser)) {
            unset($messagesByUser[$userId]);
        }

        return DB::table('users')
            ->whereIn('id', array_keys($messagesByUser))
            ->get();
    }

    /**
     * Get messages for selected user
     *
     * @param array $messages Array with all messages for user
     * @param int   $fromUser User id which we talking now
     * @return array
     */
    public function getLastMessages($messages, $fromUser)
    {
        $lastMessages = [];

        foreach ($messages as $m) {
            if ($m->from_user == $fromUser or $m->for_user == $fromUser) {
                $lastMessages[] = $m;
            }
        }

        return $lastMessages;
    }

    /**
     * Get id for last user, who was send message for me
     *
     * @param array $messages Array with all messages for user
     * @param int   $userId   User id
     * @return int
     */
    public function getLastActiveUser($messages, $userId)
    {
        $fromUser = $messages[count($messages) - 1]->from_user;

        if ($userId == $fromUser) {
            unset($messages[count($messages) - 1]);

            return $this->getLastActiveUser($messages, $userId);
        }

        return $fromUser;
    }

    /**
     * Insert message to database
     *
     * @return true
     * @throw Exception
     */
    public function sendMessage()
    {
        $input = Input::get();

        if (! DB::table('messages')
            ->insert([
                'text'      => $input['text'],
                'from_user' => $input['fromUser'],
                'for_user'  => $input['forUser']
            ])
        ) {
            throw new Exception('Insert message wrong.');
        }

        if (! DB::table('users')
            ->where('id', $input['forUser'])
            ->increment('count_new_messages')
        ) {
            throw new Exception('Increment count_new_messages wrong for user ' . $input['forUser']);
        }

        return true;
    }

    /**
     * Mark all messages as Read for me
     *
     * @param int $fromUser Message from user id
     * @param int $userId   Message for user id
     * @return bool
     */
    public function allMessagesIsWrite($fromUser, $userId)
    {
        $where['for_user'] = $userId;

        if ($fromUser) {
            $where['from_user'] = $fromUser;
        }

        return DB::table('messages')
            ->where($where)
            ->update(['be_write' => 1]);
    }

    /**
     * All messages are read for user
     *
     * @param int $userId Message for user id
     * @return bool
     */
    public function allMessagesAreWritten($userId)
    {
        return DB::table('users')
            ->where(['id' => $userId])
            ->update(['count_new_messages' => 0]);
    }

    /**
     * Send email for user if accept_private_mail is true for him
     *
     * @return true
     */
    public function sendMessageToEmail()
    {
        $user = $this->getuser(Input::get('forUser'));

        if ($user->accept_private_mail) {
            Mail::send('emails.sendMessage', [], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Вам пришло личное сообщение на сайте remont-mega.ru');
            });
        }

        return true;
    }
}
