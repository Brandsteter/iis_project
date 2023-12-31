<?php
namespace App\Enums;

use App\Models\Role;
use Exception;

enum RoleEnum
{
    case Admin;
    case Moderator;
    case User;

    /**
     * @throws Exception
     */
    public static function getUser()
    {
        $role = Role::where('role', RoleEnum::User->name)->first();
        if ($role === NULL){
            throw new Exception('Unable to find given role');
        }
        return $role;
    }

    /**
     * @throws Exception
     */
    public static function getModerator()
    {
        $role = Role::where('role', RoleEnum::Moderator->name)->first();
        if ($role === NULL){
            throw new Exception('Unable to find given role');
        }
        return $role;
    }

    /**
     * @throws Exception
     */
    public static function getAdmin()
    {
        $role = Role::where('role', RoleEnum::Admin->name)->first();
        if ($role === NULL){
            throw new Exception('Unable to find given role');
        }
        return $role;
    }

    /**
     * @throws Exception
     */
    public function getRole()
    {
        $role = Role::where('role', $this->name)->first();
        if ($role === NULL){
            throw new Exception('Unable to find given role');
        }
        return $role;
    }

    /**
     * @throws Exception
     */
    public static function fromString(string $string): RoleEnum
    {
        return collect(self::cases())->filter(function (RoleEnum $enum) use ($string){
            return $enum->name === $string;
        })->first() ?: throw new Exception("Unable to build enum from $string");
    }
}
