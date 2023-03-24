<b>Список занятых мест на <i><?= $this->date ?></i>:</b>
<?php if (count($this->reserves)): ?>
    <?php foreach ($this->reserves as $reserve): ?>
<b><?= $reserve['place'] ?>.<?= $reserve['username'] ?>(<?= $reserve['tg_username'] ?>) <?= $this->emojis[$reserve['emoji']]?></b>
    <?php endforeach; ?>
<?php else: ?>
<b>Все места свободны 🥺</b>
<?php endif; ?>
