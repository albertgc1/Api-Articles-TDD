<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait HasSorts
{
	public function scopeApplySorts(Builder $query, $sort)
	{
		if(! property_exists($this, 'allowedSorts')){
			abort(500, 'please you shoud add allowedSorts array to model');
		}

		if(is_null($sort)){
			return;
		}

		$fields = Str::of(request('sort'))->explode(',');

        foreach ($fields as $field) {
            $direction = 'asc';

            if(Str::of($field)->startsWith('-')){
                $direction = 'desc';
                $field = Str::of($field)->substr(1);
            }

			if(! collect($this->allowedSorts)->contains($field)){
				abort(400);
			}

            $query->orderBy($field, $direction);
        }
	}
}
