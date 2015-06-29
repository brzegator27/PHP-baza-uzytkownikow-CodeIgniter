<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Klasa Logging;
 * odpowiada za wyświetlanie formularza logowania
 * 
 * @category user_account
 * @author Jakub Brzegowski
 */
class Logging extends CI_Controller {

    /*
     * Wyświetla stronę z formularzem logowania
     * Jeśli logowanie się powiodło wyświetla widok logging_successful
     */
    public function logging_in() {
	// Ustawiamy tytuł strony
        $data['title'] = 'Logowanie';
        
	// Ustawiamy reguły walidacji dla pola username
        $this->form_validation->set_rules('username', 'login', 'required');
	
	// Tablica z regułami walidacji dla pola password
	$config = array(
	    array(
		'field' => 'password',
		'lbael' => 'hasło',
		'rules' => 'required|callback_check_password_validity',
		'errors' => array(
		    'check_password_validity' => 'Podane hasło jest niepoprawne, lub użytkownik o podanym loginie nie istnieje.',
		),
	    ),
	);
	
	$this->form_validation->set_rules($config);
        
        $this->load->view('templates/header', $data);
	
        if ($this->user_model->session_is_logged()) {
	    // Jeśli jest zalogowany:
            $this->load->view('logging/logging_logged');
        } elseif ($this->form_validation->run() === FALSE) {
	    // Jeśli przesłano źle wypełniony formularz
            $this->load->view('logging/logging_form', $data);
        } else {
	    // Wyświetlamy odpowiedni widok w zależności od powodzenia logowania
            $this->user_model->log_in() ?   $this->load->view('logging/logging_successful') :
					    $this->load->view('logging/logging_form', $data);
        }
        
        $this->load->view('templates/foot');       
    }
    
    /*
     * Wylogowuje użytkownika i wyświetla widok logging_out
     */
    public function logging_out() {
        $this->user_model->session_log_out();
        
        $data['title'] = 'Wylogowywanie';
        
        $this->load->view('templates/header', $data);
        $this->load->view('logging/logging_out', $data);
        $this->load->view('templates/foot');
    }
    
    /*
     * Sprawdza poprawność hasła danego użytkownika, który próbuje się zalogować
     * 
     * @param string $passed_data Hasło podane przez użytkownika w formularzu logowania
     * 
     * @return bool TRUE jeśli hasło jest poprawne; inaczej FALSE
     */
    public function check_password_validity($passed_data) {
	// Hasło sprawdzamy dla loginu podanego podczas logowania
	return $this->user_model->check_password($passed_data, '', $this->input->post('username'));
    }
}
