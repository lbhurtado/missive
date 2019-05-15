<?php

namespace LBHurtado\Missive\Repositories;

use LBHurtado\Missive\Models\Relay;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use LBHurtado\Missive\Validators\RelayValidator;

/**
 * Class ContactRepositoryEloquent.
 *
 * @package namespace App\Missive\Domain\Repositories;
 */
class RelayRepositoryEloquent extends BaseRepository implements RelayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return get_class(app('missive.relay'));
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return RelayValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
