<?php
namespace App\Helpers;

use App\Concept;
use App\Contract\Channels;
use App\Periods;
use App\Services\BusinessCore;
use App\User;
use Carbon\Carbon;

class BladeHelpers
{
    const FORMAT_DATE = 'd/m/Y';
    public static function import($number)
    {
        return BusinessCore::printAmount($number);
    }

    public static function goBack()
    {
        return '<div class="row hidden-print"><div style="float:right"><a href="javascript:history.back()" class="btn btn-link">Volver</a></div></div>';
    }

    public static function saleMode($sale_mode)
    {
        switch ($sale_mode){
            case Channels::CURRENT_ACCOUNT:
                return 'V';
                break;
            case Channels::SUBSIDY:
                return 'S';
                break;
            case Channels::PHARMACY_SELLING:
                return 'F';
                break;
            case Channels::PURCHASE_ORDER:
                return 'OC';
                break;
        }
    }

    public static function inputMemberDisenrolled($user)
    {
        return self::isMemberDisenrolled($user) ? 'disabled' : '';
    }

    public static function UserCode($user_id)
    {
        if('0' == $user_id){
            return 0;
        }

        return User::where('id', '=', $user_id)->first()->code;
    }

    public static function isMemberDisenrolled($user)
    {
        return $user->state == BusinessCore::MEMBER_DISENROLLED;
    }

    public static function buttonSubmit($message, $id = null, $function = null)
    {
        $html_id = $id ? "id=\"$id\"" : '';
        $function = ($function)??'submit()';
        return '<button type="button" class="btn btn-primary btn-submit" onclick="' . $function . '" '. $html_id .'>' . $message . '</button>';
    }

    public static function sellPeriodSelect($total = 4, $old = null, $id = 'period' , $current = false)
    {
        if(is_null($old)){
            $old = Periods::getCurrentPeriod()->uid;
        }
        $periods = BusinessCore::getPeriodAndFutures($total, $current);
        $content = "<select id='$id' name='$id' class=\"form-control\">";
        foreach ($periods as $period){
            $select = '';
            if($old == $period){
                $select = 'selected';
            }
            $content .=   "<option value='" . $period . "' $select>" . $period . "</option>";
        }
        return $content . "</select>";
    }

    public static function sellConceptSelect($sign = null, $old = null, $default = null)
    {
        if (empty($sign)) {
            $concepts = Concept::all();
        }else{
            $concepts = Concept::where('sign_operation', '=', $sign)->get();
        }
        $content = "<select id='concept_id' name='concept_id' class=\"form-control\">";
        foreach ($concepts as $concept){
            $select = '';
            if((is_null($old) && $concept->id == $default ) || $old == $concept->id){
                $select = 'selected';
            }
            $content .=   "<option value='" . $concept->id . "' $select>" . $concept->name . "</option>";
        }
        return $content . "</select>";
    }

    public static function date($date)
    {
        if ($date instanceof Carbon){
            return ('30/11/-0001' == $date->format(self::FORMAT_DATE)) ? '' : $date->format(self::FORMAT_DATE);
        }
        if ($date instanceof \DateTime){
            return ('00/00/0000' == $date->format(self::FORMAT_DATE)) ? '' : $date->format(self::FORMAT_DATE);
        }

        if ('string' == gettype($date)){
            return (new \DateTime($date))->format(self::FORMAT_DATE);
        }
    }

    public static function email($email)
    {
        return (substr($email,-8) == '@no-mail') ? '' : $email;
    }
}