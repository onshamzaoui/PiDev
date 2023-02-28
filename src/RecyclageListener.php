<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Recyclage;
use App\Entity\Don;

class RecyclageListener
{
    public function prePersist(Recyclage $recyclage, LifecycleEventArgs $event)
    {
        // Récupérer l'entité Don associée
        $don = $recyclage->getDon();
        
        // Si une entité Don est associée, la supprimer
        if ($don instanceof Don) {
            $em = $event->getEntityManager();
            $em->remove($don);
            $em->flush();
        }
    }
    
    public function preUpdate(Recyclage $recyclage, PreUpdateEventArgs $event)
    {
        // Récupérer l'entité Don associée
        $don = $recyclage->getDon();
        
        // Si une entité Don est associée, la supprimer
        if ($don instanceof Don) {
            $em = $event->getEntityManager();
            $em->remove($don);
            $em->flush();
        }
    }
}