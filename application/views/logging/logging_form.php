<?php
/*
 * Widok z formularzem logowania
 */

echo 'Formularz logowania';

echo br();
echo form_open();

echo 'Login: ';
echo form_input('username', set_value('username'));
echo br();
echo 'Hasło: ';
echo form_password('password');
echo br();
echo form_submit('submit', 'Zaloguj');

echo form_close();

echo br();
echo validation_errors();
