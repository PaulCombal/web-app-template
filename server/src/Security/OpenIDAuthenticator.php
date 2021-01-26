<?php
namespace App\Security;

use App\Repository\UserRepository;
use Google_Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class OpenIDAuthenticator extends AbstractAuthenticator
{

    private $userRepository;
    public function __construct(UserRepository $repo) {
        $this->userRepository = $repo;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->cookies->has('id_token') and $request->cookies->has('iss_op');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $apiToken = $request->cookies->get('id_token');
        $op = $request->cookies->get('iss_op');
        if (!$apiToken or !$op) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $email = null;
        switch ($op) {
            case 'google':
                try {
                    $client = new Google_Client(['client_id' => getenv("GOOGLE_CLIENT_ID")]);  // Specify the CLIENT_ID of the app that accesses the backend
                    $claims = $client->verifyIdToken($apiToken); // false or assoc
                } catch (\Throwable $e_removewithphp8) {
                    throw new CustomUserMessageAuthenticationException('Error validating Google token');
                }

                if ($claims === false) {
                    throw new CustomUserMessageAuthenticationException('Could not verify Google access token');
                }

                if (!$sub = $claims['sub'] ?? null) {
                    throw new CustomUserMessageAuthenticationException('Invalid Google token');
                }

                return new SelfValidatingPassport(new UserBadge($sub, function ($sub) {
                    return $this->userRepository->findOneBy(['google_sub' => $sub]);
                }));

            default:
                throw new CustomUserMessageAuthenticationException('Unknown OP');
        }

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
