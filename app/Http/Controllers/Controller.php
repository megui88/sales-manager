<?php

namespace App\Http\Controllers;

use App\Contract\CustomizeQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected function pagination($model)
    {
        /** @var Model $model */
        $model = new $model;
        $size = 15;
        /** @var Builder $query */
        $query = DB::table($model->getTable());
        $filters = $this->getFilters();

        foreach ($filters as $filter => $value) {

            if (empty($value)) {
                continue;
            }

            if ('true' === $value) {
                $value = true;
            }

            if ('false' === $value) {
                $value = false;
            }

            if (('size' == $filter && !$model->canUseInPaginate($filter)) || 'pageSize' == $filter) {
                $size = (int)$value;
                continue;
            }

            if (('order' == $filter && !$model->canUseInPaginate($filter)) || 'orderBy' == $filter) {
                $desc = ('-' == substr($value, 0, 1));
                if ($model->canUseInPaginate(!$desc ? $value : substr($value, 1))) {
                    $query->orderBy(
                        !$desc ? $value : substr($value, 1),
                        !$desc ? 'ASC' : 'DESC'
                    );
                }
                continue;
            }

            if ((('q' == $filter && !$model->canUseInPaginate($filter)) || 'queryCustom' == $filter)
                && $model instanceof CustomizeQuery
            ) {
                $query = $model->customQuery($query, $value, $filters);
            }

            if ($model->canUseInPaginate($filter)) {
                $query->where(
                    $filter,
                    '=',
                    $value
                );
            }

        }


        $paginate = $query->paginate($size);
        foreach ($filters as $filter => $value) {
            $paginate->addQuery("filters[$filter]", $value);
        }

        return $paginate;

    }

    /**
     * @return array
     */
    protected function getFilters()
    {
        $request = request();
        return $request->get('filters', []);
    }

    public function exceptionNotAurhoze()
    {
        $rules = [
            'password' => 'required',
        ];
        $messages = [
            'required' => 'La contraseña no pertence a un usuario autorizado.',
        ];

        $validator = Validator::make(['password'], $rules, $messages);
        $this->throwValidationException(request(), $validator);
    }
}
