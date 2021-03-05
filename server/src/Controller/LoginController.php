<?php

namespace App\Controller;

use App\Service\AccountService;
use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1")
 *
 * Class LoginController
 * @package App\Controller
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        // Anyone can access this, because even if the user has bad credentials or expired cookies,
        // we make sure to remove them, the frontend will take that as a success and purge its store

        $old = new \DateTime();
        $old->setTimestamp(0);

        $iss_cookie = Cookie::create('iss_op')
            ->withValue('')
            ->withExpires($old)
            ->withDomain('hotspotdev.me')
            ->withHttpOnly();

        $token_cookie = Cookie::create('id_token')
            ->withValue('')
            ->withExpires($old)
            ->withDomain('hotspotdev.me')
            ->withHttpOnly();

        $response = new JsonResponse(['message' => 'ok']);
        $response->headers->setCookie($iss_cookie);
        $response->headers->setCookie($token_cookie);

/*        if (($firewallContext = trim($request->attributes->get("_firewall_context", null))) && (false !== ($firewallContextNameSplit = strrpos($firewallContext, ".")))) {
            $firewallName = substr($firewallContext, $firewallContextNameSplit + 1);
        }

        die($firewallName);*/
        return $response;
    }


    /**
     * @Route("/login/google/check_token", name="login_check_google", methods={"POST"})
     * @param Request $req
     * @param AccountService $accountService
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function loginCheckGoogleToken(Request $req, AccountService $accountService)
    {
        $body = $req->toArray();
        if (!array_key_exists('id_token', $body)) {
            return $this->json(['message' => 'missing data'], Response::HTTP_BAD_REQUEST);
        }

        $requested_role = $body['role'] ?? 'ROLE_USER';
        $id_token = $body['id_token'];

        $client = new Google_Client(['client_id' => getenv("GOOGLE_CLIENT_ID")]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);

        if (!$payload) return $this->json(['message' => 'bad token'], Response::HTTP_BAD_REQUEST);

        // The token can be trusted now
        // Let's keep the token in an httpOnly cookie
        $end_date = new \DateTime();
        $end_date->setTimestamp(intval($payload['exp']));
        $cookie_domain = getenv("COOKIE_DOMAIN");

        $iss_cookie = Cookie::create('iss_op')
            ->withValue('google')
            ->withExpires($end_date)
            ->withDomain($cookie_domain)
            ->withHttpOnly();

        $token_cookie = Cookie::create('id_token')
            ->withValue($id_token)
            ->withExpires($end_date)
            ->withDomain($cookie_domain)
            ->withHttpOnly();

        $user = [
            'email' => $payload['email'],
            'name' => $payload['name'],
            'given_name' => $payload['given_name'],
            'family_name' => $payload['family_name'],
            'exp' => $payload['exp'],
            'new_user' => $accountService->ensureAccountFromClaims($payload, $requested_role)
        ];

        $response = new JsonResponse($user);
        $response->headers->setCookie($iss_cookie);
        $response->headers->setCookie($token_cookie);
        return $response;

        /**
         * Example Google $payload
         * {
         *  "iss":"accounts.google.com",
         *  "azp":"<client-id>.apps.googleusercontent.com",
         *  "aud":"<client-id>.apps.googleusercontent.com",
         *  "sub":"103697281553102369", (edited but only numbers)
         *  "email":"skimpennyteam@gmail.com",
         *  "email_verified":true,
         *  "at_hash":"6CfBLd6oxmGFjdkUuzbxuw",
         *  "name":"Skim Team",
         *  "picture":"https:\/\/lh3.googleusercontent.com\/-tMbqhQzoucs\/AAAAAAAAAAI\/AAAAAAAAAAA\/AMZuucmSp9q2lEDqCvK2vNnytehymH1RXg\/s96-c\/photo.jpg",
         *  "given_name":"Skim",
         *  "family_name":"Team",
         *  "locale":"fr",
         *  "iat":1610315925,
         *  "exp":1610319525,
         *  "jti":"f70d198784024b177695cbf0bbde3783172f89f2"
         * }
         */

    }
}
