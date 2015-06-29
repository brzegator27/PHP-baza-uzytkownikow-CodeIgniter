Twój profil:
<?php
/*
 * Widok z informacjami profilowymi zalogowanego użytkownika
 */
    echo br(2);
    
    $userdata_to_show = array(
	'username' => 'login',
	'first_name' => 'imię',
	'last_name' => 'nazwisko',
	'address' => 'adres'
    );
    
    foreach ($userdata_to_show as $key => $label) {
	echo ucfirst($label).': ';
	echo $userdata->$key;
	echo br();
    }
    
    echo br(2);
    echo anchor('/mainpage', 'Strona główna.', 'title="Strona główna"');
    echo br();
    echo anchor('/profile/change_profile', 'Zmień dane w profilu.', 'title="Zmiana danych profilowych"');
    echo br();
    echo anchor('/profile/delete_user', 'Usuń konto.', 'title="Usuwanie konta"');