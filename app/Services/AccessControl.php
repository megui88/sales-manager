<?php

namespace App\Services;


class AccessControl
{

    private $roles;

    static private function getRoles()
    {
        return [
            BusinessCore::MEMBER_ROLE => self::memberAccess(),
            BusinessCore::VENDOR_ROLE => self::vendorAccess(),
            BusinessCore::PHARMACIST_ROLE => self::pharmacistAccess(),
            BusinessCore::EMPLOYEE_ROLE => self::emplyeeAccess(),
            BusinessCore::EMPLOYEE_ADMIN_ROLE => self::employeeAdminAccess(),
            BusinessCore::AGENT_ROLE => self::agentAccess(),
        ];
    }

    static public function hasAccess($userRole, $role)
    {
        $roles = self::getRoles();
        return in_array($role, $roles[$userRole]);
    }

    static private function memberAccess()
    {
        return [
            BusinessCore::MEMBER_ROLE,
        ];
    }

    static private function vendorAccess()
    {
        return [
            BusinessCore::MEMBER_ROLE,
            BusinessCore::VENDOR_ROLE,
        ];
    }

    static private function emplyeeAccess()
    {
        return [
            BusinessCore::MEMBER_ROLE,
            BusinessCore::VENDOR_ROLE,
            BusinessCore::EMPLOYEE_ROLE,
        ];
    }

    static private function employeeAdminAccess()
    {
        return [
            BusinessCore::MEMBER_ROLE,
            BusinessCore::VENDOR_ROLE,
            BusinessCore::PHARMACIST_ROLE,
            BusinessCore::EMPLOYEE_ROLE,
            BusinessCore::EMPLOYEE_ADMIN_ROLE,
            BusinessCore::AGENT_ROLE,
        ];
    }

    static private function pharmacistAccess()
    {
        return [
            BusinessCore::MEMBER_ROLE,
            BusinessCore::VENDOR_ROLE,
            BusinessCore::PHARMACIST_ROLE,
        ];
    }

    static private function agentAccess()
    {
        return [
            BusinessCore::AGENT_ROLE,
        ];
    }
}
