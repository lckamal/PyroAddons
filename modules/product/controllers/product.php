<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class product extends Public_Controller
{
	public $data;
    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		$this->data = new stdClass();
        $this->lang->load('product');
        $this->load->driver('Streams');
        $this->template->append_css('module::product.css');
        $this->load->model('product_m');
        $this->load->model('product_category_m');
        $this->load->library('form_validation');

        $this->template->set_layout('product.html');
    }
     /**
     * List all products
     *
     * @access	public
     * @return	void
     */

    public function index()
    {
        $params = array(
            'stream' => 'products',
            'namespace' => 'product',
            'paginate' => 'yes',
            'pag_segment' => 4,
            'where' => "product_status = 1"
        );        
        $this->data->products = $this->streams->entries->get_entries($params);
        //var_dump($this->data->products);exit;

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('index', $this->data);
    }

    public function cat($cat = null)
    {
        // $cat_slug = ($subcat_slug === null) ? $cat_slug : $subcat_slug;
        // $cat = $this->product_category_m->get_by('category_slug', $cat_slug);
        // $cat or show_404();

        // $subcats = $this->product_category_m->where('category_parent', $cat->id)->get_all();
        // if($subcats || $cat->category_parent == null){
        //     $view = 'cat_list';
        // }
        // else{
        //     $this->data->products = $this->product_m->where('product_category',$cat->id)->get_all();
        //     $view = 'product_list';
        // }
        // $this->data->category = $cat;
        // $this->data->subcats = $subcats;
        // $this->template->title($cat->category_title)
        //                ->build($view, $this->data);

        $params = array(
            'stream' => 'products',
            'namespace' => 'product',
            'paginate' => 'yes',
            'pag_segment' => 4,
            'where' => "product_status = 1"
        );
        if(!empty($cat)){
            $params['where'] .= " and `product_category` = {$cat}";
        }
        $this->data->products = $this->streams->entries->get_entries($params);
        //var_dump($this->data->products);exit;
        $category = $this->product_category_m->get_by('id',$cat);
        $this->data->catn = $category->category_title;
        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('index', $this->data);
    }

    
    public function view($product_slug = null)
    {
        $product = $this->product_m->get_by('product_slug', $product_slug);
        $product or show_404();

        $product = $this->streams->entries->get_entry($product->id, 'products', 'product');

        //get the gallery
        if(isset($product->product_gallery['id'])){
            class_exists('File_m') or $this->load->model('files/file_m');
            $product->product_gallery = $this->file_m->where('folder_id', $product->product_gallery['id'])->get_all();
        }
        $this->data->product = $product;

        $this->template->title($product->product_name)
            ->append_css('module::fancybox/jquery.fancybox.css')
            ->append_js('module::fancybox/jquery.fancybox.pack.js')
            ->append_js('module::gallery.js')
            ->build('product', $this->data);
    }


    public function manage_ad()
    {
        $this->template->title('Your categories')
        ->build('manage_ad', $this->data);
    }


    public function check($subcategory)
    {
        $all_category = $this->product_category_m->get_all();
        $merged_array = array();
        $array_length = count($subcategory);
        $i = 0;
        //var_dump($all_category);
        //print_r($subcategory);
        $count = 0;
        foreach($all_category as $category)
        {
            for($i = 0; $i<=$array_length - 1; $i++)
            {
                $my_array = (array)$category;
                if($subcategory[$i] == $my_array['id'])
                {
                    $count++;
                }
            }
        }

        

        $selection_made = $count;

        if($this->session->userdata('membership') == 3)
        {
            if($selection_made > 1)
            {
            $this->form_validation->set_message('check', 'You can only select subcategories from 1 category since you are a free member.');
            return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

        if($this->session->userdata('membership') == 4)
        {
            if($selection_made > 5)
            {
            $this->form_validation->set_message('check', 'You can only select subcategories from 5 category since you are a $25 member.');
            return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

        if($this->session->userdata('membership') == 5)
        {
            if($selection_made > 5)
            {
            $this->form_validation->set_message('check', 'You can only select subcategories from 5 category since you are a free member.');
            return FALSE; 
            }
            else
            {
                return TRUE;
            }
        }
    }


    public function create()
    {

	
        $this->data->all_categories = $this->product_category_m->get_all();   

       /* var_dump($this->data->my_all_categories);
        exit();*/


        $my_head_categories = $this->product_category_m->get_all_header();
        $this->data->my_head_categories = $my_head_categories;
        if(!empty($my_head_categories))
        {
        foreach($my_head_categories as $sub_categories)
        {
            $parent_id = $sub_categories->id;
            $sub_cat_c[] = $this->product_category_m->get_sub_categories($parent_id);
        }
        }
        else
        {
            $sub_cat_c = "";
        }
        $this->data->my_sub_categories = $sub_cat_c;

        $user_id = $this->current_user->id;  
        $count_res = $this->product_m->max_order_count();
        foreach($count_res as $order)
        {
            $max_count = $order->max + 1;
        }
        
        if($max_count == "")
        {
            $max_count = 1;
        }
        if($this->input->post())
        {
        /*if($_SERVER['HTTP_REFERER']!="http://localhost/ezone/sbsupportlink.com.au/solution/create")
        {
                redirect('product');
            }*/
            //this will check if the form has been submitted from the desired url.


            $this->form_validation->set_rules('subcategory','Sub Category','required|callback_check');
            $this->form_validation->set_rules('businessname','Business Name','required');
            $this->form_validation->set_rules('firstname','First Name','required');
            $this->form_validation->set_rules('lastname','Last Name','required');
            $this->form_validation->set_rules('telephone','Telephone','required');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('suffix','Suffix','required');
            $this->form_validation->set_rules('country','Country','required');
            $this->form_validation->set_rules('area_serviced','Do you service all of Australia','required');
            $this->form_validation->set_rules('post_code','numeric');
            
            $serialized_category = serialize($this->input->post('subcategory'));
  

            if($this->form_validation->run()==TRUE)
            {
                $data = array();
                $file = $_FILES['attachment']['name']; 
                if($file)
                {
                    $this->load->library('files/files');
                    $this->load->model('files/file_folders_m');
                    $folder_id = $this->file_folders_m->get_by('slug', 'attachments')->id;
                    $name= $file.now();
                    $uploaddata = Files::upload($folder_id, $name,'attachment');
                    if($uploaddata['status'] == true)   
                    $data['business_logo']=$uploaddata['data']['id'];
                }
                $data['user'] = $user_id;
                $data['created'] = strftime("%Y-%m-%d %H:%M:%S", time()); 
                $data['businessname'] = $this->input->post('businessname');
                $data['firstname'] = $this->input->post('firstname');
                $data['lastname'] = $this->input->post('lastname');
                $data['telephone'] = $this->input->post('telephone');
                $data['mobile'] = $this->input->post('mobile');
                $data['email_address'] = $this->input->post('email');
                $data['website'] = $this->input->post('website');
                $data['unit_no'] = $this->input->post('unit');
                $data['street_address'] = $this->input->post('street');
                $data['suffix'] = $this->input->post('suffix');
                $data['suburb'] = $this->input->post('suburb');
                $data['post_code'] = $this->input->post('postcode');
                $data['state'] = $this->input->post('state');
                $data['country'] = $this->input->post('country');
                $data['area_serviced'] = $this->input->post('area_serviced');
                $data['size_of_business'] = $this->input->post('size_of_business');
                $data['business_description'] = $this->input->post('business_description');
                $data['special_offer'] = $this->input->post('special_offer');
                $data['product_category_select'] = $serialized_category;
                if($this->product_m->insert($data))
                {
                    $this->session->set_flashdata('status', 'Your Business Listing Ad has been submitted.');
                    redirect('membership/mysb');
                }
                else
                {
                    $this->session->set_flashdata('status', 'Your Business Listing Ad could not be submitted.');
                    redirect('product/create');
                }
            }            
        }
        $this->template->title('Create Your Ad.')
        ->build('create', $this->data);
    }


    public function edit($id)
    {
        $my_product = $this->product_m->get_by('user',$this->current_user->id);
        $this->data->current_product = $my_product;

        
    
        $this->data->all_categories = $this->product_category_m->get_all();    
       /* var_dump($this->data->my_all_categories);
        exit();*/


        $my_head_categories = $this->product_category_m->get_all_header();
        $this->data->my_head_categories = $my_head_categories;
        if(!empty($my_head_categories))
        {
        foreach($my_head_categories as $sub_categories)
        {
            $parent_id = $sub_categories->id;
            $sub_cat_c[] = $this->product_category_m->get_sub_categories($parent_id);
        }
        }
        else
        {
            $sub_cat_c = "";
        }
        $this->data->my_sub_categories = $sub_cat_c;
        $user_id = $this->current_user->id;  

        if($this->input->post())
        {
        /*if($_SERVER['HTTP_REFERER']!="http://localhost/ezone/sbsupportlink.com.au/solution/create")
        {
                redirect('product');
            }*/
            //this will check if the form has been submitted from the desired url.


            $this->form_validation->set_rules('subcategory','Sub Category','required|callback_check');
            $this->form_validation->set_rules('businessname','Business Name','required');
            $this->form_validation->set_rules('firstname','First Name','required');
            $this->form_validation->set_rules('lastname','Last Name','required');
            $this->form_validation->set_rules('telephone','Telephone','required');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('suffix','Suffix','required');
            $this->form_validation->set_rules('country','Country','required');
            $this->form_validation->set_rules('area_serviced','Do you service all of Australia','required');
            $this->form_validation->set_rules('post_code','numeric');
            
            $serialized_category = serialize($this->input->post('subcategory'));

            //echo $serialized_category;

            if($this->form_validation->run()==TRUE)
            {
                $data = array();
                $file = $_FILES['attachment']['name']; 
                if($file)
                {
                    $this->load->library('files/files');
                    $this->load->model('files/file_folders_m');
                    files::delete_file($my_product->business_logo);  
                    $folder_id = $this->file_folders_m->get_by('slug', 'attachments')->id;
                    $name= $file.now();
                    $uploaddata = Files::upload($folder_id, $name,'attachment');
                    if($uploaddata['status'] == true)   
                    $data['business_logo']=$uploaddata['data']['id'];
                }
                $data['user'] = $user_id;
                $data['created'] = strftime("%Y-%m-%d %H:%M:%S", time()); 
                $data['businessname'] = $this->input->post('businessname');
                $data['firstname'] = $this->input->post('firstname');
                $data['lastname'] = $this->input->post('lastname');
                $data['telephone'] = $this->input->post('telephone');
                $data['mobile'] = $this->input->post('mobile');
                $data['email_address'] = $this->input->post('email');
                $data['website'] = $this->input->post('website');
                $data['unit_no'] = $this->input->post('unit');
                $data['street_address'] = $this->input->post('street');
                $data['suffix'] = $this->input->post('suffix');
                $data['suburb'] = $this->input->post('suburb');
                $data['post_code'] = $this->input->post('postcode');
                $data['state'] = $this->input->post('state');
                $data['country'] = $this->input->post('country');
                $data['area_serviced'] = $this->input->post('area_serviced');
                $data['size_of_business'] = $this->input->post('size_of_business');
                $data['business_description'] = $this->input->post('business_description');
                $data['special_offer'] = $this->input->post('special_offer');
                $data['product_category_select'] = $serialized_category;
                if($this->product_m->update($id,$data))
                {
                    $this->session->set_flashdata('status', 'Your Business Listing Ad has been updated.');
                    redirect('membership/mysb');
                }
                else
                {
                    $this->session->set_flashdata('status', 'Your Business Listing Ad could not be updated.');
                    redirect('product/edit/'.$id);
                }
            }            
        }
        $this->template->title('Edit Your Ad.')
        ->build('edit', $this->data);
    }


    public function delete()
    {
        $my_product = $this->product_m->get_by('user',$this->current_user->id);
        $product_id = $my_product->id;
        $attachment = $my_product->business_logo;

        /*echo $solution_id;
        echo "<br>";
        echo $attachment;
        die();*/
        $this->product_single($product_id);
        if($this->product_m->delete($product_id));
        {
            $this->load->library('files/files');
            files::delete_file($attachment);
            $this->session->set_flashdata('status', 'Your Business Listing has been deleted.');
            redirect('membership/mysb');
        }
    }

    public function view_product_by_category($subcategory_id)
    {
        //show all the product form product table with pagination.
    }

    public function view_single()
    {
        $current_user = $this->current_user->id;
        // show user's product
    }

    public function membership_account()
    {
        $this->template->title('Your categories')
        ->build('membership_account', $this->data);
    }
    public function product_views()
    {
        $this->template->title('products')
        ->build('products');
    }	

    public function view_single_adv() 
    { 
        $this->load->model('product_m');
        $current_user = $this->current_user->id; $this->data->adv = $this->product_m->get_by('user',$current_user); 
        $this->template->title('View product') 
        ->build('view_single_adv',$this->data); //show current user's solution. }
    }


    public function products($category_name)
    {
        $this->load->model('product_m');

        $products = $this->product_m->get_all();

        foreach($products as $product)
        {
            $unserialized = unserialize($product->product_category_select);
            if(in_array($category_name,$unserialized))
            {
            $the_id[] = $product->id;
            }
            else
            {
                $the_id = 0;
            }
        }

        if($the_id == 0)
        {
        $id_count = 0;
        $this->data->products = 0;
        }
        else
        {
        $id_count = count($the_id);
        for($i = 0; $i <=$id_count - 1; $i++)
        {
            $id = $the_id[$i];
            $this->data->products[] = $this->product_m->get($id);
        }

        }

        $this->template->title('View product') 
        ->build('products',$this->data);        
    }

    public function product_single($adv_id)
    {
        $query = $this->db->query("SELECT * FROM default_users WHERE id = 1");
        $res = $query->result();
        foreach($res as $re)
        {
              $email = $re->email;
        }
      
        $this->load->library('email');
        $this->load->model('product_m');
        $adv_single =  $this->product_m->get_by('id',$adv_id); 
        $adv_email = $adv_single->email_address;
        $this->data->adv_single = $adv_single;    

        if($this->input->post())
        {
            $this->form_validation->set_rules('word', 'Captcha', 'callback_captcha_check');
            $this->form_validation->set_rules('business_message','Business Message','required');
            $this->form_validation->set_rules('business_phone','Business Phone No','required');
            $this->form_validation->set_rules('business_email','Business Email','required|valid_email');

            if($this->form_validation->run()===TRUE)
                {
                    $business_message = $this->input->post('business_message');
                    $business_phone = $this->input->post('business_phone');
                    $business_email = $this->input->post('business_email');

                    $this->email->from($email, 'Subsupportlink');
                    
                    $this->email->subject('A request for business listing.');
                    $message = "Hello You have recieved a request for your business product.";
                    $message .= "Business Message : {$business_message}";
                    $message .= "Business Phone : {$business_phone}";
                    $message .= "Business Email : {$business_email}";
                    $this->email->message($message);
                    if($this->input->post('confirm') == "confirm")
                    {
                    $this->email->to($adv_email);
                    }
                    else
                    {
                    $this->email->to($adv_email, $email);
                    }

                    if($this->email->send())
                    {
                        $this->session->set_flashdata('status','Your Request has been made.');
                        $site_url = site_url();
                        redirect($site_url.'product/product_single/'.$adv_id);
                    }
                    else
                    {
                        $this->session->set_flashdata('status','Your Request could not be made.');
                        $site_url = site_url();
                        redirect($site_url.'product/product_single/'.$adv_id);
                    }

                }
            {
                $this->load->helper('captcha');
                           // load codeigniter captcha helper
                $this->load->helper('captcha');
 
                $captcha_path = base_url()."addons/shared_addons/helpers/captcha/";

                $img_path = './addons/shared_addons/helpers/captcha/';

                $vals = array(
                'img_path'     => $img_path,
                'img_url'     => $captcha_path,
                'img_width'     => '200',
                'img_height' => 30,
                'border' => 0, 
                'expiration' => 7200
                );
    
                 // create captcha image
                $cap = create_captcha($vals);

                // store image html code in a variable
                $this->data->image = $cap['image'];
              
               // store the captcha word in a session
                $this->session->set_userdata('word', $cap['word']); 
    }
    
        }
        else
        {
                $this->load->helper('captcha');
                           // load codeigniter captcha helper
                $this->load->helper('captcha');
 
                $captcha_path = base_url()."addons/shared_addons/helpers/captcha/";

                $img_path = './addons/shared_addons/helpers/captcha/';

                $vals = array(
                'img_path'     => $img_path,
                'img_url'     => $captcha_path,
                'img_width'     => '200',
                'img_height' => 30,
                'border' => 0, 
                'expiration' => 7200
                );

                 // create captcha image
                $cap = create_captcha($vals);

                // store image html code in a variable
                $this->data->image = $cap['image'];
                              
               // store the captcha word in a session
                $this->session->set_userdata('word', $cap['word']); 
    }

        $this->template->title('View product') 
        ->build('product_single',$this->data);
    }


    public function adv_single($adv_id)
    {

        $query = $this->db->query("SELECT * FROM default_products WHERE user = $adv_id");
         $res = $query->result();

         
       /* foreach($res as $re)
        {
              $email = $re->email;
        }*/
      
        //$this->load->library('email');
        //$this->load->model('product_m');
        $adv_single = $res; 
        
        //$adv_email = $adv_single->email_address;
        $this->data->adv_singles = $adv_single;  

        $this->load->helper('captcha');
        // load codeigniter captcha helper
    
 
        $captcha_path = base_url()."addons/shared_addons/helpers/captcha/";

        $img_path = './addons/shared_addons/helpers/captcha/';

        $vals = array(
                'img_path'     => $img_path,
                'img_url'     => $captcha_path,
                'img_width'     => '200',
                'img_height' => 30,
                'border' => 0, 
                'expiration' => 7200
        );
    
                 // create captcha image
                $cap = create_captcha($vals);

                // store image html code in a variable
                $this->data->image = $cap['image'];                

        $this->template->title('View product') 
        ->build('product_single_user',$this->data);
    }


    public function captcha_check($captcha)
    {
        $uccaptcha = strtoupper($captcha);
        $ucsession = strtoupper($this->session->userdata('word'));
/*        echo $uccaptcha;
        echo "<br>";
        echo $ucsession;
        die();
*/
        if($uccaptcha == $ucsession)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('captcha_check', 'Your Captcha field is incorrect. Please try again.');
            return false;
        }
    }



    public function list_categories($category_slug)
    {
        $category_id = $this->product_category_m->get_by('category_slug', $category_slug);
        $category = $this->product_category_m->get($category_id->id);
        $this->data->category = $category;
        $product_by_category = $this->product_category_m->get_many_by('category_parent_id', $category->id);
        
        if(empty($product_by_category))
        {
            $redirect_url = base_url().'product/list_product/'.$category_id->id;
            redirect($redirect_url);
        }

        $this->load->model('categoryslider/categoryslider_m');
        $slider_image = $this->categoryslider_m->get_many_by('category', $category->id);
        $this->data->slider_image = $slider_image;


        $this->data->product_by_category = $product_by_category;
        $this->data->parent_category = $this->product_category_m->get($category->id);

        $this->template->title('View'.$category->product_category_title.'') 
        ->build('list_category.php',$this->data);
    }


    public function list_product($id)
    {
        $categories = $this->product_category_m->get($id);
        $this->data->product_by_category = $this->product_m->get_many_by('product_category_select', $id);
        $this->data->parent_category = $categories;
        /*var_dump($categories);
        var_dump($this->data->product_by_category);*/

        $this->template->title('View'.$categories->product_category_title.'') 
        ->build('list_product.php',$this->data);
    }

    public function list_product_single($id)
    {
        $this->data->product = $this->product_m->get($id);
        $this->template->title('Your Preffered Product.') 
        ->build('list_product_single.php',$this->data);
    }



/*
        public function new_captcha()
    {
        $this->load->helper(array('captcha', 'file'));
        $captcha = create_captcha(array(
            'word'        => strtoupper(substr(md5(time()), 0, 6)),
            'img_path'    => $this->captcha_path,
            'img_url'    => $this->captcha_path
        ));
        $this->session->set_userdata('captcha', $captcha['word']);
        $filename = $this->captcha_path . $captcha['time'] . '.jpg';
        $this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: image/jpeg');
        $this->output->set_header('Content-Length: ' . filesize($filename));
        echo read_file($filename);
    }*/


    public function send_mail()
    {
       
    }
}

/* End of file product.php */
