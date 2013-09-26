<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * The galleries module enables users to create albums, upload photos and manage their existing albums.
 *
 * @author 		Yorick Peterse - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Gallery Module
 * @category 	Modules
 * @license 	Apache License v2.0
 */
class Gallery_m extends MY_Model {
        
    /**
     * Constructor method
     * 
     * @author PyroCMS Dev Team
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		
		$this->_table = 'galleries';
        $this->primary_key = 'id';
    }

	public function tot_galleries(){
	
		return $this->db->count_all_results('galleries');
	}

	/**
	 * Get all galleries along with the total number of photos in each gallery
	 *
	 * @author Yorick Peterse - PyroCMS Dev Team
	 * @access public
	 * @return mixed
	 */
	public function get_all()
	{
		$this->db
			->select('galleries.*, file_folders.slug as folder_slug, file_folders.name as folder_name, u.username, u.group_id, g.name group')
			->join('file_folders', 'file_folders.id = galleries.folder_id', 'left')
			->join('users u', 'u.id = galleries.user_id', 'left')
			->join('groups g', 'g.id = u.group_id', 'left');

		$galleries	= parent::get_all();

		$results	= array();

		// Loop through each gallery and add the count of photos to the results
		foreach ($galleries as $gallery)
		{
			$count = $this->db
				->select('files.id')
				->join('galleries', 'galleries.folder_id = files.folder_id', 'left')
				->where('files.type', 'i')
				->where('galleries.id', $gallery->id)
				->count_all_results('files');

			$gallery->photo_count = $count; 
			$results[] = $gallery;
		}
		// Return the results
		return $results;
	}

	/**
	 * Get all galleries along with the thumbnail's filename and extension
	 *
	 * @access public
	 * @return mixed
	 */
	public function get_all_with_filename($where = NULL, $value = NULL, $num = NULL, $offset = NULL)
	{
		$this->db
			->select('galleries.*, files.filename, files.extension, files.id as file_id, file_folders.parent_id as parent')
			
			->join('gallery_images', 'gallery_images.file_id = galleries.thumbnail_id', 'left')
			->join('files', 'files.id = gallery_images.file_id', 'left')
			->join('file_folders', 'file_folders.id = galleries.folder_id', 'left')
			->where('galleries.published', '1');

		// Where clause provided?
		if ( ! empty($where) AND ! empty($value))
		{
			$this->db->where($where, $value);
		}
		
		if( $num!=NULL or $offset!=NULL)
		{
			return $this->db->get('galleries',$num,$offset)->result();
		}			

		return $this->db->get('galleries')->result();
	}

	/**
	 * Insert a new gallery into the database
	 *
	 * @author PyroCMS Dev Team
	 * @access public
	 * @param array $input The data to insert (a copy of $_POST)
	 * @return bool
	 */
	public function insert_gallery($input, $skip_validation = false)
	{
		if (is_array($input['folder_id']))
		{
			$folder = $input['folder_id'];

			$input['folder_id'] = $this->file_folders_m->insert(array(
				'name'			=> $folder['name'],
				'parent_id'		=> 0,
				'slug'			=> $folder['slug'],
				'date_added'	=> now()
			));
		}

		return (int) parent::insert(array(
			'title'				=> $input['title'],
			'slug'				=> $input['slug'],
			'folder_id'			=> $input['folder_id'],
			'thumbnail_id'		=> ! empty($input['gallery_thumbnail']) ? (int) $input['gallery_thumbnail'] : NULL,
			'description'		=> $input['description'],
			'enable_comments'	=> $input['enable_comments'],
			'published'			=> $input['published'],
			'updated_on'		=> time(),
			'css'				=> $input['css'],
			'js'				=> $input['js']
		));
	}

	/**
	 * Update an existing gallery
	 *
	 * @author PyroCMS Dev Team
	 * @access public
	 * @param int $id The ID of the row to update
	 * @param array $input The data to use for updating the DB record
	 * @return bool
	 */
	public function update_gallery($primary_value, $input, $skip_validation = false){
		
	    $gal_data = array(
            'title'             => $input['title'],
            'slug'              => $input['slug'],
            'folder_id'         => $input['folder_id'],
            'description'       => $input['description'],
            'enable_comments'   => $input['enable_comments'],
            'thumbnail_id'      => ! empty($input['gallery_thumbnail']) ? (int) $input['gallery_thumbnail'] : NULL,
            'published'         => $input['published'],
            'updated_on'        => time(),
            'css'               => $input['css'],
            'js'                => $input['js']
        );
        return parent::update($id, $gal_data);
	}

	/**
	 * Callback method for validating the slug
	 * @access public
	 * @param string $slug The slug to validate
	 * @param int $id The id of gallery
	 * @return bool
	 */
	public function check_slug($slug = '', $id = 0)
	{
		return parent::count_by(array(
			'id !='	=> $id,
			'slug'	=> $slug)
		) > 0;
	}
	
	/**
	 * get all users galleries of a holiday
	 * 
	 * @author Kamal Lamichhane - lkamal.com.np
	 * @param int $holiday_id
	 * @return array of object
	 */
	public function get_gallery_by_holiday($holiday_id = 0)
	{
		//$this->load->model('holidays/holiday_m');
		$this->load->model('holidays/datenprice_m');
		$this->load->model('galleries/gallery_image_m');
		$this->load->library('files/files');
		
		$datenprices = $this->select('id')->datenprice_m->get_many_by('holiday', $holiday_id);
		
		$data = array();
		if(is_array($datenprices))
		{
			foreach($datenprices as $datenprice)
			{
				$gallery = $this->get_many_by('datenprice_id', $datenprice->id);
				if($gallery){
					foreach($gallery as $gal)
					{
						$images = $this->gallery_image_m->select('file_id')->get_many_by('gallery_id', $gal->id);
						if($images)
						{
							foreach($images as $img)
							{
								$file = Files::get_file($img->file_id);
								$gal->images[$img->file_id] = ($file['status'] === TRUE) ? $file['data']->name : NULL;
							}
						}
					}
					$data = $gallery;
				}
			}
		}
		return $data;
		
	}
}