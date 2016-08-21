<?php
namespace App\Helpers;

use App\Services\BusinessCore;

class BladeHelpers
{
    public static function goBack()
    {
        return '
        <div class="row hidden-print">
            <div style="float:right">
                <a href="javascript:history.back()" class="btn btn-link">Volver</a>
            </div>
        </div>';
    }

    public static function inputMemberDisenrolled($user)
    {
        return self::isMemberDisenrolled($user) ? 'disabled' : '';
    }

    public static function isMemberDisenrolled($user)
    {
        return $user->state == BusinessCore::MEMBER_DISENROLLED;
    }
}