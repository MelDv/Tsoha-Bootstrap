<?php

class PersonController extends BaseController {

    public static function index() {
        $persons = Person::all();
        View::make('kayttaja/kayttajat.html', array('persons' => $persons));
    }

    public static function kirjaudu() {
        View::make('kayttaja/kirjaudu.html');
    }

    public static function kirjaudu_ulos() {
        $_SESSION['person'] = null;
        Redirect::to('/', array('message' => 'Sinut on kirjattu ulos.'));
    }

    public static function kirjaudu_sisaan() {
        $params = $_POST;
        $person = Person::authenticate($params['email'], $params['password']);

        if (!$person) {
            View::make('kayttaja/kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'email' => $params['email']));
        } else {
            $_SESSION['person'] = $person->id;
            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $person->name . '!'));
        }
    }

    public static function omasivu() {
        View::make('kayttaja/omasivu.html');
    }

    //lomakkeen näyttäminen
    public static function uusikayttaja() {
        View::make('kayttaja/uusikayttaja.html');
    }

    //käyttäjän tietojen muokkaaminen

    public static function muokkaa_oma($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => $params['password'],
            'description' => $params['description']
        );

//        Kint::dump($params);

        $person = new Person($attributes);
        $errors = $person->errors();

        if (count($errors) > 0) {
            View::make('kayttaja/muokkaa_omasivu.html', array('errors' => $errors, 'person' => $person));
        } else {
            $person->update();
            Redirect::to('/kayttajat/' . $person->id, array('message' => 'Tiedot on päivitetty'));
        }
    }

    public static function muokkaa_muita($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'password' => $params['password'],
            'active' => $params['active'],
            'current_rights' => $params['current_rights']
        );

        $person = new Person($attributes);
        $errors = $person->errors();

        if (count($errors) > 0) {
            View::make('kayttaja/muokkaa.html', array('errors' => $errors, 'person' => $person));
        } else {
            $person->updateRights();
            Redirect::to('/kayttajat/' . $person->id, array('message' => 'Käyttäjän tiedot päivitettiin'));
        }
    }

    //uuden käyttäjän lisääminen
    public static function uusi() {
        $params = $_POST;
        $attributes = array(
            'active' => $params['active'],
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => $params['password'],
            'current_rights' => $params['current_rights']
        );
//        Kint::dump($params);

        $person = new Person($attributes);
        $errors = $person->errors();

        if (count($errors) == 0) {
            $person->save();
            Redirect::to('/kayttajat/' . $person->id, array('message' => 'Käyttäjä lisättiin tietokantaan'));
        } else {
            array_unshift($errors, 'Antamissasi tiedoissa oli virheitä. ');
            View::make('kayttaja/uusikayttaja.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    //käyttäjän poistaminen
    public static function poista_kayttaja($id) {
        $person = new Person(array('id' => $id));
        $nimi = Person::findName($id);
        $person->delete($id);

        Redirect::to('/kayttajat', array('message' => 'Käyttäjä ' . $nimi . ' on poistettu'));
    }

    //lomakkeen näyttäminen
    public static function muokkaa_omasivu($id) {
        $person = Person::find($id);
        View::make('kayttaja/muokkaa_omasivu.html', array('person' => $person));
    }

    //lomakkeen näyttäminen - admin
    public static function muokkaa_hlotietoja($id) {
        $person = Person::find($id);
        View::make('kayttaja/muokkaa.html', array('person' => $person));
    }

    //käyttäjän esittelysivu
    public static function esittely($id) {
        $person = Person::find($id);
        View::make('kayttaja/esittely.html', array('person' => $person));
    }

}
