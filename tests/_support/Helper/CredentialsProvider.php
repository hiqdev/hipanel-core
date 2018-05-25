<?php
namespace hipanel\tests\_support\Helper;

use Codeception\Lib\ModuleContainer;
use Codeception\Module\WebDriver;

/**
 * Class CredentialsProvider
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class CredentialsProvider extends \Codeception\Module
{
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $this->requiredFields = [
            // Client
            'client.id', 'client.login', 'client.password',
            'seller.id', 'seller.login', 'seller.password',
            'admin.id', 'admin.login', 'admin.password',
        ];
    }

    public function getClientCredentials(): array
    {
        return [$this->config['client.id'], $this->config['client.login'], $this->config['client.password']];
    }


    public function getSellerCredentials(): array
    {
        return [$this->config['seller.id'], $this->config['seller.login'], $this->config['seller.password']];
    }

    public function getAdminCredentials(): array
    {
        return [$this->config['admin.id'], $this->config['admin.login'], $this->config['admin.password']];
    }

    public function restartBrowser()
    {
        /** @var WebDriver $wd */
        $wd = $this->getModule('WebDriver');
        $wd->_restart();
    }
}
