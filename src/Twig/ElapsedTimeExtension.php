<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

//filtre pour calculer depuis combien de temps un message est crée
class ElapsedTimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('elapsed_time', [$this, 'getElapsedTime']),
        ];
    }


    public function getElapsedTime(\DateTime $createdAt): string
    {
        $now = new \DateTime();
        $interval = $now->diff($createdAt);

        if ($interval->days >= 1) {
            return $createdAt->format("d/m/Y H:i");
        } elseif ($interval->h >= 1) {
            return $interval->h . " heure" . ($interval->h>1 ? "s" : "");
        } elseif ($interval->i >= 1) {
            return $interval->i. " minute" . ($interval->i>1 ? "s" : "");
        } else {
            return $interval->s . " seconde" . ($interval->s>1 ? "s" : "");;
        }
    }
}
