<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'admin_index', methods: ['GET'])]
final class IndexController extends AbstractController
{
    public function __invoke(): RedirectResponse
    {
        return $this->redirectToRoute('admin_page_index');
    }
}
