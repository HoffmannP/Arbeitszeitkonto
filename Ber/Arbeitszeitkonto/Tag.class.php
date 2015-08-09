<?php

namespace Ber\Arbeitszeitkonto;

class Tag {
    CONST MITTAGSPAUSE = 'PT30M';
    CONST STUNDEN_SOLL = 'PT7H30M';

    private $date;
    private $status;
    private $beginn;
    private $ende;

    private $MITTAGSPAUSE;
    private $STUNDEN_SOLL;
    private $NULL_INTERVALL;

    public function __construct(Status $status, \DateTimeImmutable $beginn, \DateTimeImmutable $ende) {
        $this->status = $status;
        $this->beginn = $beginn;
        $this->ende = $ende;
        $this->MITTAGSPAUSE = new \DateInterval(self::MITTAGSPAUSE);
        $this->STUNDEN_SOLL = new \DateInterval(self::STUNDEN_SOLL);
        $this->STUNDEN_UEBER = new \DateInterval(self::STUNDEN_SOLL);
        $this->STUNDEN_UEBER->invert = 1;
        $this->NULL_INTERVALL = new \DateInterval('PT0M');
    }

    public function is($status) {
        return $this->status->is_status($status);
    }

    private function calc_stunden_ist() {
        $tmp = $this->beginn->add($this->MITTAGSPAUSE);
        return $tmp->diff($this->ende);
    }

    private function calc_stunden_ueber() {
        if ($this->status->ist_ueberstunden_frei) {
            return $this->STUNDEN_UEBER;
        }
        return $this->beginn
            ->add($this->STUNDEN_SOLL)
            ->add($this->MITTAGSPAUSE)
            ->diff($this->ende);
    }

    public function stunden($modus) {
        switch ($modus) {
            case 'soll':
                if ($this->status->ist_arbeitstag) {
                    return $this->STUNDEN_SOLL;
                }
                return $this->NULL_INTERVALL;
            case 'ist':
                if ($this->status->ist_arbeitstag) {
                    return $this->calc_stunden_ist();
                }
                return $this->NULL_INTERVALL;
            case 'ueber':
                if ($this->status->ist_arbeitstag ||
                    $this->status->ist_ueberstunden_frei) {
                    return $this->calc_stunden_ueber();
                }
                return $this->NULL_INTERVALL;
            default:
                return new \Exception('Nicht unterstützte stunden-Modus in Klasse "' . __CLASS__ . '".');
        }
    }

    public function __get($name) {
        switch ($name) {
            case 'beginn':
                if ($this->status->ist_arbeitstag) {
                    return $this->beginn->format("G:i");
                }
                return null;
            case 'ende':
                if ($this->status->ist_arbeitstag) {
                    return $this->ende->format("G:i");
                }
                return null;
            case 'stunden_ist':
                if ($this->status->ist_arbeitstag) {
                    return $this->calc_stunden_ist()->format("%h:%I");
                }
                return null;
            case 'stunden_ueber':
                if ($this->status->ist_arbeitstag ||
                    $this->status->ist_ueberstunden_frei) {
                    return $this->calc_stunden_ueber()->format("%r%h:%I");
                }
                return null;
            case 'status':
                return $this->status->__toString();
            case 'morgen':
                return $this->beginn->setTime(0, 0)->modify('next day');
            default:
                throw new \Exception('Nicht unterstützte GET-Variable "' . $name .'" in Klasse "' . __CLASS__ . '".');
        }
    }

    public function __toString() {
        return $this->beginn->format("j") .
            "<span class='noPrint'> " . strftime("%a", $this->beginn->getTimestamp()) . "</span>";
    }
}
