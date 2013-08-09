<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * FAQ Plugin
 *
 * Create lists of faqs
 * 
 * @author		Tom Dickie
 */
class Plugin_Testimonials extends Plugin
{
	public function Testimonials()
	{

        $testimonials = $this->db
        ->get('testimonials_testimonials')
        ->result_array();

        /*
        $testimonials = $this->db
        ->get('testimonials_testimonials')
        ->where('featured', 'Yes')
        ->result_array();
        */
        return $testimonials;
	}

    public function Featured()
    {
        $testimonials = $this->db->
        get_where('testimonials_testimonials', array('featured' => 'Yes'))
        ->result_array();

        $output = "";

        foreach ($testimonials as $testimonial) {

            $output .= "<section class=\"wibble\">";
            $output .= "<h1>&ldquo;" . $testimonial['quote'] . "&rdquo;</h1>";
            $output .= "<p><span>" . $testimonial['company'] . "</span></p>";
            $output .= "<p>" . $testimonial['body'] . "</p>";
            $output .= "<p><a href=\"/testimonials\">View all testimonials</a></p>";
            $output .= '</section>';
        }

        return $output;
    }
}