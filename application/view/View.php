<?php

namespace FrontController\application\view;

class View {
	private $templateFile;
	public function __construct($templateFile) {
		$this->templateFile = $templateFile;
	}
	public function render() {
		if (file_exists ( $this->templateFile )) {
			include $this->templateFile;
		} else {
			throw new \Exception ( 'no template file ' . $this->templateFile . ' available' );
		}
	}
}