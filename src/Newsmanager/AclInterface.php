<?php
namespace Appeldorff\Newsmanager;

interface AclInterface
{
    const READ = 'read';
    const WRITE = 'write';
    const DELETE = 'delete';
    /**
     * Has the user permissions
     *
     * @param UserInterface $user
     * @param string $type
     * @param NewsItemInterface $entity
     * @return bool
     */
    public function hasPermission(UserInterface $user, $type = self::READ, NewsItemInterface $entity);
}