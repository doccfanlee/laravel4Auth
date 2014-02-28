<?php

class AccountController extends BaseController {

	public function getSignIn() {

		return View::make('account.signin');
	}

	public function postSignIn() {
		$validator = Validator::make(Input::all(),
		
			array(
				'email' => 'required|max:50|email',
				'password' => 'required|min:6',
			)
		);

		if($validator->fails()) {
			return Redirect::route('account-sign-in')->withErrors($validator)->withInput();
		}else{
			//Attempt user sign in
			$auth = Auth::attempt( array(
					'email' => Input::get('email'),
					'password' => Input::get('password'),
					'active' => 1
				));

			if($auth) {

				return Redirect::intended('/');
			} else {
				
				return Redirect::route('account-sign-in')
					->with('global', 'Email/password wrong. or account are not activated');
			}
		}


		return Redirect::route('account-sign-in')
			->with('global', 'There was a problem signing you in.');
	}
	public function getCreate() {

		return View::make('account.create');
	}

	public function postCreate() {

		$validator = Validator::make(Input::all(),
		
			array(
				'email' => 'required|max:50|email|unique:users',
				'username' => 'required|max:20|min:3|unique:users',
				'password' => 'required|min:6',
				'password_again' => 'required|same:password'
			)
		);

		if($validator->fails()) {
			return Redirect::route('account-create')->withErrors($validator)->withInput();
		}else{

			$email = Input::get('email');
			$username = Input::get('username');
			$password = Input::get('password');

			//Activation code
			$code = str_random(60);

			$create = User::create(array(
				'email' => $email,
				'username' => $username,
				'password' => Hash::make($password),
				'code' => $code,
				'active' => 0
			));

			if($create) {
				// Send Email
				Mail::send('emails.auth.active', array('link' => URL::route('account-active', $code), 'username' => $username), function($message) use ($create) {
				$message->to($create->email, $create->username)->subject('Active your account');
				});
				return Redirect::route('home')
					->with('global', 'Your account has been created. We have sent your an Email.');
			}
		}
		return 'hello create account post';

	}

	public function getActive($code) {
		$user = User::where('code', '=', $code)->where('active', '=', 0);

		if($user->count()){
			$user = $user->first();

			$user->active = 1;
			$user ->code = '';

			if($user->save()) {

				return Redirect::route('home')
					->with('global', 'Activated! You can now sign in');

			}
		}

		return Redirect::route('home')
			->with('global', 'We could not active your account, try again later.');

	}
}
