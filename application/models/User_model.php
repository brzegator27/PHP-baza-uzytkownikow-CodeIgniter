<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Klasa User_model;
 * odpowiada za zarządzanie danymi użytkowników
 * 
 * @category user_account
 * @author Jakub Brzegowski
 */
class User_model extends CI_Model {

    /*
     * Tablica mówiąca funkcji update_userdata(), jakie dane ma pobrać z przesłanego formularza
     * 
     * @access private
     */
    private $passed_data_key = array(
	'username',
	'first_name',
	'last_name',
	'address',
    );
    
    /*
     * Uaktualnia dane o użytkowniku
     * Bierze je z przekazanego formularza metodą POST
     * 
     * @return bool TRUE jeśli operacja powiodła się; inaczej FALSE
     */
    public function update_userdata() {
	/*
	 * Tablica na dane, które mają zostać uaktualnione
	 */
	$new_user_data = array();
	
	// Na podstaiwe tablicy passed_data_key pobieramy te dane z tablicy POST
	// i wstawiamy do naszej tablicy
	foreach ($this->passed_data_key as $key) {
	    if ($this->input->post($key) != NULL) {
		$new_user_data[$key] = $this->input->post($key);
	    }
	}
	
	// Hasło pobieramy oddzielnie, jako że chcemy je zaszyfrować
	if ($this->input->post('password') != NULL) {
	    $new_user_data['password'] = md5($this->input->post('password'));
	}
	
	// Uaktualniamy dane w bazie danych na podstawie user_id z sesji
	$this->db->where('user_id', $this->session_get_user_id());
	$result = $this->db->update('users', $new_user_data);
	
	if ($result) {
	    // Jeśli uaktualnienie danych w bazie się powiodło, musimy również
	    // uaktualnić dane w naszej sesji
	    $result = $result && $this->session_update();
	}
	
	return $result;
    }

    /*
     * Przekazuje dane profilowe zalogowanego użytkownika
     * 
     * @return row Zwraca obiekt z danymi użytkownika, jeśli opecja się udała, w przeciwnym wypadku NULL
     */
    public function get_userdata() {
        $user_id = $this->session_get_user_id();
	
	if (isset($user_id)) {
	    $query = $this->db->get_where('users', array('user_id' => $user_id));
	    
	    if ($query->num_rows() > 0) {
		// Zwracamy obiekt z danymi:
		return $query->row();
	    } else {
		return NULL;
	    }
	}
	
	return NULL;
    }
    
    /*
     * Kasuje konto użytkownika
     * 
     * @return bool TRUE, jeśli konto zostało usunięte; inaczej FALSE
     */
    public function delete_user() {
	// usuwamy konto użytkownika:
	$result = $this->db->delete('users', array('user_id' => $this->session_get_user_id()));
	
	if ($result) {
	    // Jeśli usuneliśmy konto z powodzeniem usuwamy jego dane z sesji
	    $this->session_log_out();
	}
	
	return $result;
    }
    
