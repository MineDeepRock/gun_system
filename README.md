銃のデータは [twitter](https://twitter.com/MineDeepRock) の`武器破壊`に書かれてる人によって作成されました。`
銃のプラグインちゃんと使いたい人とかいたら、詳しく説明します。
かなり昔の [映像](https://twitter.com/MineDeepRock/status/1290643188278005760?s=20) ですかまぁこんな感じです。

↓ここに操作方法とかのってます。
https://minedeeprock.github.io/GunServer

↓銃のデータです。これみれば追加方法とかわかります
https://github.com/MineDeepRock/gun_system_data

↓これを使った銃のソースコード
https://github.com/MineDeepRock/mine_deep_rock

↓テクスチャ
https://github.com/MineDeepRock/textures

↓グレネード
https://github.com/MineDeepRock/grenade_system

↓ガジェット
https://github.com/MineDeepRock/box_system

↓的だったかなー
https://github.com/MineDeepRock/sandbag_system


```php
public function onBulletHit(BulletHitEvent $event) {
    $attacker = $event->getAttacker();
    $victim = $event->getVictim();
    $damage = $event->getDamage();
   
    //$victim->setHealth(....
}
public function onBulletHitNear(BulletHitNEarEvent $event) {
    $attacker = $event->getAttacker();
    $victim = $event->getVictim();
    
    //敵味方判断してから
    //GunSystem::giveScare(..で制圧効果与える
}
```
