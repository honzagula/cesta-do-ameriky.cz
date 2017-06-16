<?php

namespace App\Model\User;

use App\Model\ORM\BaseDao;
use Doctrine\ORM\EntityNotFoundException;

class UserDao extends BaseDao
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(User::class);
    }

    /**
     * @param string $email
     * @param bool $active
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUserByEmail(string $email, bool $active = true)
    {
        $user = $this->getRepository()->findOneBy([
            'email' => $email,
            'active' => $active,
        ]);
        
        if ($user === null) {
            throw new EntityNotFoundException("User with e-mail `" . $email . "` not found.");
        }
        
        return $user;
    }
}
