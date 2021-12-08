<?php

namespace App\Http\Controllers\Api\v1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\ApiUserException;
use App\Http\Controllers\Api\v1\Log\LogController;
use App\Http\Resources\V1\UserResourceCollection;
use App\Repositories\UserRepository;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    /**
     * Display a listing of the Users.
     *    
     * @param Request $request
     * @param LogController $logController
     * @param UserRepository $userRepo
     * @return \Illuminate\Http\Response
     */
    public function users(
        Request $request, 
        LogController $logController,
        UserRepository $userRepo
    ) {

        $this->_requestData = $request->all();

        $log = [
            'user_id' => Auth::id(),
            'request' => json_encode($this->_requestData),
        ];

        try{

            $users = $userRepo->getAllUsers();

            if(!$users) {

                $log['response'] = json_encode($users);
                $log['code'] = 404;

                return response()->json([
                    'message' => 'Users list not found!'
                ], 404);
            }

            $successResponse = [
                'message' => 'Successful get user info',
                'data' => new UserResourceCollection($users),
            ];

            $log['response'] = json_encode($successResponse);
            $log['code'] = 200;
            
            return response()->json($successResponse, 200);
 
        } catch (ApiUserException $e) {

            $e->customReport($e);

            $log['response'] = $e->getMessage();
            $log['code'] = $e->getCode();

            return response(['message' => $e->getMessage()], $e->getCode());
        
        }finally {
        
            $logController->logger($request, $log);
        }
    }


    /**
     * Display the specified User.
     *
     * @param  int  $id
     * @param Request $request
     * @param LogController $logController
     * @param UserRepository $userRepo
     * @return \Illuminate\Http\Response
     */
    public function show(
        $id,
        Request $request, 
        LogController $logController,
        UserRepository $userRepo
    ) {

        $requestData = $request->all();

        $log = [
            'user_id' => Auth::id(),
            'request' => json_encode($requestData),
        ];

        try{

            $result = $userRepo->getUser($id);

            if(!$result) {

                $log['response'] = json_encode($result);
                $log['code'] = 500;

                return response()->json([
                    'message' => 'There is a problem!'
                ], 500);

            }else if(is_array($result)) {

                $log['response'] = json_encode($result);
                $log['code'] = 404;

                return response()->json($result, 404);
            }

            $successResponse = [
                'message' => 'Successful get user info',
                'data' => new UserResource($result),
            ];

            $log['response'] = json_encode($successResponse);
            $log['code'] = 200;
            
            return response()->json($successResponse, 200);
 
        } catch (ApiUserException $e) {

            $e->customReport($e);

            $log['response'] = $e->getMessage();
            $log['code'] = $e->getCode();

            return response(['message' => $e->getMessage()], $e->getCode());
        
        }finally {
        
            $logController->logger($request, $log);
        }
    }



    /**
     * Update user info.
     *
     * @param  int  $id
     * @param Request $request
     * @param LogController $logController
     * @param UserRepository $userRepo
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        UpdateUserRequest $request, 
        LogController $logController,
        UserRepository $userRepo
    ){
        if(!$userRepo->checkUserExist($id)){
            return response()->json([
                'message' => 'User not exist!'
            ], 404);
        }

        $requestData = $request->all();
        $requestData['password'] = bcrypt($requestData['password']);

        $log = [
            'user_id' => Auth::id(),
            'request' => json_encode($requestData),
        ];

        try{
            
            $result = $userRepo->updateUserInfo($requestData,$id);

            if(!$result) {

                $log['response'] = json_encode($result);
                $log['code'] =  501;

                return response()->json([
                    'message' => 'User info not update!'
                ], 501);
            }

            $successResponse = [
                'message' => 'Successfull update info.',
            ];

            $log['response'] = json_encode($successResponse);
            $log['code'] = 202;
            
            return response()->json($successResponse, 202);
 
        } catch (ApiUserException $e) {

            $e->customReport($e);

            $log['response'] = $e->getMessage();
            $log['code'] = $e->getCode();

            return response(['message' => $e->getMessage()], $e->getCode());
        
        }finally {
        
            $logController->logger($request, $log);
        }

    }



    /**
     * Remove the user.
     *
     * @param  int  $id
     * @param Request $request
     * @param LogController $logController
     * @param UserRepository $userRepo
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        $id,
        UpdateUserRequest $request, 
        LogController $logController,
        UserRepository $userRepo
    ){
        if(!$userRepo->checkUserExist($id)){
            return response()->json([
                'message' => 'User not exist!'
            ], 404);
        }

        $log = [
            'user_id' => Auth::id(),
            'request' => json_encode($request->all()),
        ];

        try{
            
            $result = $userRepo->deleteUser($id);

            if(!$result) {

                $log['response'] = json_encode($result);
                $log['code'] =  501;

                return response()->json([
                    'message' => 'User info not delete!'
                ], 501);
            }

            $successResponse = [
                'message' => 'Successfull delete user',
            ];

            $log['response'] = json_encode($successResponse);
            $log['code'] = 202;
            
            return response()->json($successResponse, 202);
 
        } catch (ApiUserException $e) {

            $e->customReport($e);

            $log['response'] = $e->getMessage();
            $log['code'] = $e->getCode();

            return response(['message' => $e->getMessage()], $e->getCode());
        
        }finally {
        
            $logController->logger($request, $log);
        }
    }

}
