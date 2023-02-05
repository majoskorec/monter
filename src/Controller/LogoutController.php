<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logout', name: 'logout')]
final class LogoutController extends AbstractController
{
    public function __invoke(): void
    {
    }
}
