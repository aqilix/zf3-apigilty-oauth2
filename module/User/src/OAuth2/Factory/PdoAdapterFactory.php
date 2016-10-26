<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace User\OAuth2\Factory;

use ZF\OAuth2\Factory\PdoAdapterFactory as ZFOAuth2PdoAdapterFactory;
use Interop\Container\ContainerInterface;
use ZF\MvcAuth\MvcAuthEvent;
use User\OAuth2\Adapter\PdoAdapter;
use ZF\OAuth2\Controller\Exception;

/**
 * Override PDOAdapterFactory
 *
 * @author dolly
 *
 */
class PdoAdapterFactory extends ZFOAuth2PdoAdapterFactory
{
    /**
     * (non-PHPdoc)
     * @see \ZF\OAuth2\Factory\PdoAdapterFactory::__invoke()
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        if (empty($config['zf-oauth2']['db'])) {
            throw new Exception\RuntimeException(
                'The database configuration [\'zf-oauth2\'][\'db\'] for OAuth2 is missing'
            );
        }

        $username = isset($config['zf-oauth2']['db']['username']) ? $config['zf-oauth2']['db']['username'] : null;
        $password = isset($config['zf-oauth2']['db']['password']) ? $config['zf-oauth2']['db']['password'] : null;
        $options  = isset($config['zf-oauth2']['db']['options']) ? $config['zf-oauth2']['db']['options'] : [];

        $oauth2ServerConfig = [];
        if (isset($config['zf-oauth2']['storage_settings'])
            && is_array($config['zf-oauth2']['storage_settings'])
        ) {
            $oauth2ServerConfig = $config['zf-oauth2']['storage_settings'];
        }

        $pdoAdapter = new PdoAdapter([
            'dsn'      => $config['zf-oauth2']['db']['dsn'],
            'username' => $username,
            'password' => $password,
            'options'  => $options,
        ], $oauth2ServerConfig);
        return $pdoAdapter;
    }
}
