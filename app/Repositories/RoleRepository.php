<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Models\Role;

class RoleRepository
{
    /**
     * Create new user
     *
     * @param array $user
     * @return object|bool
     */
    public function updateRole(array $role, int $id)
    {
        try {
        
            $result = Role::where('user_id', $id)
                ->update(['role' => $role['role']]);
            
            if($result) {
                return true;
            }

            return false;

        } catch (\PDOException $e) {

            Log::channel('api_database_exceptions')
                ->emergency(
                    'Occurrence point => Roler Repository (updateRole method) ***** ' .
                    $e->getMessage()
                );

            return false;
        }
    }

}
