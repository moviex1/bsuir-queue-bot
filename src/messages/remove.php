<?php if($this->queue):?>
<b>Вы освободили <?=$this->queue->getPlace()?> место на <?= $this->queue->getLessonDate()->format('Y-m-d h:i:s')?> 👍</b>
<?php else :?>
<b>Вы не находитесь в очереди 🚫</b>
<?php endif;?>
