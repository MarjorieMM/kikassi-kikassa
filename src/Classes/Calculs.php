<?php

namespace App\Classes;

class Calculs
{
    private $finrc;
    private $objet;
    private $depotPermanent;
    private $emprunt;
    private $now;

    public function __construct($finrc, $objet, $depotPermanent, $emprunt)
    {
        $this->finrc = $finrc;
        $this->objet = $objet;
        $this->depotPermanent = $depotPermanent;
        $this->emprunt = $emprunt;
        $this->now = new \DateTime();
    }

    function calculDepot($adherent): int
    {
        if ($adherent) {
            if ($this->finrc > $this->now) {
                $res =
                    ($this->objet->getValeurAchat() *
                        $this->objet->getCoefUsure()) /
                        5 /
                        3 -
                    $this->depotPermanent;
                return $res > 0 ? $res : 0;
            } else {
                $res =
                    ($this->objet->getValeurAchat() *
                        $this->objet->getCoefUsure()) /
                        5 -
                    $this->depotPermanent;
                return $res > 0 ? $res : 0;
            }
        } else {
            return 0;
        }
    }

    function calculPrix(): int
    {
        $days = $this->emprunt
            ->getDateDebut()
            ->diff($this->emprunt->getDateFin())->days;
        return (((($this->objet->getValeurAchat() *
            $this->objet->getPourcentCalcul()) /
            100) *
            $this->objet->getCoefUsure()) /
            5) *
            $days;
    }
}