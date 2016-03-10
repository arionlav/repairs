<?
namespace App\Http\Controllers;

use App\Http\Models\CommentsModel;
use URL;
use Redirect;

/**
 * Class CommentsController is responsible for handling an articles comments
 *
 * @package App\Http\Controllers
 */
class CommentsController extends Controller
{
    /**
     * Set model class in $this->model variable
     */
    public function __construct()
    {
        $this->model = new CommentsModel();
    }

    /**
     * Add comment and redirect to previous page with posted comment
     *
     * @return \Illuminate\Http\Response
     */
    public function sendComment()
    {
        $validator = $this->model->checkValidation();

        if ($validator->fails()) {
            return Redirect::to(URL::previous() . '#commentsDiv')
                ->withInput()
                ->withErrors($validator);
        }

        if (! $this->model->checkFileValidation()) {
            return Redirect::to(URL::previous() . '#commentsDiv')
                ->withInput()
                ->withErrors(['file' => 'Плохой файл']);
        }

        $id = $this->model->sendComment();
        $this->model->sendMessageForUser($id);

        return Redirect::to(URL::previous() . '#comment' . $id);
    }
}
