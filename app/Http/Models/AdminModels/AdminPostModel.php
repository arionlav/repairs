<?php
namespace App\Http\Models\AdminModels;

use Mockery\CountValidator\Exception;
use DB;
use Input;

/**
 * Trait AdminPostModel provide logic for work with articles from admin panel
 *
 * @package App\Http\Models\AdminModels
 */
trait AdminPostModel
{
    /**
     * @var string Path to dir with articles
     */
    protected $pathToPostsDir = 'resources/posts/';

    /**
     * @var array Array with image indexes for preview mode
     */
    protected $usageImageNumber = [];

    /**
     * @var array Array with elements for preview mode. Simulated data
     */
    protected $currentPost = [];

    /**
     * @var array Validation rules for creating article
     */
    protected $validationRules = [
        'header'          => 200,
        'list'            => 600,
        'description'     => 1200,
        'keywords'        => 100,
        'metaKeywords'    => 400,
        'metaDescription' => 500
    ];

    /**
     * Check if it is preview or insert mode
     *
     * @param array $input
     * @return array|false
     */
    public function checkPreviewMode($input)
    {
        if (isset($input['preview'])) {
            return $this->previewPost($input);
        }

        return false;
    }

    /**
     * Start insert or update mode
     *
     * @param array $input Input values from admin
     * @return int Id for new article
     */
    public function runInsertUpdate($input)
    {
        if (isset($input['postId'])) {
            // update mode
            $this->updatePost($input, $input['postId']);
            $id = $input['postId'];
        } else {
            // insert mode
            $id = $this->insertPost($input);
        }

        return $id;
    }

    /**
     * Get all articles from database
     *
     * @return array
     */
    public function getAllPostLinks()
    {
        return DB::table('posts')
            ->select('id', 'header')
            ->get();
    }

    /**
     * Get article by id
     *
     * @param int $id Article id
     * @return \StdClass
     * @throw Exception
     */
    public function getPostById($id)
    {
        $post = DB::table('posts')
            ->where('id', $id)
            ->first();

        $path = base_path() . '/resources/posts/' . $id . '/text.post';

        if (is_file($path)) {
            $text = file_get_contents($path);
        } else {
            throw new Exception('Wrong file: ' . $path);
        }

        for ($i = 0; $i <= config('var.maxImagesOnPage'); $i++) {
            ($i === 0)
                ? $imgName = 'main'
                : $imgName = '00' . $i;

            $pattern =
                "|&lt;a class=&quot;lightbox&quot; rel=&quot;gallery&quot;.+/({$imgName}).+class=&quot;mainImg( left)?&quot;.+&quot;/&gt;&lt;/a&gt;|";

            if (preg_match($pattern, $text, $match)) {

                $alignSide = '';
                if (isset($match[2])) {
                    if ($match[2] === ' left') {
                        $alignSide = 'l';
                    } elseif ($match[2] === ' right') {
                        $alignSide = 'r';
                    }
                }
                $text = strtr($text, [$match[0] => ':::' . $i . $alignSide]);
            }
        }

        $post->text = $text;

        return $post;
    }

    /**
     * Delete selected articles
     *
     * @return true
     * @throw Exception
     */
    public function deleteSelectedPosts()
    {
        $input = Input::get();

        foreach ($input as $key => $val) {
            if ($key === '_token' or $key === 'tableOrders_length') {
                continue;
            }
            if (! $this->deletePostFromDb($key)) {
                throw new Exception('We can\'t delete post with id "' . $key . '"" from database');
            }

            $this->deleteCommentsForPost($key);

            $this->deleteFolder($key);
        }

        return true;
    }

    /**
     * Insert new article
     *
     * @param array $input Input values from admin
     * @return int Id for new article
     * @throw Exception
     */
    protected function insertPost($input)
    {
        $this->checkInputValues($input);

        if (! Input::hasFile('fileMain')) {
            throw new Exception('Main images must be loaded');
        }

        // insert in database
        $id = DB::table('posts')
            ->insertGetId([
                'header'           => $input['header'],
                'description'      => $input['description'],
                'list'             => $input['list'],
                'keywords'         => $input['keywords'],
                'meta_keywords'    => $input['metaKeywords'],
                'meta_description' => $input['metaDescription'],
                'category'         => $input['category'],
            ]);

        // create folder with name $id in $this->pathToPostsDir folder
        $path = base_path() . '/' . $this->pathToPostsDir . $id;

        if (is_dir($path)) {
            $this->deleteInsertRow($id);
            throw new Exception('Path "' . $path . '" is already exists. We can\'t create new folder.');
        }
        mkdir($path);

        $text = $this->replaceCodeImgToTag($input, 'insert', $id);

        file_put_contents($path . '/text.post', htmlspecialchars($text));

        $this->moveImages($id);

        $this->deleteTempFiles();

        return $id;
    }

