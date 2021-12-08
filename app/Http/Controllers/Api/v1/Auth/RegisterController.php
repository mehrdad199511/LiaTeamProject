<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Log\LogController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ApiAuthException;
use Illuminate\Http\Response;
use App\Http\Resources\V1\UserResource;
use App\Repositories\UserRepository;

/**
 * Class RegisterController
 * @tag Auth
 * @namespace App\Http\Controllers\Api\v1\Auth
 */
class RegisterController extends Controller
{
    /**
     * Store register request data
     *
     * @var array
     */
    private $_requestData = [];

    /**
     * Store user access token
     *
     * @var string
     */
    private $_accessToken = '';

    /**
     * Store created user
     *
     * @var object
     */
    private $_user = null;

    /**
     * Do register
     *
     * @param RegisterRequest $request
     * @param UserRepository $userRepo
     * @param LogController $logController
     * @return Response
     */
    public function register(
        RegisterRequest $request,
        UserRepository $userRepo,
        LogController $logController
    ){
        $this->_requestData = $request->all();

        // Hashing user password
        $this->_requestData['password'] = Hash::make($this->_requestData['password']);

        $log = [
            'request' => json_encode($this->_requestData),
        ];

        try {

            $this->_createUser($userRepo);
            $this->_createRole();
            $this->_setAccessToken();

            $successResponse = [
                'message' => 'User creation is successful!',
                'user' => new UserResource($this->_user),
                'token' => $this->_accessToken
            ];

            $log['response'] = json_encode($successResponse);
            $log['code'] = 200;

            return response()->json($successResponse, 201);

        } catch (ApiAuthException $e) {
            $e->customReport($e);

            $log['response'] = $e->getMessage();
            $log['code'] = $e->getCode();

           return response(['message' => $e->getMessage()], $e->getCode());
           
        } finally {
           
            $logController->logger($request, $log);
        }
    }

    /**
     * Create new user
     *
     * @param UserRepository $userRepo
     * @return bool
     * @throws ApiAuthException
     */
    private function _createUser(UserRepository $userRepo)
    {
        $this->_user = $userRepo->create($this->_requestData);

        if (is_object($this->_user)) {
            return true;
        }

        throw new ApiAuthException("User creation failure!", 500);
    }

    /**
     * Create new user
     *
     * @return bool
     * @throws ApiAuthException
     */
    private function _createRole()
    {
        $roleCreationResult = $this->_user->role()->create([
            'user_id' => $this->_user->id,
            'role'    => 'basic'
        ]);

        if (is_object($roleCreationResult)) {
            return true;
        }

        throw new ApiAuthException("Role creation failure!", 500);
    }

    /**
     * Set access token for registered user
     */
    private function _setAccessToken()
    {
        $this->_accessToken = $this->_user->createToken('UserToken', ['basic'])->accessToken;
    }
}
