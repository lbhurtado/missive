<?php

namespace LBHurtado\Missive\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use LBHurtado\Missive\Validators\AirtimeValidator;

/**
 * Class AirtimeRepositoryEloquent.
 *
 * @package namespace App\Missive\Domain\Repositories;
 */
class AirtimeRepositoryEloquent extends BaseRepository implements AirtimeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return get_class(app('missive.airtime'));
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return AirtimeValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
