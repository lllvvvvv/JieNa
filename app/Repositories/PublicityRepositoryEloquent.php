<?php

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PublicityRepository;
use App\Entities\Publicity;
use App\Validators\PublicityValidator;

/**
 * Class PublicityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PublicityRepositoryEloquent extends BaseRepository implements PublicityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */



    public function model()
    {
        return Publicity::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
