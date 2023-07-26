<?php
App::uses('Component', 'Controller');
class MultiplePagingComponent extends Component{
	/**
	 * To get page of pagination with model. Use if you make multiple pagination in one page.
	 *
	 * Date Created  : 11 January 2011
	 * Last Modified : 11 January 2011
	 *
	 * @param string $model
	 * @return integer page
	 *
	 * @access private
	 * @author Rijal Asep Nugroho
	 * @since mtemplate 1.0 version
	 */
	function __pageForPagination($model) {
	    $page = 1;
	    $sameModel = isset($this->params['named']['model']) && $this->params['named']['model'] == $model;
	    $pageInUrl = isset($this->params['named']['page']);
	    if ($sameModel && $pageInUrl) {
	      $page = $this->params['named']['page'];
	    }

	    $this->passedArgs['page'] = $page;
	    return $page;
	}
}
