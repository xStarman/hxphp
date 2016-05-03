<?php

namespace HXPHP\System\Helpers\Menu;

class RealLink
{
	/**
	 * Retorna o link com os coringas preenchidos
	 * @param  string $value Link 
	 * @return string        Link tratado
	 */
	public function get($value)
	{
		$value = str_replace(
			array('% %', '%/'),
			array('%%', '%'),
			$value
		);

		return str_replace(
			array(
				'%siteURL%',
				'%site_URL',
				'%site_url',
				'%baseURI%',
				'%base_uri%'
			), 
			array(
				$this->configs->site->url . $this->configs->baseURI,
				$this->configs->site->url . $this->configs->baseURI,
				$this->configs->site->url . $this->configs->baseURI,
				$this->configs->baseURI,
				$this->configs->baseURI
			),
			$value
		);
	}
}