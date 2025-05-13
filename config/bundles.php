<?php

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use SymfonyCasts\Bundle\ResetPassword\SymfonyCastsResetPasswordBundle;

return [
    FrameworkBundle::class => ['all' => true],
    MakerBundle::class => ['dev' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    MonologBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    WebpackEncoreBundle::class => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    SymfonyCastsResetPasswordBundle::class => ['all' => true],
    DAMADoctrineTestBundle::class => ['test' => true],
    KnpPaginatorBundle::class => ['all' => true],
    StimulusBundle::class => ['all' => true],
];
