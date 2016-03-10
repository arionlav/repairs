<?
namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Validator;
use Input;
use Auth;
use Mail;
use DB;
use Redirect;

/**
 * Class AuthController is responsible for handling user registration & login
 *
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers,
        ThrottlesLogins;

    /**
     * @var string Path for redirect after success login
     */
    protected $redirectPath = '/';

    /**
     * @var string Path to login page
     */
    protected $loginPath = 'auth/login';

    /**
     * @var string Subject for verification email
     */
    protected $subjectInVerificationMail = 'Подтверждение регистрации';

    /**
     * @var array Validation rules for registration
     */
    protected $validationRegisterRules = [
        'name'     => 'required|max:50|min:3',
        'email'    => 'required|email|unique:users,email',
        'captcha'  => 'required|captcha',
        'password' => 'required|confirmed|min:3'
    ];

    /**
     * @var array Validation rules for login
     */
    protected $validationLoginRules = [
        'email'    => 'required|exists:users',
        'password' => 'required'
    ];

    /**
     * @var array Messages for validation at registration
     */
    protected $messagesRegister = [
        'required'           => 'Поле должно быть заполнено',
        'min'                => 'Введите не менее :min символов',
        'max'                => 'Введите не более :max символов',
        'email.required'     => 'E-mail понадобится для входа на сайт',
        'email'              => 'Ведите реальный E-mail',
        'email.unique'       => 'Этот E-mail уже зарегистрирован',
        'password.confirmed' => 'Пароли не совпадают',
        'captcha.required'   => 'Пожалуйста, введите символ на картинке выше',
        'captcha.captcha'    => 'Символы введены неверно, попробуйте еще раз <br> Нажмите на картинку, чтобы обновить'
    ];

    /**
     * @var array Messages for validation at login
     */
    protected $messagesLogin = [
        'exists'   => 'Неправильный E-mail или пароль',
        'required' => 'Поля должны быть заполнены'
    ];

    /**
     * Create a new authentication controller instance
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the application registration form
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister()
    {
        $input = Input::only(
            'name',
            'email',
            'password',
            'password_confirmation',
            'captcha'
        );

        $validator = Validator::make($input, $this->validationRegisterRules, $this->messagesRegister);

        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $input['confirmation_code'] = str_random(30);

        $this->create($input);

        Mail::send('emails.verify', ['confirmation_code' => $input['confirmation_code']], function ($message) {
            $message->to(Input::get('email'), Input::get('name'))
                ->subject($this->subjectInVerificationMail);
        });

        return view('auth.registerSuccess', [
            'registerSuccess' => 1
        ]);
    }

    /**
     * Create a new user instance after a valid registration
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'confirmation_code' => $data['confirmation_code'],
        ]);
    }


    /**
     * Check confirmation code from users email with the same code in database
     *
     * @param string $confirmation_code
     * @return \Illuminate\Http\Response
     */
    public function confirmVerify($confirmation_code)
    {
        if (! $confirmation_code) {
            abort(404);
        }

        $user = User::whereConfirmationCode($confirmation_code)
            ->first();

        if (! $user) {
            abort(404);
        }

        $user->confirmed         = 1;
        $user->confirmation_code = null;
        $user->save();

        return view('auth.verify', [
            'verifyIsGood' => 1
        ]);
    }

    /**
     * Show the application login form
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        $input = Input::only(
            'email',
            'password'
        );

        $validator = Validator::make($input, $this->validationLoginRules, $this->messagesLogin);

        // check e-mail and password for required
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        // check confirm E-mail
        if (! $this->checkConfirm($input['email'])) {
            return Redirect::back()
                ->withInput()
                ->withErrors([
                    'credentials' => 'Ваш E-mail не подтвержден,<br> пожалуйста, проверьте Вашу почту,
                                    <br> возможно письмо попало в спам'
                ]);
        }

        if (! Auth::attempt($input)) {
            return Redirect::back()
                ->withInput()
                ->withErrors([
                    'credentials' => 'Неправильный E-mail или пароль'
                ]);
        }

        if (Auth::attempt($input, $request->has('remember'))) {
            if ($request->session()
                ->has('previousUrl')
            ) {
                $this->redirectPath = $request->session()
                    ->get('previousUrl');
            }

            return $this->handleUserWasAuthenticated($request, $throttles);
        }
    }

    /**
     * Get confirmed value from database
     *
     * @param string $email
     * @return bool
     */
    protected function checkConfirm($email)
    {
        return DB::table('users')
            ->where('email', $email)
            ->value('confirmed');
    }
}
