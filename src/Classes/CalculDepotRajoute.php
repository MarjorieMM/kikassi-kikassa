<?php

namespace App\Classes;

use Symfony\Component\Validator\Constraints\DateTime;

class CalculDepotRajoute
{
    private $finrc;
    private $objet;
    private $depotPermanent;

    public function __construct($finrc, $objet, $depotPermanent)
    {
        $this->finrc = $finrc;
        $this->objet = $objet;
        $this->depotPermanent = $depotPermanent;
    }

    function calculDepot()
    {
        $now = new DateTime('now');
        if ($this->finrc > $now) {
            return ($this->objet->getValeurAchat() *
                $this->objet->getCoefUsure()) /
                5 /
                3 -
                $this->depotPermanent;
        } else {
            return ($this->objet->getValeurAchat() *
                $this->objet->getCoefUsure()) /
                5 -
                $this->depotPermanent;
        }
    }
}