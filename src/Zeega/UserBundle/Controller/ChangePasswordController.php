<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zeega\UserBundle\Controller;

use FOS\UserBundle\Controller\ChangePasswordController as BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class ChangePasswordController extends BaseController
{
    /**
     * Change user password
     */
    public function changePasswordAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'change_password.flash.success');
        }
		
		
        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:ChangePassword:changePassword.html.'.$this->container->getParameter('fos_user.template.engine'),
                array('form' => $form->createView(), 'theme' => $this->container->getParameter('fos_user.template.theme'), 
				'email' => $user->getEmail(),
				'user_id' => $user->getId(),
				'displayname' => $user->getDisplayName(),
				'userrole' => $user->getRoles(),
				'bio'=>$user->getBio(),
				'thumb'=>$user->getThumbUrl(),
				'title'=>'',
				'page'=>'home',
				'projectsMenu'=>true,
				'myprojects'=>false,
				'site' => false,					
				'sites'=>false,
            )
        );
    }

}