    /*
     * Sprawdza czy przekazana dana o danym typie już istnieje w bazie danych w tablicy 'users'
     * 
     * @param string $data szukana dana
     * @param string $type typ szukanej danej - nazwa kolumny w bazie danych
     * 
     * @return bool TRUE, gdy został znaleziony duplikat; inaczej FALSE
     */
    public function look_for_the_same($data, $type) {
        $query = $this->db->get_where('users', array($type => $data));
        
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /*
     * Tworzy nowego użytkownika poprzez zapisanie danych do tabeli users z bazy danych
     * 
     * @return bool TRUE jeśli dodanie danych się powiodło, w przeciwnym wypdku FALSE
     */
    public function create_user() {
	// Tablica z danymi przekazanymi przez formularz
        $new_user_data = array(
            'username' => $this->input->post('username'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'address' => $this->input->post('address'),
            'password' => md5($this->input->post('password')),
        );
        
	// Wstawiamy użytkownika do bazy danych
        $query = $this->db->insert('users', $new_user_data);
        
        return $query;
    }
    
    /*
     * Sprawdza, czy podane hasło jest poprawne dla danego użytkownika
     * 
     * @param string $password Hasło, jeśli nie jest podane zakładamy, że zostało przesłane w formularzu metodą POST
     * @param string $user_id Id użytkownika z bazy danych, 
     *				jeśli nie jest podane, zakładamy, że sprawdzamy hasło dla użytkowniak, które jest aktualnie zalogowany
     * @param string $username Login użytkownika względem którego ma zostać sprawdzone hasło
     * 
     * @return bool TRUE jeśli hasło jest poprawne, w przecinwnym wypadku FALSE
     */
    public function check_password($password = '', $user_id = '', $username = '') {
	// Jeśli hasło nie zostało przekazane jako paramentr, pobieramy je z tablicy POST
	if ($password === '') {
	    $password = $this->input->post('password');
	}
	
	// Jeśli ani user_id, jak i username nie zostało przekazane, to pobieramy z sesji user_id i operujemy tylko przy jego użyciu
	if ($user_id === '' && $username === '') {
	    $user_id = $this->session_get_user_id();
	    
	    $query = $this->db->get_where('users', array('user_id' => $user_id, 'password' => md5($password)));
	} else {
	    // Któraś z danych jest ustawiona, więc:
	    
	    // Jeśli zostało przekazane user_id, to wykorzystujemy tylko je
	    if ($user_id !== '') {
		$query = $this->db->get_where('users', array('user_id' => $user_id, 'password' => md5($password)));
	    } 
	    // Jeśli nie zostało przekazane user_id, to wykorzystujemy $username
	    else {
		$query = $this->db->get_where('users', array('username' => $username, 'password' => md5($password)));
	    }   
	}

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /*
     * Loguje użytkownika, zakłada, że odpowiednie dane zostały przekazane metodą POST
     * 
     * @return bool TRUE jeśli logowanie powiodło się; inaczej FALSE
     */
    public function log_in() {
	// Pobieramy potrzebne dane
        $user_data = array(
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
        );
        
	// Pobieramy dane
        $query = $this->db->get_where('users', $user_data);
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
	    // Logujemy użytkownika
            $this->session_log_in($row->user_id, $row->username);
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /*
     * Ustanawia sesję dla danego użytkownika
     * 
     * @param string $user_id Id użytkownika, który ma zostać zalogowany
     * @param string $username Nazwa użytkownika, który ma zostać zalogowany
     */
    private function session_log_in($user_id, $username) {
        // Zakładamy, że sesja została otwarta

	// Ustawiamy zmienne sesji
	$_SESSION['user_id'] = $user_id;
	$_SESSION['username'] = $username;
	// 1 oznacza, że użytkownik jest wylogowany
	$_SESSION['is_logged_in'] = 1;
    }
    
    /*
     * Wylogowywanie; usuwa odpowiednie dane z sesji
     */
    public function session_log_out() {
	// Sprawdzamy, czy poszczególne dane w tablicy sesji są ustawione
	// Jeśli nie to je "kasujemy"
	if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
	
        if (isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }
        
        if (isset($_SESSION['is_logged_in'])) {
	    // 0 oznacza, że użytkownik jest wylogowany
            $_SESSION['is_logged_in'] = 0;
        }
    }
    
    /*
     * Sprawdza, czy jest ktoś zalogowany w danej sesji
     * 
     * @return bool TRUE jeśli jest ktoś zalogowany; inaczej FALSE
     */
    public function session_is_logged() {
        if (isset($_SESSION['is_logged_in'])) {
	    // 1 oznacza, że użytkownik jest zalogowany
            if ($_SESSION['is_logged_in'] == 1) { 
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    /*
     * Pobiera z sesji nazwę użytkownika, który jest obecnie zalogowany
     * 
     * @return string Nazwa użytkownika; NULL jeśli nie jest nikt w danej sesji zalogowany
     */
    public function session_get_username() {
        if (isset($_SESSION['is_logged_in']) && isset($_SESSION['username'])) {
	    return $_SESSION['username'];
        }
        
        return NULL;
    }
    
    /*
     * Pobiera z sesji Id użytkownika, który jest obecnie zalogowany
     * 
     * @return integer Id użytkownika; NULL jeśli nie jest nikt w danej sesji zalogowany
     */
    public function session_get_user_id() {
        if (isset($_SESSION['is_logged_in']) && isset($_SESSION['user_id'])) {
	    return $_SESSION['user_id'];
        }
        
        return NULL;
    }
    
    /*
     * Uaktualnia dane w sesji na podstawie Id użytkownika(które nie ulega nigdy zmianie)
     * 
     * @return TRUE jeśli operacja się powiodła; FALSE w przeciwnym wypadku
     */
    public function session_update() {
	// Jeśli pewne zmienne sesj nie istnieją, to nie uaktualniamy jej i zwracamy FALSE
	if ( ! $this->session_is_logged()) {
	    return FALSE;
	}

	// Pobieramy dane z bazy danych na podstawie user_id, które dla danego użytkownika nigdy się nie zmienia
	$query = $this->db->get_where('users', array('user_id' => $this->session_get_user_id()));

	if ($query->num_rows() > 0) {
	    $row = $query->row();
	    
	    // Uaktualniamy odpowiednie dane
	    $_SESSION['username'] = $row->username;

	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}