    /**
     * Update article
     *
     * @param array $input Input values from admin
     * @param int   $id    Article id
     * @return true
     * @throw Exception
     */
    protected function updatePost($input, $id)
    {
        $path = base_path() . '/' . $this->pathToPostsDir . $id;

        $text = $this->replaceCodeImgToTag($input, 'insert', $id);

        if (! file_put_contents($path . '/text.post', htmlspecialchars($text))) {
            throw new Exception('We can\'t put full text in file. Check path: ' . $path);
        }

        $updateArray = [
            'header'           => $input['header'],
            'description'      => $input['description'],
            'list'             => $input['list'],
            'keywords'         => $input['keywords'],
            'meta_keywords'    => $input['metaKeywords'],
            'meta_description' => $input['metaDescription']
        ];

        if (isset($input['updateDate'])) {
            $updateArray['date'] = date('Y-m-d H:i:s');
        }

        DB::table('posts')
            ->where('id', $id)
            ->update($updateArray);

        $this->moveImages($id);

        $this->deleteTempFiles();

        return true;
    }

    /**
     * Preview for new article
     *
     * @param array $input Input values from admin
     * @return array Simulated data for preview
     */
    protected function previewPost($input)
    {
        $this->checkInputValues($input);

        (isset($input['postId']))
            ? $id = $input['postId'] // update mode
            : $id = null;            // insert mode

        $usageImageNumber = [];

        for ($i = config('var.maxImagesOnPage'); $i >= 0; $i--) {
            if (strpos($input['text'], ':::' . $i) !== false) {
                $usageImageNumber[] = $i;
            }
        }

        if (! is_null($id)) {
            if (! empty($usageImageNumber)) {
                foreach ($usageImageNumber as $imgNumber) {
                    $path = $this->pathToPostsDir . $id . '/';
                    if ($imgNumber === 0) {
                        $fileName  = 'main.jpg';
                        $imgNumber = 'main';
                    } else {
                        $fileName = '00' . $imgNumber . '.jpg';
                    }
                    $this->currentPost['img'][$imgNumber] = config('var.pathToRoot') . '/' . $path . $fileName;
                }
            }
        }

        $this->moveImages();

        $currentPost                         = $this->currentPost;
        $currentPost['text']                 = $this->replaceCodeImgToTag($input, 'preview');
        $currentPost['comments']             = [];
        $currentPost['db']                   = new \stdClass();
        $currentPost['db']->id               = 1;
        $currentPost['db']->description      = $input['description'];
        $currentPost['db']->list             = $input['list'];
        $currentPost['db']->header           = $input['header'];
        $currentPost['db']->category         = 1;
        $currentPost['db']->keywords         = $input['keywords'];
        $currentPost['db']->likes            = 100;
        $currentPost['db']->review           = 1000;
        $currentPost['db']->date             = '2015-11-28 18:21:44';
        $currentPost['db']->meta_keywords    = $input['metaKeywords'];
        $currentPost['db']->meta_description = $input['metaDescription'];
        $currentPost['db']->categoryName     = 'ExampleCategoryName';
        $currentPost['db']->categoryParent   = 'ExampleCategoryParent';

        return $currentPost;
    }

