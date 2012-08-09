<?php
/**
 * oEmbed Parser
	by: Brandon Runyon
	date: 8/8/2012
	license: GPL v.3.0
 */
class oEmbedParser {
	
	private $_url;
	private $_response;
	private $_endpoint;
	private $_format;
	private $_provider;
	
	//well known provider dictionary
	private $_providers = array(
		'youtube' => array(
			'url' => 'http://www.youtube.com/',
			'endpoint' => 'http://www.youtube.com/oembed'
		) ,
		'vimeo' => array(
			'url' => 'http://vimeo.com/',
			'endpoint' => 'http://vimeo.com/api/oembed.'
		)
	);
	
	
	//constructor, can take the url optionally, can be over ridden by method setURL($url)
	public function __construct($argument = null) {
		if ($argument !== null) {
			$this->_url = $argument;
		}
		return $this;
	}
	
	
	private function _encode($provider, $url) {
		$api = $this->_providers[$provider]['endpoint'];
		$encodedURL = urlencode($url);
		switch (strtolower($provider)) {
			case 'youtube':
				//match and break up url
				preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $encodedURL);
				$endpoint = $api . '?url=' . $encodedURL[0] . '&format=' . $this->_format;
			break;
			case 'vimeo':
				$endpoint = $api . $this->_format . '?url=' . $encodedURL;
			break;
		}
		return $endpoint;
	}
	
	//in case you'd want to convert an xml file to json... idk
	private function _XML_To_ARRAY($data) {
		$params = array();
		$xml_parser = xml_parser_create();
			
			xml_parse_into_struct($xml_parser, $data, $vals, $index);
			xml_parser_free($xml_parser);
			
			
			$level = array();
			foreach ($vals as $xml_elem) {
			  if ($xml_elem['type'] == 'open') {
			  	
			    if (array_key_exists('attributes',$xml_elem)) {
			      list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
			    } else {
			      $level[$xml_elem['level']] = $xml_elem['tag'];
			    }
				
			  }
			  
			  if ($xml_elem['type'] == 'complete') {
			    $start_level = 1;
			    $php_stmt = '$params';
				
			    while($start_level < $xml_elem['level']) {
			      $php_stmt .= '[$level['.$start_level.']]';
			      $start_level++;
			    }
				
			    $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
			    eval($php_stmt);
			  }
			 
			}
		 return $params;
	}
	
	//same deal... just xml to json
	private function _XML_To_JSON($data) {
		return json_encode(_XML_To_ARRAY($data));
	}
	
	private function _setResponse() {
		$data = @file_get_contents($this->_endpoint);
		$params = array();
		$params = $data;
		/*if ($this->_format=='xml') {
			$params = $this->_XML_To_JSON($data);
		} else {
			$params = $data;
		}*/
		
		$this->_response = $data;
	}
	
	
	//public methods
	public function setFormat($format) {
		$this->_format = strtolower($format);
		return $this;
	}
	
	public function setURL($url) {
		$this->_url = $url;
		return $this;
	}
	
	public function setProvider($provider) {
		$this->_provider = $provider;
		return $this;
	}
	
	public function execute() {
		$this->_endpoint = $this->_encode($this->_provider, $this->_url);
		$this->_setResponse();
		return $this;
	}
	
	public function get() {
		//return response
		return $this->_response;
	}
	
}


?>

