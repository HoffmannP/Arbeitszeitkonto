<?php

namespace Ber\Arbeitszeitkonto;

class Status {
    CONST ARBEITEN = 0;
    CONST KRANK = 1;
    CONST URLAUB = 2;

    CONST WOCHENENDE = 10;
    const FEIERTAG = 11;
    CONST FREI = 12;
    CONST FREIGESTELLT = 13;
    CONST KIND_KRANK = 14;

    CONST UEBERSTUNDEN_FREI = 20;

    private $status;
    private $toString;

    public function __construct($status) {
        $this->toString = array(
            self::ARBEITEN => null,
            self::KRANK => 'Krank',
            self::URLAUB => 'Urlaub',

            self::WOCHENENDE => '<span class="noPrint">Wochenende</span>',
            self::FEIERTAG => 'Feiertag',
            self::FREI => '<span class="noPrint">Frei</span>',
            self::FREIGESTELLT => 'Weiterbildung (bezahlte Freistellung)',
            self::KIND_KRANK => 'Kind krank',

            self::UEBERSTUNDEN_FREI => 'Überstundenabbau',
        );

        if (!array_key_exists($status, $this->toString)) {
            throw new \Exception('Nicht unterstützter Status-Wert "' . $status . '" in Klasse "' . __CLASS__ . '".');
        }

        $this->status = $status;
    }

    public function is_status($status) {
        return $this->status == $status;
    }

    public function __get($name) {
        switch ($name) {
            case 'ist_arbeitstag':
                return $this->is_status(self::ARBEITEN);
            case 'ist_ueberstunden_frei':
                return $this->is_status(self::UEBERSTUNDEN_FREI);
            default:
                throw new \Exception('Nicht unterstützte GET-Variable "' . $name . '" in Klasse "' . __CLASS__ . '".');

        }
    }

    public function __toString() {
        return $this->toString[$this->status];
    }
}
