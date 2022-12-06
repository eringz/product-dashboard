<?php
    defined('BASEPATH') OR exit('No direct script access is allowed');

    class Review extends CI_Model
    {

        /*
            DOCU: This function is to validate review input from a user.
            Owner: Ron Garcia santos
        */
        function validate_review()
        {
            $this->form_validation->set_error_delimiters('<div>', '</div>');
            $this->form_validation->set_rules('review', 'Review', 'required');
            
            
            if(!$this->form_validation->run()){
                return validation_errors();
            }else{
                return 'success';
            }
        }

        /*
            DOCU: This function is to store review information.
            Owner: Ron Garcia Santos
        */
        function add_review($current_user_id, $review, $product_id)
        {
            $query = "INSERT INTO reviews(user_id, review, product_id, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW())";
            $values = array($current_user_id, $review, $product_id);
            return $this->db->query($query, $values);
        }

        /*
            DOCU: This function is to get the review information stored.
            Owner: Ron Garcia Santos
        */
        function get_reviews()
        {
            $query = "SELECT reviews.*, CONCAT(first_name, ' ', last_name) as username  FROM reviews
                        INNER JOIN products ON products.id = reviews.product_id
                        INNER JOIN users ON users.id = reviews.user_id
                        ORDER BY reviews.created_at DESC";
            return $this->db->query($query)->result_array();
        }

        
    }
?>