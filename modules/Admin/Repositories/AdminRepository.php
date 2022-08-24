<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use DB;
use Modules\Admin\Models\Admin;

class AdminRepository extends BaseRepository
{
    /**
     * @return  Admin
     */
    public function getModel()
    {
        return Admin::class;
    }

    /**
     * @param mixed $query  Query.
     * @param mixed $column Column.
     * @param mixed $data   Data.
     *
     * @return Query
     */
    public function search($query, $column, $data)
    {
        switch ($column) {
            case 'id':
            case 'created_at':
                return $query->whereDate($column, $data);
            case 'username':
                return $query->where($column, 'like', '%'.$data.'%');
            default:
                return $query;
        }
    }
}
