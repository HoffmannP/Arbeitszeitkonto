$(main);

function main() {
    $('div.nav button#forw').click(monat.bind(this, +1));
    $('div.nav button#back').click(monat.bind(this, -1));
    $('div.nav button#year').click(year);
    $('div.nav button#add').click(add);
}

function monat(direction) {
    var uri = URI(window.location);
    var show = {};
    if (uri.search(true).jahr) {
        show.year = +uri.search(true).jahr;
    }
    if (uri.search(true).monat) {
        show.month = +uri.search(true).monat - 1;
    }
    show = moment(show);
    if (direction > 0) {
        show.add('1', 'months');
    } else {
        show.subtract('1', 'months');
    }
    uri.setSearch('monat', show.month() + 1);
    if (show.year() != moment().year()) {
        uri.setSearch('jahr', show.year());
    } else {
        uri.removeSearch('jahr');
    }
    window.location = uri;
}

function year() {
    window.location = "Jahresübersicht.php";
}

function add() {
    window.location = "neuerArbeitstag.php";
}
