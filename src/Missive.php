<?php

namespace LBHurtado\Missive;

use Illuminate\Support\Arr;
use LBHurtado\Missive\Models\Topup;
use LBHurtado\Missive\Models\Contact;
use LBHurtado\Missive\Types\ChargeType;
use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Missive\Pivots\AirtimeContact;
use LBHurtado\Missive\Repositories\AirtimeRepository;

class Missive
{
    /** @var  \LBHurtado\Missive\Classes\SMSAbstract */
    protected $sms;

    /** @var \LBHurtado\Missive\Repositories\AirtimeRepository */
    protected $airtimes;

    public function __construct(AirtimeRepository $airtimes)
    {
        $this->airtimes = $airtimes;
    }

    /**
     * @param SMSAbstract $sms
     * @return $this
     */
    public function setSMS(SMSAbstract $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * @return SMSAbstract
     */
    public function getSMS(): SMSAbstract
    {
        return $this->sms;
    }

    /**
     * @return Contact
     */
    public function getContact(): Contact
    {
        return $this->getSMS()->origin;
    }

    /**
     * @param ChargeType $key
     * @param int $qty
     */
    public function chargeSMS(ChargeType $key, int $qty = 1)
    {
        //TODO: return newly created pivot record
        tap($this->getContact(), function ($contact) use ($key, $qty) {
            optional($this->airtimes->findWhere(['key' => $key->value()])->first(), function ($airtime) use ($contact, $qty) {
                $contact->addAirtime($airtime, (new AirtimeContact)->setQty($qty));
            });
        });
    }

    /**
     * @param string $otp
     * @return $this
     */
    public function verifyContact(string $otp)
    {
        $this->getContact()->verify(trim($otp));

        return $this;
    }

    public function topupMobile(array $attributes)
    {
        $mobile = $attributes['mobile'];
        $amount = $attributes['amount'];

        //TODO: create a separate package for SMS Driver Manager
        tap(Contact::create(compact('mobile')), function ($contact) use ($amount) {
            Topup::make(compact( 'amount'))->contact()->associate($contact)->save();
        });
    }

    //TODO: create an artisan command to challenge
}
