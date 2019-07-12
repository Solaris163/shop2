<?php

foreach ($catalog as $item):?>

<h2><a href="/?c=product&a=card&id=<?=$item['id']?>"><?=$item['name']?></a></h2>
<p><?=$item['description']?></p>
<p>цена: <?=$item['price']?></p>

<?endforeach;?>