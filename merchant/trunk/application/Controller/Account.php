<?php

namespace Application\Controller;

use System\Bootstrap;

class Account extends Front {

    public function __construct() {
        parent::__construct();

        if (! $this->auth->isLogin()) {
            $this->response->redirect(url('account/login'));
        }
    }

    /**
	 * 验证是否为商户
	 */
	protected function checkMerchantPermission()
	{
		return $this->user->type === 'merchant';
	}

	/**
	 * 验证是否为餐车经营者
	 */
	protected function checkManagerPermission()
	{
		return $this->user->type === 'manager';
	}

    protected function checkPermission()
    {
        return $this->user->type === 'merchant' || $this->user->role == 2;
    }
}