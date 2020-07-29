<?php

namespace App\JsonApi;

use Illuminate\Support\Str;

class JsonApiBuilder
{

	public function jsonPaginate()
	{
		return function() {
			return $this->paginate(
                        $perPage = request('page.size'),
                        $columns = ['*'],
                        $pageName = 'page[number]',
                        $page = request('page.number')
                    )->appends(request()->except('page.number'));
		};
	}

	public function applySorts()
	{
		return function() {
			if(! property_exists($this->model, 'allowedSorts')){
                abort(500, 'please you shoud add allowedSorts array to model');
            }

            if(is_null($sort = request('sort'))){
                return $this;
            }

            $fields = Str::of($sort)->explode(',');

            foreach ($fields as $field) {
                $direction = 'asc';

                if(Str::of($field)->startsWith('-')){
                    $direction = 'desc';
                    $field = Str::of($field)->substr(1);
                }

                if(! collect($this->model->allowedSorts)->contains($field)){
                    abort(400);
                }

                $this->orderBy($field, $direction);
            }

            return $this;
		};
	}

}
