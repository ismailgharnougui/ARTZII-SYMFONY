<?php

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserService{
    public function hashPassword(UserPasswordEncoderInterface $passwordEncoder, $plainPassword)
{
    $user = new Utilisateur('username', $plainPassword, ['ROLE_USER']);
    $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
    return $encodedPassword;
}

}