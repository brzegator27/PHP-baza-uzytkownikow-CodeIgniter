<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Klasa Mainpage;
 * odpowiada za wyświetlanie strony głównej
 * 
 * @category pages
 * @author Jakub Brzegowski
 */
class Mainpage extends CI_Controller {
    
    /*
     * Wyświetla stronę główną; 
     * różną w zależności od tego, czy użytkownik jest zalogowany
     */
    public function index(){
        $data['title'] = 'Strona główna';
        
        $this->load->view('templates/header', $data);
	
        if ($this->user_model->session_is_logged()) {
	    // Jeśli użytkownik jest zalogowany:
            $this->load->view('mainpage/mainpage_logged');
        } else {
	    // Jeśli użytkownik nie jest zalogowany
            $this->load->view('mainpage/mainpage_unlogged', $data);
        }
	
        $this->load->view('templates/foot');
    }
}
