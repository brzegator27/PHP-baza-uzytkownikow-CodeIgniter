<?php
/*
 * Widok z formularzem zmiany danych profilowych
 */
echo 'Zmiana danych profilowych';
echo br();
echo 'Pola pozostawione puste pozostaną niezmienione';
echo br(2);

/*
 * Tablica zawierająca informacje o tym, jakie inputy(poza tymi z hasłami)
 * będą w formularzu
 */
$our_form = array(
    'username' => 'login',
    'first_name' => 'imię',
    'last_name' => 'nazwisko',
    'address' => 'adres',
);

echo form_open();

// Wyświetlamy inputy na podstawie tablicy
foreach($our_form as $key => $value) {  
    echo ucfirst($value).': ';
    echo form_input($key, set_value($key));
    echo br();
}

echo 'Stare hasło: ';
echo form_password('password_old');

echo br();

echo 'Nowe hasło: ';
echo form_password('password');

echo br();
echo form_submit('submit', 'Zmień dane');

echo form_close();

echo br();
echo validation_errors();


