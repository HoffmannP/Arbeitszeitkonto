<?php

namespace Ber\Arbeitszeitkonto;

class Arbeitszeitkonto {
    CONST ERZEUGE_TABELLE = <<<EOT
        CREATE TABLE IF NOT EXISTS Arbeitstage (
            status Arbeitszeitkonto.class.php,
            beginn TEXT,
            ende TEXT
        )
EOT;
    CONST LESE_TAG  = 'SELECT status, beginn, ende FROM Arbeitstage WHERE substr(beginn, 1, 10) = :datum';
    CONST LESE_ALLE_BIS = 'SELECT status, beginn, ende FROM Arbeitstage WHERE strftime("%s", beginn) < :datum;';

    private $getDay;
    private $getAlleBis;

    public function __construct($dsn) {
        $db = new \PDO($dsn);
        $db->query(self::ERZEUGE_TABELLE);
        $this->getDay = $db->prepare(self::LESE_TAG);
        $this->getAlleBis = $db->prepare(self::LESE_ALLE_BIS);
    }

    private function summe_ueberstunden(\DateTimeInterface $datum) {
        $zero = \DateTime::createFromFormat("U", 0);
        $counter = clone($zero);

        $this->getAlleBis->bindValue(':datum', $datum->format('U'));
        $this->getAlleBis->execute();

        foreach ($this->getAlleBis as $columns) {
            $tmp_tag = new Tag(
                new Status($columns['status']),
                new \DateTimeImmutable($columns['beginn']),
                new \DateTimeImmutable($columns['ende'])
            );
            $counter->add($tmp_tag->stunden('ueber'));
        }
        return $zero->diff($counter);
    }

    private function prettyPrint(\DateInterval $diff) {
        return ($diff->invert ? '-' : '') .
            ($diff->days * 24 + $diff->h) .
            ':' . ($diff->i < 10 ? '0' : '') . $diff->i;
    }

    public function ueberstunden_bis(\DateTimeInterface $bis) {
        return $this->prettyPrint($this->summe_ueberstunden($bis));
    }

    public function woche(\DateTimeInterface $beginn) {
        return $this->periode($beginn->format('Y-\WW-1'), 'P7D');
    }

    public function monat(\DateTimeInterface $beginn) {
        return $this->periode($beginn->format('Y-m-01'), 'P1M');
    }

    public function jahr(\DateTimeInterface $beginn) {
        return $this->periode($beginn->format('Y-01-01'), 'P1Y');
    }

    public function periode($beginn, $interval_spec) {
        $anfang_der_periode = new \DateTimeImmutable($beginn);
        $periode = new \DatePeriod(
            $anfang_der_periode,
            new \DateInterval('P1D'),
            $anfang_der_periode->add(new \DateInterval($interval_spec))
        );
        $tage = array();
        foreach ($periode as $datum) {
            $tmp_tag = $this->tag($datum);
            if ($tmp_tag && !$tmp_tag->is(Status::WOCHENENDE)) {
            $tage[] = $tmp_tag;
            }
        }
        return new Periode($tage);
    }

    public function tag(\DateTimeInterface $date) {
        $this->getDay->bindValue(':datum', $date->format('Y-m-d'));
        $this->getDay->execute();
        $columns = $this->getDay->fetch(\PDO::FETCH_ASSOC);

        if ($columns === false) {
            return null;
        }

        return new Tag(
            new Status($columns['status']),
            new \DateTimeImmutable($columns['beginn']),
            new \DateTimeImmutable($columns['ende'])
        );
    }
}
