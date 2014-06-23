<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author		Paul Thickett
 */
class Plugin_Social extends Plugin
{

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'twitter' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Sharing things or twitte some posts!'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => 'button|text|url|via|lang|size|count',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'button' => array(// this is the name="World" attribute
						'type' => 'text',// Can be: slug, number, flag, text, array, any.
						'flags' => 'share|follow|mention',// flags are predefined values like asc|desc|random.
						'default' => '',// this attribute defaults to this if no value is given
						'required' => true,// is this attribute required?
					),
					'text' => array(
						'type' => 'text',
						'flags' => '',
						'default' => '',
						'required' => false
					),
					'via' => array(
						'type' => 'text',
						'flags' => '@mention',
						'default' => 'lckamal',
						'required' => false
					),
					'url' => array (
						'type' => 'text',
						'flags' => 'Username'
					)
				),
			),
			'facebook' => array(
				'description' => array(
					'en' => 'Facebook plugin to share feeds'
				),
				'single' => true,
				'double' => false,
				'variables' => 'appId|send|href|faces|width|button|colorsheme',
				'attributes' => array(
					'appId' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '160205314138582',
						'required' => false
					),
					'button' => array(
						'type' => 'text',
						'flags' => 'like|send|follow|comments',
						'default' => 'like',
						'required' => true
					),
					'colorsheme' => array(
						'type' => 'text',
						'flags' => 'black|white',
						'default' => 'white',
						'required' => false
					)
				)
			),
			'gplus' => array(
				'description' => array(
					'en' => 'Google plug plugin to share feeds'
				),
				'single' => true,
				'double' => false,
				'variables' => 'href|size|annotation|width|align|recommendations|count',
				'attributes' => array(
					'href' => array(
						'type' => 'text',
						'flags' => '',
						'default' => 'http://www.lkamal.com.np',
						'required' => false
					),
					'size' => array(
						'type' => 'text',
						'flags' => 'small|medium|standard|tall',
						'default' => 'medium',
						'required' => true
					),
					'annotation' => array(
						'type' => 'text',
						'flags' => 'none|bubble|inline',
						'default' => 'bubble',
						'required' => false
					),
					'width' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => false
					),
					'align' => array(
						'type' => 'text',
						'flags' => 'left|right',
						'default' => 'left',
						'required' => false
					),
					'recommendations' => array(
						'type' => 'text',
						'flags' => 'true|false',
						'default' => 'true',
						'required' => false
					),
					'count' => array(
						'type' => 'text',
						'flags' => 'true|false',
						'default' => 'true',
						'required' => false
					)
				)
			)
		);
	
		return $info;
	}

	function twitter() {

		$button = ($this->attribute('button')!='') ? $this->attribute('button') : 'share';
		$text = ($this->attribute('text')!='') ? $this->attribute('text') : '';
		$url = ($this->attribute('url')!='') ? $this->attribute('url') : '';
		$via = ($this->attribute('via')!='') ? $this->attribute('via') : 'lckamal';
		$lang = ($this->attribute('size')!='') ? $this->attribute('lang') : 'en';
		$size = ($this->attribute('size')=='large') ? $this->attribute('size') : '';
		$count = ($this->attribute('count')=='none') ? $this->attribute('count') : '';

		switch($button) {
			case 'share':
				$plugin = '
					<a
						href="https://twitter.com/share"
						class="twitter-share-button"
						data-url="'.$url.'"
						data-text="'.$text.'"
						data-via="'.$via.'"
						data-lang="'.$lang.'"
						data-size="'.$size.'"
						data-count="'.$count.'"
						>
						Twittear
					</a>
					';
				break;
			case 'follow':
				$plugin = '
					<a
						href="https://twitter.com/'.$via.'"
						class="twitter-follow-button"
						data-show-count="false"
						data-lang="'.$lang.'"
						data-size="'.$size.'"
						>
						Twittear
					</a>
					';
				break;
			case 'mention':
				$plugin ='
					<a
						href="https://twitter.com/intent/tweet?screen_name='.$via.'"
						class="twitter-mention-button"
						data-lang="'.$lang.'"
						data-size="'.$size.'"
						data-related="'.$via.'"
						>
						Tweet to @'.$via.'
					</a>
					';
					break;
		}
		$plugin .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
			return $plugin;
	}

	function facebook() {
		$appId = ($this->attribute('appId')!='') ? $this->attribute('appId') : '160205314138582';

		$href = ($this->attribute('href')!='') ? $this->attribute('href') : '';
		$send = ($this->attribute('send')!='') ? $this->attribute('send') : 'true';
		$width = ($this->attribute('width')!='') ? $this->attribute('width') : '450';
		$faces = ($this->attribute('faces')!='') ? $this->attribute('faces') : 'false';
		$colorscheme = ($this->attribute('colorscheme')!='') ? $this->attribute('colorscheme') : 'light';
		$button = $this->attribute('button') ? $this->attribute('button') : 'like';
		$layout = $this->attribute('layout') ? $this->attribute('layout') : 'button_count';

		if( ! in_array($colorscheme,array('light','dark')))
		{
			$colorscheme = 'light';
		}

		if ( ! in_array($button, array('like','follow','comments', 'send')))
		{
			$button = 'like';
		}

		if ( ! in_array($layout, array('button_count','box_count','standard')))
		{
			$layout = 'standard';
		}
		
		switch ($button) {
			case 'like':
				$plugin = '<div class="fb-like" data-href="'.$href.'" data-send="'.$send.'" data-width="'.$width.'" data-show-faces="'.$faces.'" data-layout="'.$layout.'"></div>';
				break;
			
			case 'send':
				$plugin = '<div class="fb-send" data-href="'.$href.'"></div>';
				break;

			case 'follow':
				$plugin = '<div class="fb-follow" data-href="'.$href.'" data-show-faces="'.$faces.'" data-layout="'.$layout.'" data-width="'.$width.'"></div>';
				break;

			case 'comments':
				$plugin = '<div class="fb-comments" data-href="'.$href.'" data-width="'.$width.'" data-num-posts="'.$width.'"></div>';
				break;

			default:
				# code...
				break;
		}


		$plugin ='<div id="fb-root"></div>
			<script>(function(d, s, id) {
			    var js, fjs = d.getElementsByTagName(s)[0];
			    if (d.getElementById(id)) return;
			    js = d.createElement(s); js.id = id;
			    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId='.$appId.'";
			    fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>' . $plugin;

		return $plugin;
	}
	
	/**
	 * google plus share button plugin
	 * 
	 * @author lkamal.com.np - lckamal
	 * @return string $plugin
	 */
	function gplus() {
		$href = ($this->attribute('href')!='') ? $this->attribute('href') : '';
		$size = ($this->attribute('size')!='') ? $this->attribute('size') : 'medium';
		$annotation = ($this->attribute('annotation')!='') ? $this->attribute('annotation') : 'bubble';
		$width = ($this->attribute('width')!='') ? $this->attribute('width') : '';
		$align = ($this->attribute('align')!='') ? $this->attribute('align') : 'left';
		$recommendations = ($this->attribute('recommendations')!='') ? $this->attribute('recommendations') : 'true';
		$count = ($this->attribute('count')=='none') ? $this->attribute('count') : '';
		$lang = ($this->attribute('lang')!='') ? $this->attribute('lang') : 'en-US';
		
		$plugin = '<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" 
				data-size="'.$size.'" 
				data-href="'.$href.'" 
				data-annotation="'.$annotation.'" 
				data-width="'.$width.'" 
				data-recommendations="'.$recommendations.' 
				data-count="'.$count.'>
			</div>
			
			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			window.___gcfg = {
			    lang: \'en-US\',
			    parsetags: \'onload\'
			  };
			  
			  (function() {
			    var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
			    po.src = \'https://apis.google.com/js/plusone.js\';
			    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>';
			
			return $plugin;
	}

}
