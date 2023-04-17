<?php if($this->queue):?>
<b>Вы занимаете <?= $this->queue->getPlace()?> место на <i><?= $this->queue->getLessonDate()->format("Y-m-d h:i:s")?></i> 😋</b>
<?php else: ?>
<b>На данный момент вы не находитесь в очереди 😢</b>
<?php endif;?>