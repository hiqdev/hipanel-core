<?php
namespace hipanel\tests\_support\Helper;

use Codeception\Lib\ModuleContainer;

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
            'client.login', 'client.password', 'client.id',
            'seller.login', 'seller.password', 'seller.id',
            'admin.login', 'admin.password', 'admin.id',
        ];
    }

    public function getClientCredentials(): array
    {
        return [$this->config['client.login'], $this->config['client.password'], $this->config['client.id']];
    }


    public function getSellerCredentials(): array
    {
        return [$this->config['seller.login'], $this->config['seller.password'], $this->config['seller.id']];
    }

    public function getAdminCredentials(): array
    {
        return [$this->config['admin.login'], $this->config['admin.password'], $this->config['admin.id']];
    }
}
