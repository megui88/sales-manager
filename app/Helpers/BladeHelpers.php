<?php
namespace App\Helpers;

use App\Concept;
use App\Services\BusinessCore;

class BladeHelpers
{
    public static function goBack()
    {
        return '<div class="row hidden-print"><div style="float:right"><a href="javascript:history.back()" class="btn btn-link">Volver</a></div></div>';
    }

    public static function inputMemberDisenrolled($user)
    {
        return self::isMemberDisenrolled($user) ? 'disabled' : '';
    }

    public static function isMemberDisenrolled($user)
    {
        return $user->state == BusinessCore::MEMBER_DISENROLLED;
    }

    public static function buttonSubmit($message, $id = null)
    {
        $html_id = $id ? "id=\"$id\"" : '';
        return '<button type="button" class="btn btn-primary btn-submit" onclick="submit()" '. $html_id .'>' . $message . '</button>';
    }

    public static function sellPeriodSelect($total = 4, $old = null)
    {
        $periods = BusinessCore::getPeriodAndFutures($total);
        $content = "<select id='period' name='period' class=\"form-control\">";
        foreach ($periods as $period){
            $select = '';
            if($old == $period){
                $select = 'selected';
            }
            $content .=   "<option value='" . $period . "' $select>" . $period . "</option>";
        }
        return $content . "</select>";
    }

    public static function sellConceptSelect($sign = null, $old = null)
    {
        if(empty($sign)) {
            $concepts = Concept::all();
        }else{
            $concepts = Concept::where('sign_operation', '=', $sign)->get();
        }
        $content = "<select id='concept_id' name='concept_id' class=\"form-control\">";
        foreach ($concepts as $concept){
            $select = '';
            if($old == $concept){
                $select = 'selected';
            }
            $content .=   "<option value='" . $concept->id . "' $select>" . $concept->name . "</option>";
        }
        return $content . "</select>";
    }
}