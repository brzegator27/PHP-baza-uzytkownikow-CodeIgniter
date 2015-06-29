Jesteś na stronie głównej.
<?php
/*
 * Widok strony głównej dla użytkowników, którzy nie są zalogowani
 */
    echo br();
    echo anchor('registration', 'Zarejestruj się!', 'title="registration"');
    echo br();
    echo anchor('logging/logging_in', 'Zaloguj się!', 'title="logging"');
?>