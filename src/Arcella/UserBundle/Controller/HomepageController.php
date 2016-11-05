<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * The HomepageController is just a temporary solution until the real contents
 * are coming to Arcella...
 */
class HomepageController extends Controller
{
    /**
     * Just the static homepage.
     *
     * @Route("/", name="homepage")
     * @Method({"GET"})
     *
     * @return Response The http-response
     */
    public function homepageAction()
    {
        return $this->render('default/index.html.twig');
    }
}