    /**
     * When we write article, we use :::{1, 2...} code for inserting images
     * Now we must replace them in html image tags with link on current image
     *
     * @param array    $input
     * @param string   $mode
     * @param null|int $id
     * @return string String with full article text
     */
    protected function replaceCodeImgToTag($input, $mode, $id = null)
    {
        $idDirToImage = '';
        if ($mode === 'insert') {
            // if $id isn't null, get link to the image from post directory
            (is_null($id) and $mode === 'insert')
                ? $idDirToImage = 'temp/'
                : $idDirToImage = $id . '/';
        }

        for ($i = config('var.maxImagesOnPage'); $i >= 0; $i--) {
            if (preg_match('|:::(' . $i . ')([l,r])?|', $input['text'], $matches)) {
                $filename    = '';
                $pathToImage = '';
                $imageTitle  = '';

                if ($i === 0) {
                    $filename   = 'main';
                    $imageTitle = $input['header'];
                } else {
                    if ($mode === 'insert') {
                        $filename   = '00' . $i;
                        $imageTitle = $input['header'] . ', фото ' . $i;
                    } elseif ($mode === 'preview') {
                        $filename   = $i;
                        $imageTitle = $input['header'] . ', фото ' . $i;
                    }
                }

                $alignSide = '';
                if (isset($matches[2])) {
                    if ($matches[2] === 'l') {
                        $alignSide = ' left';
                    } elseif ($matches[2] === 'r') {
                        $alignSide = ' right';
                    }
                }

                if ($mode === 'insert') {
                    $pathToImage = config('var.pathToRoot') . '/' . $this->pathToPostsDir
                                   . $idDirToImage . $filename . '.jpg';
                } elseif ($mode === 'preview') {
                    $pathToImage = $this->currentPost['img'][$filename];
                }

                $linkImg = '<a class="lightbox" rel="gallery" href="' . $pathToImage . '" title="'
                           . $imageTitle . '"><img class="mainImg' . $alignSide . '" src="'
                           . $pathToImage . '" alt="' . $imageTitle . '" title="' . $imageTitle . '"/></a>';

                $input['text'] = strtr($input['text'], [
                    ':::' . $i . ((isset($matches[2])) ? $matches[2] : '') => $linkImg
                ]);

                $this->usageImageNumber[] = $i;
            }
        }

        return $input['text'];
    }

    /**
     * Move input images, populate array $this->currentPost['img']
     * If param $id is null, this is preview mode
     *
     * @param null|int $id
     */
    protected function moveImages($id = null)
    {
        if (is_null($id)) {
            $dirName = 'temp/';
        } else {
            $dirName = $id;
        }
        $path     = $this->pathToPostsDir . $dirName;
        $fileName = 'main.jpg';

        if (Input::hasFile('fileMain')) {
            Input::file('fileMain')
                ->move($path, $fileName);
            if (is_null($id)) {
                $this->currentPost['img']['main'] = config('var.pathToRoot') . '/' . $path . $fileName;
            }
        }

        for ($i = 1; $i <= config('var.maxImagesOnPage'); $i++) {
            if (Input::hasFile('file' . $i)) {
                $fileName = '00' . $i . '.jpg';

                Input::file('file' . $i)
                    ->move($path, $fileName);
                if (is_null($id)) {
                    $this->currentPost['img'][$i] = config('var.pathToRoot') . '/' . $path . $fileName;
                }
            }
        }
    }

    /**
     * Check input values
     *
     * @param array $input
     * @return true
     * @throw Exception
     */
    protected function checkInputValues($input)
    {
        foreach ($input as $key => $val) {
            if (
                $key === '_token'
                or $key === 'text'
                or $key === 'preview'
                or $key === 'category'
                or $key === 'postId'
            ) {
                continue;
            }

            if ($val === '') {
                throw new Exception('Field "' . strtoupper($key) . '" can not be empty');
            }

            if (strlen($val) > $this->validationRules[$key]) {
                throw new Exception('Field "' . strtoupper($key) . '" to big. You can use max '
                                    . $this->validationRules[$key] . ' symbols. Now you have ' . strlen($val));
            }
        }

        return true;
    }

    /**
     * Clean temp directory
     *
     * @return true
     */
    protected function deleteTempFiles()
    {
        $files = glob($this->pathToPostsDir . 'temp/*');

        if (! empty($files)) {
            array_map('unlink', $files);
        }

        return true;
    }

    /**
     * Delete inserting row if after insert command we find errors
     *
     * @param int $id Article id
     * @return bool
     */
    protected function deleteInsertRow($id)
    {
        return DB::table('posts')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Delete post from database
     *
     * @param int $id Article id
     * @return bool
     */
    protected function deletePostFromDb($id)
    {
        return DB::table('posts')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Delete comments for article id
     *
     * @param int $id Article id
     * @return bool
     */
    protected function deleteCommentsForPost($id)
    {
        return DB::table('comments')
            ->where('id_post', $id)
            ->delete();
    }

    /**
     * Delete folder for article with all files
     *
     * @param int $id Article id
     * @return true
     */
    protected function deleteFolder($id)
    {
        $path  = base_path() . '/resources/posts/' . $id;
        $files = glob($path . '/*');

        if (! empty($files)) {
            array_map('unlink', $files);
        }

        if (is_dir($path)) {
            rmdir($path);
        }

        return true;
    }
}
