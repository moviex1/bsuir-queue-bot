<b>Список занятых мест на <i><?= $this->lessonDate ?></i>:</b>
<?php if (count($this->reserves)): ?>
    <?php foreach ($this->reserves as $reserve): ?>
<b><?= $reserve->getPlace() ?>.<?= $reserve->getUser()->getName() ?>(<?= $reserve->getUser()->getTgUsername() ?>) <?= $this->emojis[$reserve->getEmoji()]?></b>
    <?php endforeach; ?>
<?php else: ?>
<b>Все места свободны 🥺</b>
<?php endif; ?>
