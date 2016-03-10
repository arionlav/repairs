<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Input;
use Validator;
use Redirect;

/**
 * Class PasswordController - Password Reset Controller
 *
 * @package App\Http\Controllers\Auth
 */
class PasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var string path for redirect after success login
     */
    protected $redirectPath = '/';

    /**
     * @var array Validation rules for reset password in email page
     */
    protected $validationRules = [
        'email'   => 'required|email|exists:users',
        'captcha' => 'required|captcha'
    ];

    /**
     * @var array Validation rules for reset password
     */
    protected $validationResetRules = [
        'email'    => 'required|email|exists:users',
        'captcha'  => 'required|captcha',
        'password' => 'required|confirmed|min:3'
    ];

    /**
     * @var array Messages for validation at resetting password
     */
    protected $messages = [
        'email.required'   => 'Поле должно быть заполнено',
        'email.exists'     => 'Пользователя с таким E-mail у нас нет',
        'email'            => 'Ведите реальный E-mail',
        'captcha.required' => 'Пожалуйста, введите символ на картинке выше',
        'captcha.captcha'  => 'Символы введены неверно, попробуйте еще раз <br> Нажмите на картинку, чтобы обновить'
    ];

    /**
     * @var array Messages for validation at email page
     */
    protected $messagesReset = [
        'required'           => 'Поле должно быть заполнено',
        'min'                => 'Введите не менее :min символов',
        'email'              => 'Ведите реальный E-mail',
        'email.exists'       => 'Пользователя с таким E-mail у нас нет',
        'password.confirmed' => 'Пароли не совпадают',
        'captcha.required'   => 'Пожалуйста, введите символ на картинке выше',
        'captcha.captcha'    => 'Символы введены неверно, попробуйте еще раз <br> Нажмите на картинку, чтобы обновить'
    ];

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return view('auth.password');
    }

    /**
     * Send a reset link to the given user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $input = Input::only(
            'email',
            'captcha'
        );

        $validator = Validator::make($input, $this->validationRules, $this->messages);

        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                // was redirect bach with 'status', trans($response)
                return redirect()->to('password/reset-success');

            case Password::INVALID_USER:
                return redirect()
                    ->back()
                    ->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Восстановление пароля на remont-mega.ru';
    }

    /**
     * Where user right insert your email and captcha
     *
     * @return \Illuminate\Http\Response
     */
    public function getResetSuccess()
    {
        return view('auth.resetSuccess', ['resettingSuccess' => 1]);
    }

    /**
     * Display the password reset view for the given token
     *
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token) or is_null($this->checkTokenIfIs($token))) {
            abort(404);
        }

        return view('auth.reset')->with('token', $token);
    }

    /**
     * Reset the given user's password
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $input = Input::only(
            'token',
            'email',
            'password',
            'password_confirmation',
            'captcha'
        );

        $validator = Validator::make($input, $this->validationResetRules, $this->messagesReset);

        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if ($request->session()
            ->has('previousUrl')
        ) {
            $this->redirectPath = $request->session()
                ->get('previousUrl');
        }

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath)->with('status', trans($response));

            default:
                return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }
}
