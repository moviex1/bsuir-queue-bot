<?php if($this->user):?>
<b>Вы освободили <?=$this->user['place']?> место на <?= $this->user['date']?> 👍</b>
<?php else :?>
<b>Вы не находитесь в очереди 🚫</b>
<?php endif;?>
