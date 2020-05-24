```php
public function onBulletHit(ProjectileHitEntityEvent $event): void {
    $bullet = $event->getEntity();
    $victim = $event->getEntityHit();
    $attacker = $bullet->getOwningEntity();

    if ($attacker instanceof Player) {
        $damage = \gun_system\controller\DamageController::calculateDamage($attacker,$victim);
        //$victim->setHealth($victim->getHealht()-$damage);
    }
}
```