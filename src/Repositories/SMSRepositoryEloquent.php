<?php

namespace LBHurtado\Missive\Repositories;

use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Validators\SMSValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ContactRepositoryEloquent.
 *
 * @package namespace App\Missive\Domain\Repositories;
 */
class SMSRepositoryEloquent extends BaseRepository implements SMSRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return app('missive.sms');

//        return SMS::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return SMSValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
