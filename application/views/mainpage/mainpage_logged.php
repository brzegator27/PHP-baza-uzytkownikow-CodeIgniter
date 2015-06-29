Jesteś na stronie głównej.
<?php
/*
 * Wiok ze stroną główną dla użytkowników, którzy są zalogowani
 */
    echo br(2);
    echo anchor('profile', 'Zobacz swój profil.', 'title="User profile"');
    echo br();
    echo anchor('logging/logging_out', 'Wyloguj', 'title="Logging out"');
?>
