<?php

/*namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class CustomAuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    private $authorizationChecker;

    public function __construct(HttpUtils $httpUtils, array $options = [], AuthorizationCheckerInterface $authorizationChecker = null)
    {
        $this->authorizationChecker = $authorizationChecker;
        parent::__construct($httpUtils, $options);
    }

    protected function determineTargetUrl(Request $request)
    {
        // Redirect to the wanted page
        if (null !== $this->providerKey && $targetUrl = $request->getSession()->get('_security.'.$this->providerKey.'.target_path')) {
            $request->getSession()->remove('_security.'.$this->providerKey.'.target_path');

            return $targetUrl;
        }

          // My redirection logic
        if ($this->authorizationChecker->isGranted('ROLE_FOOBAR') {
            return $this->httpUtils->generateUri($request, 'foobar');
        }

        // Default behavior
        return parent::determineTargetUrl($request);
    }
}*/
