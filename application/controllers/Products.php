<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Products extends CI_Controller
    {
        /*
            DOCU: This function set the specific page of user and admin
            Showing product dashboard.
            Owner: Ron Garcia Santos
        */
        public function index()
        {
            $products = $this->product->get_all_products();
            $product_list = array(
                'lists' => $products
            );
            
            $current_user_id = $this->session->userdata('user_id');
            if($current_user_id == 1){
                $this->load->view('templates/admin_header');
                $this->load->view('products/admin_dashboard', $product_list);
            }else{
                $this->load->view('templates/user_header');
                $this->load->view('products/admin_dashboard', $product_list);
            }
            
            
        }

        /*
            DOCU: Show the new product page if add new button triggered.
            Owner: Ron Garcia Santos
        */
        public function new()
        {
            $current_user_id = $this->session->userdata('user_id');
            if($current_user_id == 1){
                $this->load->view('templates/admin_header');
            }else{
                $this->load->view('templates/user_header');
            }
            
            $this->load->view('products/new');
        }

        /*
            DOCU: This function collect inputs from forms when clicked.
            Validates inputs and go to page depends on validation process.
            Owner: Ron Garcia Santos
        */

        public function create()
        {
            $product = $this->input->post('product_name');
            $result = $this->product->validate_registration($product);
            if($result != null){
                $this->session->set_flashdata('input_errors', $result);
                redirect('products/new');
            }else{
                $form_data = $this->input->post();
                $this->product->create_product($form_data);
                $this->session->set_flashdata('input_success', 'New Product created successfully!');
                redirect('/dashboard/admin');
            }
        }

        /*
            DOCU: Showing information of product specified.
            Owner: Ron Garcia Santos
        */
        public function show($id)
        {
            $current_user_id = $this->session->userdata('user_id');
            if($current_user_id == 1){
                $this->load->view('templates/admin_header');
            }else{
                $this->load->view('templates/user_header');
            }

            $product = $this->product->get_product($id);
            $this->load->view('products/show', $product);
        }

        /*
            DOCU: Show edit page when admin can edit product information specify.
            Owner: Ron Garcia Santos
        */
        public function edit($id)
        {
            $current_user_id = $this->session->userdata('user_id');
            if($current_user_id == 1){
                $this->load->view('templates/admin_header');
            }else{
                $this->load->view('templates/user_header');
            }

            $product = $this->product->get_product($id);
            $this->load->view('products/edit', $product);
            $this->output->enable_profiler();
        }

        /*
            DOCU: This function update information when save button is clicked.
            Owner: Ron Garcia Santos
        */
        public function update($id)
        {
            $result = $this->product->validate_product($this->input->post());
            if($result != null){
                $this->session->set_flashdata('input_errors', $result);
                redirect('products/edit/'. $id);
            }else{
                $product = $this->input->post();
                $this->product->update_product($product, $id);
                $this->session->set_flashdata('input_success', 'Product updated successfully!');
                redirect('/dashboard/admin');
            }
        }

        public function destroy($id)
        {
            $this->product->destroy_product($id);
            //needs prior confirmation box before deleting a product.
            $this->session->set_flashdata('input_success', 'Product remove successfully!');
            redirect('/dashboard/admin');
        }
    }
?>