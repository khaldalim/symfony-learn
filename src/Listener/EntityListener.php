<?php


namespace App\Listener;


use App\Entity\Tag;
use App\Entity\Topic;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class EntityListener
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Topic) {
            $slug = $this->slugger->slug($entity->getName());
            $entity->setSlug($slug);
        }

        if ($entity instanceof Tag) {
            $slug = $this->slugger->slug($entity->getText());
            $entity->setSlug($slug);
        }

    }
}
