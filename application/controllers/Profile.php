<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Klasa Profile;
 * odpowiada za wyświetlanie danych użytkownika, jak i ich zmianę
 * również usuwanie konta
 * 
 * @category user_account
 * @author Jakub Brzegowski
 */
class Profile extends CI_Controller {
    
    /*
     * TRUE jeśli użytkownik jest zalogowany; inaczej FALSE
     */
    private $is_logged;
    
    /*
     * Kontroler;
     * Sprawdzamy, czy użytkownik jest zalogowany
     */
    public function __construct() {
	parent::__construct();
	
	// Sprawdzamy, czy użytkownik jest zalogowany i ustawiamy odpowiednią zmienną
	$this->is_logged = $this->user_model->session_is_logged();
    }

    /*
     * Wyświetla stronę /profile/show_profile
     */
    public function index() {
	$this->show_profile();
    }
    
    /*
     * Wyświetla stronę profilu dla użytkownika, który nie jest zalogowany
     * 
     * @access private
     */
    private function show_profile_unlogged() {
	$data['title'] = 'Dane profilowe';
	$this->load->view('templates/header', $data);
	$this->load->view('profile/profile_unlogged');
	$this->load->view('templates/foot');
    }
    
    /*
     * Wyświetla stronę profilu dla użytkownika, który jest zalogowany
     * Sprawdza przed wyświetleniem, czy jest on zalogowany
     */
    public function show_profile() {
	// Sprawdzamy, czy użytkownik jest zalogowany
	if ( ! $this->is_logged) {
	    $this->show_profile_unlogged();
	    
	    return;
	}
	
	$data['title'] = 'Profil '.$this->user_model->session_get_username();
	
	// Pobieramy z bazy danych dane profilowa użytkownika, który jest zalogowany
	$data['userdata'] = $this->user_model->get_userdata();
	
	$this->load->view('templates/header', $data);
	$this->load->view('profile/profile_logged', $data);
        $this->load->view('templates/foot');
    }
    
    /*
     * Wyświetla stronę odpowiedzialną za zmianę danych profilowych użytkownika
     */
    public function change_profile() {
	/*
	 * tablica zawierająca ustawienia sposobu walidacji przekazywanych danych
	 */
	$config = array(
	    array(
	    'field' => 'username',
	    'lbael' => 'loing',
	    'rules' => 'trim|alpha_numeric|max_length[30]|callback_check_username_validity',
	    'errors' => array(
		'check_username_validity' => 'Użytkownik o takim loginie już istnieje, podaj inny.'
		),
	    ),
	    array(
	    'field' => 'first_name',
	    'lbael' => 'imię',
	    'rules' => 'trim|alpha|max_length[45]',
	    ),
	    array(
	    'field' => 'last_name',
	    'lbael' => 'nazwisko',
	    'rules' => 'trim|alpha|max_length[45]',
	    ),
	    array(
	    'field' => 'address',
	    'lbael' => 'adres',
	    'rules' => 'alpha_numeric_spaces|max_length[45]',
	    ),
	    array(
	    'field' => 'password_old',
	    'lbael' => 'hasło',
	    'rules' => 'required|trim|max_length[30]|callback_check_password_validity',
	    'errors' => array(
		'check_password_validity' => 'Podane stare hasło jest niepoprawne.',
		),
	    ),
	    array(
	    'field' => 'password',
	    'lbael' => 'hasło',
	    'rules' => 'trim|max_length[30]',
	    ),
	);
	
	// Sprawdzamy, czy użytkownik jest zalogowany
	if ( ! $this->is_logged) {
	    $this->show_profile_unlogged();
	    
	    return;
	}
	
	$data['title'] = 'Zmiana danych profilowych';
        
	// Ustawiamy reguły walidacji
        $this->form_validation->set_rules($config);
        
        $this->load->view('templates/header', $data);
        
	if($this->form_validation->run() == FALSE) {
	    // Jeśli formularz jest wypełniony niepoprawnie
            $this->load->view('profile/profile_change', $data);
        } else {
	    // Uaktualniamy dane użytkownika
            $result = $this->user_model->update_userdata();
            
            if ($result) {
		// Jeśli się udało, to pobieramy dane profilowe i wyświetlamy stronę profilu użytkownika
		$data['userdata'] = $this->user_model->get_userdata();
		$this->load->view('profile/profile_logged', $data);
	    } else {
		// Jeśli się NIE udało ładujemy formularz jeszcze raz
		$this->load->view('profile/profile_change', $data);
	    }           
        }
        
        $this->load->view('templates/foot');
    }
    
    /*
     * Generuje stronę do usuwania konta użytkownika
     */
    public function delete_user() {
	// Sprawdzamy, czy użytkownik jest zalogowany
	if ( ! $this->is_logged) {
	    $this->show_profile_unlogged();
	    
	    return;
	}
	
	/*
	 * tablica zawierająca ustawienia sposobu walidacji przekazywanych danych
	 */
	$config = array(
	    array(
	    'field' => 'password',
	    'lbael' => 'hasło',
	    'rules' => 'required|trim|max_length[30]|callback_check_password_validity',
	    'errors' => array(
		'check_password_validity' => 'Podane hasło jest niepoprawne.',
		),
	    ),
	);
	
	$data['title'] = 'Usuwanie konta użytkownika';
        
	// Ustawiamy reguły walidacji:
        $this->form_validation->set_rules($config);
        
        $this->load->view('templates/header', $data);
        
	if($this->form_validation->run() == FALSE) {
	    // Gdy formularz nie został poprawenie wypełniony 
            $this->load->view('profile/profile_delete', $data);
        } else {
	    // Usuwamy konto
            $result = $this->user_model->delete_user();
            
            if ($result) {
		// Jeśli udało się go usunąć przechodzimy na stronę główną
		$this->load->view('/mainpage', $data);
	    } else {
		// Jeśli się NIE udało usunąć konta, wyświetlamy stronę jeszcze raz
		$this->load->view('profile/profile_delete', $data);
	    }           
        }
        
        $this->load->view('templates/foot');	
    }
    
    /*
     * Sprawdza, czy użytkownik może zmienić login na podany
     * Jeśli nowy login jest taki sam, jak stary, lub jest różny od tych
     * znajdujących się w bazie danych, to może go zmienić
     * 
     * @param string $passed_data Nowa nazwa użytkownika przekazana przez formularz
     * @return bool TRUE gdy login jest wolny; inaczej FALSE
     */
    public function check_username_validity($passed_data) {
	// Sprawdzamy, czy jest to login zalogowango użytkownika
	if ($this->user_model->session_get_username() == $passed_data) {
	    return TRUE;
	}
	
	// Sprawdzamy, czy w bazie danych istnieje już taki login
	$result = $this->user_model->look_for_the_same($passed_data, 'username');
	
	return ! $result;
    }
    
    /*
     * Sprawdza czy podane hasło zgadza się z tym z bazy danych
     * 
     * @param string $passed_data Hasło przekazane do formulrza
     * @return bool Zwraca prawdę, jeśli hasła się zgadzają
     */
    public function check_password_validity($passed_data) {
	// Sprawdzamy, czy przekazane hasło jest poprawne
	return $this->user_model->check_password($passed_data);
    }
}