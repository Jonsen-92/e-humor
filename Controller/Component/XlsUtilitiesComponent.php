<?php
App::uses('Component', 'Controller');

/*
 * Created on May 9, 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 class XlsUtilitiesComponent extends Component {
 	 function _xlsBOF() {
		return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	}

	function _xlsEOF() {
		return pack("ss", 0x0A, 0x00);
	}

	function _xlsWriteNumber($Row, $Col, $Value) {
		return pack("sssss", 0x203, 14, $Row, $Col, 0x0).pack("d", $Value);
	}

	function _xlsWriteLabel($Row, $Col, $Value ) {
		$L = strlen($Value);
		return pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L).$Value;
	} 
 }
?>
