<?php

/**
 * RedUser, the application user
 *
 * @package    redracer
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedUser extends AgaviRbacSecurityUser implements AgaviISecurityUser
{

	/**
	 * Current user
	 *
	 * @var		User
	 */
	protected $user = null;

	/**
	 * Startup the user.
	 *
	 * @author     Harald Kirschner <mail@digitarald.de>
	 */
	public function startup()
	{
		parent::startup();

		if (!$this->isAuthenticated()) {

			$this->grantRole('anonymous');

			$reqData = $this->context->getRequest()->getRequestData();

			if ($reqData->hasCookie('remember') ) {
				$response = $this->context->getController()->getGlobalResponse();

				$token = $reqData->getCookie('remember');
				$peer = $this->context->getModel('Users');
				$user = $peer->findOneByToken($token);

				if ($user) {
					$this->login($user);
					$response->setCookie('remember', $this->createToken(), AgaviConfig::get('org.redracer.config.account.autologin_lifetime') );
				} else {
					// login didn't work. that cookie sucks, delete it.
					$response->setCookie('remember', false);
				}
			}
		} else {
			$this->getProfile();
		}
	}

	/**
	 * Get current user profile
	 *
	 * @deprecated Old library
	 * @return	User
	 */
	public function getOpenIdRedirect($url)
	{
		$openid = new SimpleOpenID();

		$openid->SetIdentity($url);
		$openid->SetTrustRoot('http://' . $this->context->getRequest()->getUrlAuthority() );

		$openid->SetRequiredFields(array('nickname', 'email', 'fullname') );
		$openid->SetOptionalFields(array('dob', 'gender', 'postcode', 'country', 'language', 'timezone') );

		if (!$openid->GetOpenIDServer() ) {
			$error = $openid->GetError();
			throw new AgaviSecurityException($error['code'] . ': ' . $error['description']);
		}

		$openid->SetApprovedURL($this->context->getRouting()->gen('account.login_response', array(), array(
			'relative' => false
		) ) );

		return $openid->GetRedirectURL();
	}

	/**
	 * Get current user profile
	 *
	 * @return	User
	 */
	public function getProfile()
	{
		if (!$this->isAuthenticated() ) {
			return null;
		}

		if (!$this->user && ($id = $this->getAttribute('id', 'org.redracer.user') ) ) {
			$peer = $this->context->getModel('Users');
			$user = $peer->findOneById($id);

			if (!$user) {
				$this->logout();
				return null;
			}

			$this->user = $user;
		}

		return $this->user;
	}

	/**
	 * Generates and saves a login token for later login.
	 *
	 * @param      User Optional Model, defaults to current user
	 */
	public function createToken($user = null)
	{
		if (!$user) {
			$user = $this->user;
		}

		$token = new UserToken();
		$token['user_id'] = $user['id'];
		$token['token'] = md5(AgaviToolkit::uniqid() );

		if (!$token->trySave() ) {
			return null;
		}
		return $token['token'];
	}

	/**
	 * Login this user
	 *
	 * Binds given model to the application user
	 */
	public function login(User $user)
	{
		$this->user = $user;

		$this->setAttribute('login_at', new DateTime($this->user['login_at']), 'org.redracer.user');

		$this->user['login_at'] = date('Y-m-d H:i:s');
		$this->user->trySave();

		$this->setAttribute('id', $this->user['id'], 'org.redracer.user');

		$this->clearCredentials();

		/**
		 * Set authentification and authorization
		 */
		$this->setAuthenticated(true);

		$this->grantRole('member');

		if ($this->user['role']) {
			$this->grantRole($this->user['role']);
		}
	}

	/**
	 * Delete all authorisation and authentication.
	 */
	public function logout()
	{
		$this->clearCredentials();
		$this->setAuthenticated(false);
	}

	/**
	 * Appends a new flash message to the queue. Will be saved over redirects and printed
	 * when possible.
	 *
	 * @param	String The message, keep it short and escaped.
	 * @param	String The style (CSS-class to use). For example success, notice and error. Defaults to notice.
	 * @param	String An element ID for dynamic behaviour. The element will be highlighted if available, to make changes more visible.
	 */
	public function addFlash($message, $style = 'notice', $target = null)
	{
		$this->appendAttribute('messages', array($message, $style, $target), 'org.redracer.flash');
	}


}

?>