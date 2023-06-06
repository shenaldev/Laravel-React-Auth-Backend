<?php
namespace App\Traits;

trait UserRolesTrait
{
    /**
     * ADD USER ROLES INTO ARRAY
     * @param $user User Object
     * @return $role Array Of User Roles
     */
    public function getUserRoles($user)
    {
        $roles = [];
        foreach ($user->roles as $role) {
            array_push($roles, $role->slug);
        }

        return $roles;
    }
}
