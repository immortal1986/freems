<?php
use Phalcon\Mvc\User\Component;


class Messenger extends Component
{
	/**
	 * @var array
	 **/
	public $classmap = array();

	/**
	 * Sets defaults for the class map (optional)
	 *
	 * @param array $classmap
	 **/
	public function __construct($classmap = array()) {

	// -- set the defaults
	$this->classmap = array(
		'error'   => 'flash_message-error',
		'success' => 'flash_message-success',
		'info'  => 'flash_message-info',
		'warning' => 'flash_message-warning'
	);

	// -- set new class map options (also optional)
	if (!empty($classmap)) {
		foreach ($classmap as $key => $value) {
			$this->classmap[$key] = $value;
		}
	}
}

	/**
	 * error(), success(), info(), warning()
	 * Sets the flash messages
	 *
	 * @param  string message
	 * @param  string position
	 * @return string
	 **/
	public function error($message, $position = '')
{
	$this->session->flashMessage = array(
		'position' => $position,
		'message' => '<div class="' . $this->classmap['error'] . '">
                 ' . $message . '
             </div>
        ');
}

	public function success($message, $position = '')
{
	$this->session->flashMessage = array(
		'position' => $position,
		'message' => '<div class="' . $this->classmap['success'] . '">
                 ' . $message . '
             </div>
        ');
}

	public function info($message, $position = '')
{
	$this->session->flashMessage = array(
		'position' => $position,
		'message' => '<div class="' . $this->classmap['info'] . '">
                 ' . $message . '
             </div>
        ');
}

	public function warning($message, $position = '')
{
	$this->session->flashMessage = array(
		'position' => $position,
		'message' => '<div class="' . $this->classmap['warning'] . '">
                 ' . $message . '
             </div>
        ');
}

	/**
	 * Check if theres messages in the session to render
	 *
	 * @param  string  $position
	 * @return bool
	 **/
	public function hasMessage($position = null)
{
	if (isset($this->session->flashMessage) && !empty($position)) {
		return $this->session->flashMessage['position'] == $position ? true : false ;
	} else {
		return $this->session->flashMessage ? true : false ;
	}
}

	/**
	 * Renders the flash message
	 *
	 * @param  string  $position
	 * @return string
	 **/
	public function render($position = null)
{
	// -- store the message locally
	$message = $this->session->flashMessage;

	// --  check if there is in fact a flashed message
	if (empty($message))
		return;

	// -- then remove from the session
	$this->session->remove('FlashMessage');

	// -- if no position the just return the message
	if (is_null($position)) {

		return $message['message'];

		// --  else return the requested position
	} elseif ($position == $message['position']) {

		return $message['message'];
	}
}
}