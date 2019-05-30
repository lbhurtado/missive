<?php

namespace LBHurtado\Missive;

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

    /**
     * @param array $attributes
     */
    public function topupMobile(array $attributes)
    {
        $mobile = $attributes['mobile'];
        $amount = $attributes['amount'];

        //TODO: create a separate package for SMS Driver Manager
        tap(Contact::create(compact('mobile')), function ($contact) use ($amount) {
            Topup::make(compact( 'amount'))->contact()->associate($contact)->save();
        });
    }

    /**
     * Extract the associative array in config('missive.relay')
     * to be used for default relay configuration e.g.
     *
     *  [
     *      'from' => 'from_number',
     *      'to' => 'to_number',
     *      'message' => 'content',
     *  ]
     *
     * @return array
     */
    public function getRelayProviderConfig(): array
    {
        return config('missive.relay.providers')[config('missive.relay.default')];
    }

    /**
     * Extract the associative array in config('tactician.fields')
     * and "merge: it with the relay provider config to be used for validation e.g.
     *
     * from
     *
     *  [
     *      'from' => 'required',
     *      'to' => 'required',
     *      'message' => 'string|max:800',
     *  ]
     *
     * to
     *
     *  [
     *      'from_number' => 'required',
     *      'to_number' => 'required',
     *      'content' => 'string|max:800',
     *  ]
     *
     * @return array
     */
    public function getRelayRules(): array
    {
        $relayRules = config('tactician.fields');

        return optional(array_flip($this->getRelayProviderConfig()), function ($mapping) use ($relayRules) {
            $rules = [];
            foreach ($mapping as $field => $rule) {
                $rules[$field] = $relayRules[$rule];
            }

            return $rules;
        });
    }

    //TODO: create an artisan command to challenge
}
