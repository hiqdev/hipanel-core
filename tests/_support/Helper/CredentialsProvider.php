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
            'client.login', 'client.password',
            'seller.login', 'seller.password',
            'admin.login', 'admin.password',
        ];
    }

    public function getClientCredentials(): array
    {
        return [$this->config['client.login'], $this->config['client.password']];
    }


    public function getSellerCredentials(): array
    {
        return [$this->config['seller.login'], $this->config['seller.password']];
    }

    public function getAdminCredentials(): array
    {
        return [$this->config['admin.login'], $this->config['admin.password']];
    }
}
