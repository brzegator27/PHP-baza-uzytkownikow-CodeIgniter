<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Klasa Registration;
 * odpowiada za wyświetlanie formularza rejestracji użytkownika
 * 
 * @category user_account
 * @author Jakub Brzegowski
 */
class Registration extends CI_Controller {

    /*
     * Wyświetla formularz rejestracji
     * Jeśli rejestracja przebiegła pomyślnie wyświetla stosowny widok
     */
    public function index() {
        $data['title'] = 'Rejestracja';
	
	/*
	 * tablica zawierająca ustawienia sposobu walidacji przekazywanych danych
	 */
	$config = array(
	    array(
		'field' => 'username',
		'lbael' => 'loing',
		'rules' => 'trim|required|alpha_numeric|max_length[30]|callback_check_if_username_exists',
		'errors' => array(
		    'check_if_username_exists' => 'Użytkownik o takim loginie już istnieje, podaj inny.'
		),
	    ),
	    array(
		'field' => 'first_name',
		'lbael' => 'imię',
		'rules' => 'trim|required|alpha|max_length[45]',
	    ),
	    array(
		'field' => 'last_name',
		'lbael' => 'nazwisko',
		'rules' => 'trim|required|alpha|max_length[45]',
	    ),
	    array(
		'field' => 'address',
		'lbael' => 'adres',
		'rules' => 'required|alpha_numeric_spaces|max_length[45]',
	    ),
	    array(
		'field' => 'password',
		'lbael' => 'hasło',
		'rules' => 'required|trim|max_length[30]',
	    ),
	);
        
	// Ustawiamy reguły walidacji:
        $this->form_validation->set_rules($config);
        
        $this->load->view('templates/header', $data);
        
        if ($this->user_model->session_is_logged()) {
	    // Jeśli użytkonik jest zalogowany, nie musi się już rejestrować
            $this->load->view('registration/registration_logged');
        } elseif($this->form_validation->run() == FALSE) {
	    // Jeśli formularz został wypełniony niepoprawnie
            $this->load->view('registration/registration_form', $data);
        } else {
	    // Próbujemy utworzy nowe konto użytkownika
            $result = $this->user_model->create_user();
            
	    // W zależności od powodzenia operacji wyświetlamy odpowiedni widok
            $result ? $this->load->view('registration/registration_successful', $data) : 
                      $this->load->view('registration/registration_form', $data);
        }
        
        $this->load->view('templates/foot');       
    }
    
    /*
     * Sprawdza, czy w bazie danych istnieje już dany login
     * 
     * @param string $passed_data Login, który chciałby mieć użytkownik
     * @return bool TRUE jeśli login jest wolny; inaczej FALSE
     */
    public function check_if_username_exists($passed_data) {
        return ! $this->user_model->look_for_the_same($passed_data, 'username');
    }
}