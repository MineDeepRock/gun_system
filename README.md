```php
public function onBulletHit(ProjectileHitEntityEvent $event): void {
    $bullet = $event->getEntity();
    $victim = $event->getEntityHit();
    $attacker = $bullet->getOwningEntity();

    if ($bullet instanceof \gun_system\pmmp\entities\BulletEntity && $attacker instanceof Player) {
        $damage = \gun_system\controller\DamageController::calculateDamage($attacker,$victim);
        //$victim->setHealth($victim->getHealht()-$damage);
    }
}
```
