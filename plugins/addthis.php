<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author		kamal lamichhane
 */
class Plugin_Addthis extends Plugin
{

	/**
	 * different styles of addthis share buttons
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'share' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Sharing things through addthis!'
				),
				'single' => true,
				'double' => false,
				'variables' => 'style|buttons',
				'attributes' => array(
					'style' => array(
						'type' => 'text',
						'flags' => 'default|32x32|16x16|bookmark|floating_counter|floating_32x32|floating_16x16',
						'default' => '',
						'required' => true,
					),
				),
			),
		);
	
		return $info;
	}

	function buttons() {
		
		$style = $this->attribute('style', 'default');
		
		switch($style)
		{
			case "default":
				$plugin = '<div class="addthis_toolbox addthis_default_style">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
					<a class="addthis_counter addthis_pill_style"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>';
			break;
			case "32x32":
				$plugin = '<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
					<a class="addthis_button_facebook"></a>
					<a class="addthis_button_twitter"></a>
					<a class="addthis_button_google_plusone_share"></a>
					<a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>';
			break;
			case "16x16":
				$plugin = '<div class="addthis_toolbox addthis_default_style addthis_16x16_style">
					<a class="addthis_button_facebook"></a>
					<a class="addthis_button_twitter"></a>
					<a class="addthis_button_google_plusone_share"></a>
					<a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>';
			break;
			case "bookmark":
				$plugin = '<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=ra-4e43f9c5158e188b"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
					<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e43f9c5158e188b"></script>';
			break;
			case "floating_counter":
				$plugin = '<div class="addthis_toolbox addthis_floating_style addthis_counter_style" style="left:50px;top:50px;">
					<a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
					<a class="addthis_button_tweet" tw:count="vertical"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
					<a class="addthis_counter"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>';
			break;
			case "floating_32x32":
				$plugin = '<div class="addthis_toolbox addthis_floating_style addthis_32x32_style">
					<a class="addthis_button_facebook"></a>
					<a class="addthis_button_twitter"></a>
					<a class="addthis_button_google_plusone_share"></a>
					<a class="addthis_button_compact"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>';
			break;
			case "floating_16x16":
				$plugin = '<div class="addthis_toolbox addthis_floating_style addthis_16x16_style">
					<a class="addthis_button_facebook"></a>
					<a class="addthis_button_twitter"></a>
					<a class="addthis_button_google_plusone_share"></a>
					<a class="addthis_button_compact"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>';
			break;
		}
		
		return $plugin;
	}

}
