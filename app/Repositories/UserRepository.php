<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserRepository
{
    /**
     * Create new user
     *
     * @param array $user
     * @return object|bool
     */
    public function create(array $user)
    {
        try {

            return User::create($user);

        } catch (\PDOException $e) {
            Log::channel('api_database_exceptions')
                ->emergency(
                    'Occurrence point => User Repository (create method) ***** ' .
                    $e->getMessage()
                );

            return false;
        }
    }

    /**
     * Get all user's urls
     *
     * @param int $userID
     * @return object|bool
     */
    public function getAllUsers()
    {
        try {
            
            $user = User::get();

            if ($user != null) {
                return $user;
            }

            return false;

        } catch (\PDOException $e) {

            Log::channel('api_database_exceptions')
                ->emergency(
                    'Occurrence point => User Repository (getAllUsers method) ***** ' .
                    $e->getMessage() . ' ***** ' . $e->getLine()
                );

            return false;
        }
    }


    /**
     * Get all user
     *
     * @param int $userID
     * @return object|bool
     */
    public function getUser(int $id)
    {
        try {

            $user = User::find($id);

            if ($user != null) {
                return $user;
            }

            return ['message' => 'user not found !'];

        } catch (\PDOException $e) {

            Log::channel('api_database_exceptions')
                ->emergency(
                    'Occurrence point => User Repository (getUser method) ***** ' .
                    $e->getMessage() . ' ***** ' . $e->getLine()
                );

            return false;
        }
    }



    /**
     * Update user info
     *
     * @param int $data
     * @param int $id
     * @return object|bool
     */
    public function updateUserInfo(array $data,int $id)
    {
        try {
            $result = User::find($id);
            $result->update($data);
          
            if($result) {
                return true;
            }

            return false;

        } catch (\PDOException $e) {

            Log::channel('api_database_exceptions')
                ->emergency(
                    'Occurrence point => User Repository (updateUserInfo method) ***** ' .
                    $e->getMessage() . ' ***** ' . $e->getLine()
                );

            return false;
        }
    }


    /**
     * delete user info
     *
     * @param int $id
     * @return object|bool
     */
    public function deleteUser(int $id)
    {
        try {

            $result = User::find($id);
            $result->delete();
            
            if($result) {
                return true;
            }

            return false;

        } catch (\PDOException $e) {

            Log::channel('api_database_exceptions')
                ->emergency(
                    'Occurrence point => User Repository (deleteUser method) ***** ' .
                    $e->getMessage() . ' ***** ' . $e->getLine()
                );

            return false;
        }
    }




    /**
     * Update user info
     * @param int $id
     * @return object|bool
     */
    public function checkUserExist(int $id)
    {
        return User::find($id);
    }


}
