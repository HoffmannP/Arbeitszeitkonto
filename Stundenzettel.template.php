<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arbeitszeitkonto - Monatsübersicht</title>
        <link rel="stylesheet" href="style.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.16.0/URI.min.js" charset="utf-8"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js" charset="utf-8"></script>
        <script src="script.js" charset="utf-8"></script>
    </head>
    <body>
        <div class="nav">
            <button id="back">←</button>
            <button id="year">↑</button>
            <button id="forw">→</button>
            <button id="add">+</button>
        </div>
        <h1>Stundenabrechnung</h1>
        <table class="Kopf">
            <tbody>
                <tr>
                    <th>Einrichtung</th>
                    <td>Tagesklink</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>Katja Lehr</td>
                </tr>
                <tr>
                    <th>Monat</th>
                    <td><?= $monatDT->format('m/y') ?></td>
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
                    <td><?= $Tag->status ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <table class="Fuss">
            <tbody>
                <tr>
                    <td>Stunden soll:</td>
                    <td><?= $Monat->stunden_soll ?></td>
                </tr>
                <tr>
                    <td>Stunden ist:</td>
                    <td><?= $Monat->stunden_ist ?></td>
                </tr>
                <tr>
                    <td>Überstunden (Monat):</td>
                    <td><?= $Monat->stunden_ueber ?></td>
                </tr>
                <tr>
                    <th>Überstunden (gesamt):</th>
                    <th><?= $Stundenzettel->ueberstunden_bis($monatDT->add(new \DateInterval('P1M'))) ?></th>
                </tr>
            </tbody>
        </table>
    </body>
</html>
