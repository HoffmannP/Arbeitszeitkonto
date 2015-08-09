$(main);

function main() {
    Mousetrap.bind('left', monat.bind(this, -1));
    Mousetrap.bind('right', monat.bind(this, +1));
    Mousetrap.bind('ctrl+up', year);
    Mousetrap.bind('+', add);
    Mousetrap.bind('p', css);

    $('.nav button#forw').click(monat.bind(this, +1));
    $('.nav button#back').click(monat.bind(this, -1));
    $('.nav button#year').click(year);
    $('.nav button#add').click(add);
    $('.nav button#css').click(css);
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

function css() {
    $('.noPrint').css(
        'display',
        $('.noPrint').css('display') == 'inline' ?
            'none' :
            'inline'
    );
}
