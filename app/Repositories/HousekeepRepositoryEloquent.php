<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\HousekeepRepository;
use App\Entities\Housekeep;
use App\Validators\HousekeepValidator;

/**
 * Class HousekeepRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class HousekeepRepositoryEloquent extends BaseRepository implements HousekeepRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Housekeep::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
