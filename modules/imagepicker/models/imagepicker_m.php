<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * faq module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  faq Module
 */
class Imagepicker_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'files';
        $this->primary_key = 'id';
        class_exists('file_m') or $this->load->model('files/file_m');
        class_exists('file_folders_m') or $this->load->model('files/file_folders_m');
	}
	
    /**
     * Get all folders and files within a folder
     *
     * @param   int     $parent The id of this folder
     * @return  array
     *
    **/
    public static function folder_contents($parent = 0, $type = null)
    {
        // they can also pass a url hash such as #foo/bar/some-other-folder-slug
        if ( ! is_numeric($parent))
        {
            $segment = explode('/', trim($parent, '/#'));
            $result = ci()->file_folders_m->get_by('slug', array_pop($segment));

            $parent = ($result ? $result->id : 0);
        }

        $folders = ci()->file_folders_m->where('parent_id', $parent)
            ->where('hidden', 0)
            ->order_by('sort')
            ->get_all();

        // $files = ci()->file_m->where(array('folder_id' => $parent))
        //     ->order_by('sort')
        //     ->get_all();
        $files = ci()->file_m->where(array('folder_id' => $parent, 'type' => $type))
            ->order_by('sort')
            ->get_all();

        // let's be nice and add a date in that's formatted like the rest of the CMS
        if ($folders)
        {
            foreach ($folders as &$folder) 
            {
                $folder->formatted_date = format_date($folder->date_added);

                $folder->file_count = ci()->file_m->count_by('folder_id', $folder->id);
            }
        }

        if ($files)
        {
            ci()->load->library('keywords/keywords');

            foreach ($files as &$file) 
            {
                $file->keywords_hash = $file->keywords;
                $file->keywords = ci()->keywords->get_string($file->keywords);
                $file->formatted_date = format_date($file->date_added);
            }
        }

        return Files::result(true, null, null, array('folder' => $folders, 'file' => $files, 'parent_id' => $parent));
    }
}
