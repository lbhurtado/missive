<?php

namespace LBHurtado\Missive;

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

    public function verifyContact(string $otp)
    {
        $this->getContact()->verify(trim($otp));

        return $this;
    }
}
