<?php

class HomeController extends BaseController {


	public function home()
	{
		/*
		Mail::send('emails.auth.test', array('name' => 'hao'), function($message) {
			$message->to('yli@matchcode.com')->subject('Test Mail');
		});
		 */
		return View::make('home');
	}

}
