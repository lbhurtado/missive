<?php

namespace LBHurtado\Missive\Repositories;

use LBHurtado\Missive\Models\Contact;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use LBHurtado\Missive\Validators\ContactValidator;

/**
 * Class ContactRepositoryEloquent.
 *
 * @package namespace App\Missive\Domain\Repositories;
 */
class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return get_class(app('missive.contact'));
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ContactValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
