<?php

namespace App\Security;

use App\Entity\Board;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BoardVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on Board objects inside this voter
        if (!$subject instanceof Board) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Board object, thanks to supports
        /** @var Board $board */
        $board = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($board, $user);
            case self::EDIT:
                return $this->canEdit($board, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Board $board, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($board, $user)) {
            return true;
        }

        // @todo Add collaborators
        // @todo Add private/public boards ?
        return false;

        // the Board object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$board->isPrivate();
    }

    private function canEdit(Board $board, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $board->getOwner();
    }
}
