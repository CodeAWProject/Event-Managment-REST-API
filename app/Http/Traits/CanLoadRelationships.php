<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{

    // Model or QB or EB for $for variable
    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder
    {

        //$relations = it uses relations if the parameter is passed
        //$this->relations = if it's not then try to get the field relations defined inside the class where this trade is
        $relations = $relations ?? $this->relations ?? [];

        foreach($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if(!$include) {
            return false;
        }

        //array map_with trim will remove all the starting leading spaces and all the ending spaces from any string
        $relations = array_map('trim' ,explode(',', $include));


        //Check if a specific relation that's passed to this method is inside relations array
        return in_array($relation, $relations);
    }
}