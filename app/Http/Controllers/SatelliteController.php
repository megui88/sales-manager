<?php

namespace App\Http\Controllers;

use App\Company;
use App\Periods;
use App\Sale;
use App\Services\BusinessCore;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use \DB;

class SatelliteController extends Controller
{

    public function downloadFile()
    {
        $period = Request()->get('period');
        $_period = Periods::where('uid', '=', $period)->first();
        $date = is_null($_period->closed_at)
            ? date('Ymd', strtotime('now'))
            : $_period->closed_at->format('Ymd');
        $rows = DB::select(DB::raw(
            "SELECT u.code, " .
            " u.headquarters_id," .
            " SUM(d.amount_of_quota) as mount FROM users as u " .
            "JOIN dues as d ON d.period = :period AND d.payer_id=u.id AND d.state NOT IN ('" . Sale::ANNULLED . "', '" . Sale::PENDING . "') " .
            "WHERE u.role != 'proveedor' AND u.state='" . BusinessCore::MEMBER_AFFILIATE . "'AND u.company_id='b3cb9468-9092-11e6-9568-04011111c601' " .
            "GROUP BY d.payer_id ORDER by u.headquarters_id, u.code"),
            ['period' => $period,]
        );
        $total = 0;
        $content = "\t\t\tCANT\t\t" . number_format(count($rows), 2, ',', '') . PHP_EOL;
        foreach ($rows as $row) {
            $code = ($row->headquarters_id == 'b3d519f2-9092-11e6-9ece-04011111c601') ? 5132 : 5124;
            $content .= $row->code . "\t\t" . $date . "\t" . $code . "\t" . number_format($row->mount, 2, ',',
                    '') . PHP_EOL;
            $total += $row->mount;
        }
        $content .= "\t\t\tTOTA\t" . number_format($total, 2, ',', '') . PHP_EOL;
        return Response::make($content)
            ->header("Content-type", " charset=utf-8")
            ->header("Content-disposition", "attachment; filename=\"satelite_" . $period . "_MUTUALMP.txt\"");
    }

    public function othersFile()
    {
        $period = Request()->get('period');
        $company = Company::where('id', '=', Request()->get('company_id'))->first();
        $rows = DB::select(DB::raw(
            "SELECT u.code, " .
            " CONCAT(u.name,' ',u.last_name) as name, " .
            " u.headquarters_id," .
            " SUM(d.amount_of_quota) as mount FROM users as u " .
            "JOIN dues as d ON d.period = :period AND d.payer_id=u.id AND d.state NOT IN ('" . Sale::ANNULLED . "', '" . Sale::PENDING . "') " .
            "WHERE u.role != 'proveedor' AND u.state='" . BusinessCore::MEMBER_AFFILIATE . "'AND u.company_id='" . $company->id . "' " .
            "GROUP BY d.payer_id ORDER by u.headquarters_id, u.code"),
            ['period' => $period,]
        );
        $content = "Legajo" . PHP_EOL;
        foreach ($rows as $row) {
            $code = ($row->headquarters_id == 'b3d519f2-9092-11e6-9ece-04011111c601') ? 'Merlo' : 'Goya';
            $content .= $row->code . ';' . $row->name . ';' . number_format($row->mount, 2, ',',
                    '') . ';' . $code . PHP_EOL;
        }
        return Response::make($content)
            ->header("Content-type", " charset=utf-8")
            ->header("Content-disposition", "attachment; filename=\"" . $company->name . "_" . $period . "_.csv\"");
    }
}
