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

		$reqData = $this->getContext()->getRequest()->getRequestData();

		if (!$this->isAuthenticated() && $reqData->hasCookie('autologon') )
		{
			/**
			 * @todo login by autologon
			 */
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

			throw new AgaviSecurityException($error['description'], $error['code']);
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
	 * Login this user
	 *
	 * Binds given model to the application user
	 */
	public function login(UserModel $user)
	{
		$this->user = $user;

		$this->setAttribute('login_at', new DateTime($this->user['login_at']), 'our.user');

		$this->user['login_at'] = date('Y-m-d H:i:s');
		$this->user->save();

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

	public function logout()
	{
		$this->clearCredentials();
		$this->setAuthenticated(false);
	}

	public function addFlash($message, $style = null)
	{
		$this->appendAttribute('messages', array($message, $style), 'our.flash');
	}


}

?>