<?php if(count($this->recommendations)): ?>
<b>Список ваших рекомендаций😇:</b>
<?php $count = 1;?>
<?php foreach ($this->recommendations as $recommendation): ?>
<b><?= $count++?>.</b>
<b>------------</b>
    - <b><i><?= $recommendation->getRecommendation()?></i></b>
<b>------------</b>
<?php endforeach;?>
<?php else:?>
<b>К сожалению у вас пока что нету рекомендаций.😣</b>
<b>- Возможно вам стоит сдать следующую лабораторную чтобы получить рекомендацию😉</b>
<b>- Либо же у вас нету никаких проблем!😚</b>
<?php endif; ?>
