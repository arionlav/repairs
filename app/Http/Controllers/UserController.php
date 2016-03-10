<?php
namespace App\Http\Controllers;

use App\Http\Models\UserModel;
use App\Http\Models\PostsModel;
use Auth;
use Input;
use Redirect;
use URL;

/**
 * Class UserController is responsible for handling user account page
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Set model class in $this->model variable
     */
    public function __construct()
    {
        if (! Auth::check()) {
            abort(404);
        }
        $this->model = new UserModel();
    }

    /**
     * Index page for user account
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $user = Auth::user();

        return view('user.account', [
            'user' => $user
        ]);
    }

    /**
     * Modify page for user account
     *
     * @return \Illuminate\Http\Response
     */
    public function getModify()
    {
        $user = Auth::user();

        return view('user.accountModify', [
            'user' => $user
        ]);
    }

    /**
     * Update account
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdate()
    {
        $validator = $this->model->validateInputs();

        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        $user = Auth::user();

        if ($this->model->updateImage($user) === false) {
            return Redirect::to(URL::previous())
                ->withInput()
                ->withErrors(['file' => 'Плохой файл']);
        }

        $this->model->updateTextField($user);

        return redirect()->to('account');
    }

    /**
     * Page with private messages
     *
     * @param int $fromUser
     * @return \Illuminate\Http\Response
     */
    public function getMessages($fromUser = 0)
    {
        $user = Auth::user();

        $messages = $this->model->getMessages($user->id);

        if ($fromUser === 0) {
            $fromUser = $this->model->getLastActiveUser($messages, $user->id);
        }

        $lastMessages = $this->model->getLastMessages($messages, $fromUser);
        $users        = $this->model->getUsersByIds($messages, $user->id);

        $this->model->allMessagesIsWrite($fromUser, $user->id);
        $this->model->allMessagesAreWritten($user->id);

        return view('user.accountMessages', [
            'user'         => $user,
            'users'        => $users,
            'messages'     => $messages,
            'lastMessages' => $lastMessages,
            'fromUser'     => $fromUser,
            'usersOrder'   => $this->model->usersOrder
        ]);
    }

    /**
     * Send message for user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMessageSend()
    {
        $this->model->sendMessage();

        $this->model->sendMessageToEmail();

        return redirect()->to('account/messages/' . Input::get('forUser'));
    }

    /**
     * Page for sending message to user
     *
     * @param int $id User id
     * @return \Illuminate\Http\Response
     */
    public function getMessagesForUser($id)
    {
        $user = $this->model->getuser($id);

        if (Auth::user()->id == $user->id) {
            abort(404);
        }

        return view('user.messageForUser', ['user' => $user]);
    }

    /**
     * Send message to user and redirect to private message page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMessagesForUser()
    {
        $this->model->sendMessage();

        return redirect()->to('account/messages/' . Input::get('forUser'));
    }

    /**
     * Get page, where user clicked on the like button
     *
     * @param int $page The number of pagination page
     * @return \Illuminate\Http\Response
     */
    public function getLikePosts($page = 1)
    {
        $model = new PostsModel();
        $model->getLikePosts($page);

        return view('index', [
            'posts' => $model->posts
        ]);
    }
}
