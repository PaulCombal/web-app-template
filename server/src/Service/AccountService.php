<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserLogin;
use App\Entity\UserPreferences;
use Doctrine\ORM\EntityManagerInterface;

class AccountService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $claims
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function createAccountFromClaims($claims)
    {
        //Let's start with issuer-specific matters

        if (!array_key_exists('iss', $claims)) {
            throw new \Exception('No iss claim in token');
        }

        if (!array_key_exists('sub', $claims)) {
            throw new \Exception('No sub claim in token');
        }

        $user = null;
        $new_user = false;
        $op = null;

        // PHP8: str_contains
        // switch uses the == operator
        switch (true) {
            case strpos($claims['iss'], 'google'):
                $op = 'google';
                $user = $this->em->getRepository(User::class)->findOneBy(['google_sub' => $claims['sub']]) ?? null;
                if (!$user) {
                    $new_user = true;
                    $user = new User();
                    $user->setGoogleSub($claims['sub']);
                }
                break;

            case strpos($claims['iss'], 'apple'):
                $op = 'apple';
                throw new \Exception('Not implemented yet');
                break;

            default:
                throw new \Exception('Unknown issuer');
        }

        /**
         * Remember the login attempt
         */
        $log = new UserLogin($user, $op);
        $user->addUserLogin($log);
        $this->em->persist($log);


        if (!$new_user) {
            // maybe update a few things from claims idk

            $this->em->flush(); // save the login attempt
            return false;
        }


        /**
         * Example Google $payload
         * {
         *  "iss":"accounts.google.com",
         *  "azp":"<client-id>.apps.googleusercontent.com",
         *  "aud":"<client_id>.apps.googleusercontent.com",
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

        // This can be converted to a one-liner in PHP8: condition ?? throw Exception
        if (array_key_exists('email', $claims)) {
            $user->setEmail($claims['email']);
        }
        else {
            throw new \Exception('No email claim');
        }

        $user->setGivenName($claims['given_name'] ?? null);
        $user->setFamilyName($claims['family_name'] ?? null);
        $user->setPicture($claims['picture'] ?? null);
        $preferences = new UserPreferences($user);

        $this->em->persist($preferences);
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }
}