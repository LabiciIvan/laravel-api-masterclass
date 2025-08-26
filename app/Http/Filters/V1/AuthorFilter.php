<?php

namespace App\Http\Filters\V1;

use Illuminate\Support\Facades\Log;

class AuthorFilter extends QueryFilter {

    protected array $sortable = [
        'name',
        'email',
        'id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function include($value) {
        return $this->builder->with($value);
    }

    public function id($value) {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    public function email($value) {

        $likeString = str_replace('*', '%', $value);

        return $this->builder->where('email', 'LIKE', $likeString);
    }

    public function name($value) {

        $likeString = str_replace('*', '%', $value);

        return $this->builder->where('name', 'LIKE', $likeString);
    }

    public function createdAt($value) {

        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt($value) {

        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

}
