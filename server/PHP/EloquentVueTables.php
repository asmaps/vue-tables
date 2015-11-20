<?php

namespace App\Services\VueTables;

use Input;

Class EloquentVueTables extends VueTables implements VueTablesInterface  {

  public function get($model, Array $fields) {

    extract(Input::all());

    $data = $model->select($fields);

    if (isset($query) && $query) {

      $query = $this->parseQuery($query, $byColumn);

      if ($byColumn==1):
       foreach ($query as $field=>$query):
         $data->where($field,'LIKE',"%{$query}%");
       endforeach;
       else:
        foreach ($fields as $index=>$field):
          $method = $index?"orWhere":"where";
          $data->{$method}($field,'LIKE',"%{$query}%");
        endforeach;
        endif;
      }

      $count = $data->count();

      $data->limit($limit)
      ->skip($limit * ($page-1));

      if (isset($orderBy) && $orderBy):
        $direction = $ascending==1?"ASC":"DESC";
      $data->orderBy($orderBy,$direction);
      endif;

      $results = $data->get()->toArray();

      return ['data'=>$results,
              'count'=>$count];

    }

  }
