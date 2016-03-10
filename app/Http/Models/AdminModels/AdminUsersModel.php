<?php
namespace App\Http\Models\AdminModels;

use Mockery\CountValidator\Exception;
use DB;
use Input;

/**
 * Trait AdminUsersModel provide logic for work with users from admin panel
 *
 * @package App\Http\Models\AdminModels
 */
trait AdminUsersModel
{
    /**
     * Get all users
     *
     * @return array
     */
    public function getAllUsers()
    {
        return DB::table('users')
            ->get();
    }

    /**
     * Get the number of comments for users
     *
     * @return array Array with 'user id' => 'count comments' pairs
     */
    public function getCountComments()
    {
        $commentsFromDb = DB::table('comments')
            ->select('id_user')
            ->get();

        $comments = [];

        if (! empty($commentsFromDb)) {
            foreach ($commentsFromDb as $c) {
                if (! isset($comments[$c->id_user])) {
                    $comments[$c->id_user] = 1;
                } else {
                    $comments[$c->id_user]++;
                }
            }
        }

        return $comments;
    }

    /**
     * Get user by id
     *
     * @param int $id User id
     * @return \StdClass
     */
    public function getUserById($id)
    {
        return DB::table('users')
            ->where('id', $id)
            ->first();
    }

    /**
     * Update user
     *
     * @param array $input Input values from admin
     * @return bool
     * @throw Exception
     */
    public function updateUser($input)
    {
        if (isset($input['confirmed'])) {
            $result = DB::table('users')
                ->where('id', $input['id'])
                ->update([
                    'confirmation_code' => null,
                    'confirmed'         => 1
                ]);
            if (! $result) {
                throw new Exception('Error confirmed');
            }
        }

        if (isset($input['avatar'])) {
            $pathToAvatars = base_path() . '/resources/users/' . $input['id'] . '.jpg';
            if (is_file($pathToAvatars)) {
                unlink($pathToAvatars);
            }
        }

        return DB::table('users')
            ->where('id', $input['id'])
            ->update([
                'name'  => $input['name'],
                'email' => $input['email'],
                'role'  => $input['role']
            ]);
    }

    /**
     * Delete avatar
     *
     * @param int $id Users id
     * @return true
     */
    public function deleteAvatar($id)
    {
        $pathToAvatars = base_path() . '/resources/users/' . $id . '.jpg';
        if (is_file($pathToAvatars)) {
            unlink($pathToAvatars);
        }

        return true;
    }

    /**
     * Delete user comments
     *
     * @see App\Http\Models\AdminModels\AdminCommentsModel::deleteComment($id)
     * @param int $id Users id
     * @return true
     */
    public function deleteUsersComments($id)
    {
        $comments = DB::table('comments')
            ->where('id_user', $id)
            ->select('id')
            ->get();

        if (! empty($comments)) {
            foreach ($comments as $c) {
                $this->deleteComment($c->id);
            }
        }

        return true;
    }

    /**
     * Delete user from database
     *
     * @param int $id Users id
     * @return bool
     */
    public function deleteUserFromDb($id)
    {
        return DB::table('users')
            ->where('id', $id)
            ->delete();
    }
}
