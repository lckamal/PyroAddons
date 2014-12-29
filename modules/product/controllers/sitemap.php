<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap extends Public_Controller
{

     /**
     * team sitemap
     */
     public function xml()
    {
        class_exists('product_m') or $this->load->model('product_m');
        $all_items = $this->pyrocache->model('product_m', 'get_active_products', array(), 360);

        $doc = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />');

        //Fetch all the item
        if($all_items)
        {
	        foreach($all_items as $item)
	        {
	            $node = $doc->addChild('url');

	            //This will create a link to the item usign the slug
	            $loc = site_url('product/'.$item->product_slug);

	            $node->addChild('loc', $loc);

	            $node->addChild('lastmod', date(DATE_W3C, strtotime($item->created)));

	        }
        }

        $this->output
            ->set_content_type('application/xml')
            ->set_output($doc->asXML());
    }
}

/* End of file product.php */
