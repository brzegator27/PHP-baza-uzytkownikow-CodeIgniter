<?php
/*
 * Widok z formularzem do usunięcia konta użytkownika
 */
echo 'Aby usunąć konto podaj hasło:';
echo br(2);

echo form_open();

echo 'Hasło: ';
echo form_password('password');

echo br();

echo form_submit('submit', 'Zmień dane');

echo form_close();

echo br();
echo validation_errors();


