<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'index')]
final class IndexController extends AbstractController
{
    public function __invoke(): RedirectResponse
    {
        return $this->redirectToRoute('page', ['page' => 'index']);
    }
}
