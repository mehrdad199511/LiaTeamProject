<?php

namespace App\Http\Controllers\Api\v1\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\ApiRoleException;
use App\Http\Controllers\Api\v1\Log\LogController;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RoleChangeRequest;
use App\Repositories\UserRepository;

class RoleController extends Controller
{
    
    /**
     * Update the role.
     *
     * @param  int  $id
     * @param Request $request
     * @param LogController $logController
     * @param RoleRepository $roleRepo
     * @param UserRepository $roleRepo
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        RoleChangeRequest $request, 
        LogController $logController,
        RoleRepository $roleRepo,
        UserRepository $userRepo
    ){
        if(!$userRepo->checkUserExist($id)){
            return response()->json([
                'message' => 'User not exist!'
            ], 404);
        }
        
        $requestData = $request->all();

        $log = [
            'user_id' => Auth::id(),
            'request' => json_encode($requestData),
        ];

        try{
            
            $result = $roleRepo->updateRole($requestData,$id);

            if(!$result) {

                $log['response'] = json_encode($result);
                $log['code'] =  501;

                return response()->json([
                    'message' => 'role not update!'
                ], 501);
            }

            $successResponse = [
                'message' => 'Successfull update role.',
            ];

            $log['response'] = json_encode($successResponse);
            $log['code'] = 202;
            
            return response()->json($successResponse, 202);
 
        } catch (ApiRoleException $e) {

            $e->customReport($e);

            $log['response'] = $e->getMessage();
            $log['code'] = $e->getCode();

            return response(['message' => $e->getMessage()], $e->getCode());
        
        }finally {
        
            $logController->logger($request, $log);
        }

    }

}
