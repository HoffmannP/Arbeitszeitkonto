<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arbeitszeitkonto - Monatsübersicht</title>
        <link rel="stylesheet" href="style.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.16.0/URI.min.js" charset="utf-8"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js" charset="utf-8"></script>
        <script src="script.js" charset="utf-8"></script>
    </head>
    <body>
        <h1>
            Stundenabrechnung
            <span class="nav noPrint">
                <button id="back">◀</button>
                <button id="year">▲</button>
                <button id="forw">▶</button>
                <button id="add">✚</button>
                <button id="css">◎</button>
            </span>
        </h1>
        <table class="Kopf">
            <tbody>
                <tr>
                    <th>Einrichtung</th>
                    <td><em>Tagesklink</em></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><em>Katja Lehr</em></td>
                </tr>
                <tr>
                    <th>Monat</th>
                    <td><em><?= strftime('%b %y', $monatDT->getTimestamp()) ?></em></td>
                </tr>
                <tr>
                    <th>Urlaub</th>
                    <td class="unit_tage"><?= $Monat->anzahl_urlaub ?></td>
                </tr>
                <tr>
                    <th>Krank</th>
                    <td class="unit_tage"><?= $Monat->anzahl_krank ?></td>
                </tr>
                <tr>
                    <th>Kind krank</th>
                    <td class="unit_tage"><?= $Monat->anzahl_kind_krank ?></td>
                </tr>
            </tbody>
        </table>
        <table class="Liste">
            <thead>
                <tr>
                    <th>Tag</th>
                    <th colspan="2">Arbeitszeit</th>
                    <th>Stunden</th>
                    <th>Überstunden</th>
                    <th>Sonstiges</th>
                </tr>
                <tr>
                    <td></td>
                    <td>von:</td>
                    <td>bis:</td>
                    <td></td>
                    <td></td>
                    <td>Urlaub/Krank</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($Monat->tage as $Tag): ?>
                <tr>
                    <td><?= $Tag ?></td>
                    <td><?= $Tag->beginn ?></td>
                    <td><?= $Tag->ende ?></td>
                    <td><?= $Tag->stunden_ist ?></td>
                    <td><?= $Tag->stunden_ueber ?></td>
                    <td>
                        <?= $Tag->status ?>
                        <?php if ($Tag->is(20)) {
                            echo '<span class="noPrint">' . $Stundenzettel->ueberstunden_bis($Tag->morgen) . ' h verbleibend</span>';
                        } ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <table class="Fuss">
            <tbody>
                <tr>
                    <td>Stunden soll:</td>
                    <td class="unit_stunden right"><?= $Monat->stunden_soll ?></td>
                    <td style="width:10em"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Stunden ist:</td>
                    <td class="unit_stunden right"><?= $Monat->stunden_ist ?></td>
                    <td></td>
                    <td>Jena,</td>
                </tr>
                <tr>
                    <td>Überstunden (Monat):</td>
                    <td class="unit_stunden right"><?= $Monat->stunden_ueber ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Überstunden (gesamt):</th>
                    <th class="unit_stunden right"><?= $Stundenzettel->ueberstunden_bis($monatDT->add(new \DateInterval('P1M'))) ?></th>
                    <td></td>
                    <td>Unterschrift:</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
