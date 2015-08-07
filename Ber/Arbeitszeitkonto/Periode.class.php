<?php

namespace Ber\Arbeitszeitkonto;

class Periode {
    public $tage;

    private $ZERO;

    public function __construct($tage) {
        $this->tage = $tage;
        $this->ZERO = \DateTimeImmutable::createFromFormat("U", 0);
    }

    private function anzahl_tage_status($status) {
        $counter = 0;
        foreach ($this->tage as $tag) {
            if ($tag->is($status)) {
                $counter++;
            }
        }
        return $counter;
    }

    private function summe_stunden($modus) {
        $counter = new \DateTime($this->ZERO->format('c'));
        foreach ($this->tage as $tag) {
            $counter->add($tag->stunden($modus));
        }
        return $this->ZERO->diff($counter);
    }

    private function prettyPrint(\DateInterval $diff) {
        return ($diff->invert ? '-' : '') .
            ($diff->days * 24 + $diff->h) .
            ':' . ($diff->i < 10 ? '0' : '') . $diff->i;
    }

    public function __get($name) {
        switch ($name) {
            case 'anzahl_urlaub':
                return $this->anzahl_tage_status(Status::URLAUB);
            case 'anzahl_krank':
                return $this->anzahl_tage_status(Status::KRANK);
            case 'anzahl_kind_krank':
                return $this->anzahl_tage_status(Status::KIND_KRANK);
            case 'stunden_soll':
                return $this->prettyPrint($this->summe_stunden('soll'));
            case 'stunden_ist':
                return $this->prettyPrint($this->summe_stunden('ist'));
            case 'stunden_ueber':
                return $this->prettyPrint($this->summe_stunden('ueber'));
        }
    }
}
