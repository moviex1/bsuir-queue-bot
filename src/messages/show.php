<?php if($this->user):?>
<b>Вы занимаете <?= $this->user['place']?> место на <i><?= $this->user['date']?></i> 😋</b>
<?php else: ?>
<b>На данный момент вы не находитесь в очереди 😢</b>
<?php endif;?>