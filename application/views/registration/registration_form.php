<?php
/*
 * Widok z formularzem rejstracji
 */
echo 'Formularz rejestracji';

/*
 * Tablica zawierająca informacje o tym, jakie inputy(poza tymi z hasłami)
 * będą w formularzu
 */
$our_form = array(
    'username' => 'login',
    'first_name' => 'imię',
    'last_name' => 'nazwisko',
    'address' => 'adres'
);

echo br();
echo form_open();

// Wyświetlamy inputy na podstawie tablicy
foreach($our_form as $key => $value) {  
    echo ucfirst($value).': ';
    echo form_input($key, set_value($key));
    echo br();
}

echo 'Hasło: ';
echo form_password('password');
echo br();
echo form_submit('submit', 'Zarejestruj');

echo form_close();

echo br();
echo validation_errors();


