<?php

/*
 * Gumroad OAuth2 Provider
 * (c) alofoxx
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alofoxx\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class GumroadResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * @var array
     */
    protected $response;

    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'user_id');
    }

    /**
     * Returns email address of the resource owner
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Returns full name of the resource owner
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getValueByKey($this->response, 'name');
    }

    /**
     * Returns Gumroad bio of the resource owner
     *
     * @return string|null
     */
    public function getBio()
    {
        return $this->getValueByKey($this->response, 'bio');
    }

    /**
     * Returns identities of the resource owner
     *
     * @see https://auth0.com/docs/user-profile/user-profile-structure
     * @return array|null
     */
    public function getIdentities()
    {
        return $this->getValueByKey($this->response, 'identities');
    }

    /**
     * Returns facebook profile of the resource owner
     *
     * @return string|null
     */
    public function getFacebookProfile()
    {
        return $this->getValueByKey($this->response, 'facebook_profile');
    }

    /**
     * Returns twitter handle of the resource owner
     *
     * @return string|null
     */
    public function getTwitterHandle()
    {
        return $this->getValueByKey($this->response, 'twitter_handle');
    }

    /**
     * Returns gumroad profile url of the resource owner
     *
     * @return string|null
     */
    public function getProfileUrl()
    {
        return $this->getValueByKey($this->response, 'url');
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->response;
    }
}
