```php
public function onBulletHit(BulletHitEvent $event) {
    $attacker = $event->getAttacker();
    $victim = $event->getVictim();
    $damage = $event->getDamage();
}
public function onBulletHitNear(BulletHitNEarEvent $event) {
    $attacker = $event->getAttacker();
    $victim = $event->getVictim();
}
```
