<?php

/**
 * OurUser, the application user
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class OurUser extends AgaviRbacSecurityUser implements AgaviISecurityUser
{

	/**
	 * Current user
	 *
	 * @var		UserModel
	 */
	protected $user = null;

	/**
	 * Initialize this User.
	 *
	 * @param      AgaviContext An AgaviContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Harald Kirschner <mail@digitarald.de>
	 */
	public function initialize(AgaviContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);

		if (!$this->authenticated)
		{
			$this->roles = array('anonymous');
		}
	}

	public function startup()
	{
		parent::startup();

		$reqData = $this->context->getRequest()->getRequestData();

		if (!$this->isAuthenticated() && $reqData->hasCookie('remember') )
		{
			$token = $reqData->getCookie('remember');

			$response = $this->getContext()->getController()->getGlobalResponse();

			$table = Doctrine::getTable('UserModel');
			$user = $table->findOneByToken($token);

			if ($user)
			{
				$this->login($user);
				$response->setCookie('remember', $this->getToken(), AgaviConfig::get('core.remember_expire') );
			}
			else
			{
				// login didn't work. that cookie sucks, delete it.
				$response->setCookie('remember', false);
			}
		}
	}

	/**
	 * Get current user profile
	 *
	 * @return	UserModel
	 */
	public function getOpenIdRedirect($url)
	{
		$openid = new SimpleOpenID();

		$openid->SetIdentity($url);
		$openid->SetTrustRoot('http://' . $this->context->getRequest()->getUrlAuthority() );

		$openid->SetRequiredFields(array('nickname', 'email', 'fullname') );
		$openid->SetOptionalFields(array('dob', 'gender', 'postcode', 'country', 'language', 'timezone') );

		if (!$openid->GetOpenIDServer() )
		{
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
	 * @return	UserModel
	 */
	public function getProfile()
	{
		if (!$this->isAuthenticated() )
		{
			return null;
		}

		if (!$this->user && ($id = $this->getAttribute('id', 'our.user') ) )
		{
			$table = Doctrine::getTable('UserModel');

			$user = $table->findOneById($id);

			if (!$user)
			{
				return null;
			}

			$this->user = $user;
		}

		return $this->user;
	}

	/**
	 * Generates and saves a login token for later login.
	 *
	 * @param	UserModel Optional Model, defaults to current user
	 */
	public function getToken($user = null)
	{
		if (!$user)
		{
			$user = $this->user;
		}

		$token = new UserTokenModel();

		$token['user_id'] = $user['id'];
		$token['token'] = md5(AgaviToolkit::uniqid() );

		if (!$token->trySave() )
		{
			return null;
		}
		return $token['token'];
	}

	/**
	 * Login this user
	 *
	 * Binds given model to the application user
	 */
	public function login(UserModel $user)
	{
		$this->user = $user;

		$this->setAttribute('login_at', new DateTime($this->user['login_at']), 'our.user');

		$this->user['login_at'] = date('Y-m-d H:i:s');
		$this->user->trySave();

		$this->setAttribute('id', $this->user['id'], 'our.user');

		$this->clearCredentials();

		/**
		 * Set authentification and authorization
		 */
		$this->setAuthenticated(true);

		$this->grantRole('member');

		if ($this->user['role'])
		{
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
		$this->appendAttribute('messages', array($message, $style, $target), 'our.flash');
	}


}

?>