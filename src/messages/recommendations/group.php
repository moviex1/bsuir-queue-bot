<?php
if (count($this->students)): ?>
    <b>Список студентов группы <i><?= $this->students[0]->getGroup() ?></i>:</b>
    <?php
    foreach ($this->students as $student): ?>
        <b><?= $student->getId() ?>.<?= $student->getName() ?>(<?= $student->getTgUsername() ?>)</b>
        <?php
        if ($student->getGit()): ?>
            <b>- <a href="https://github.com/<?= $student->getGit() ?>"><?= $student->getGit()?></a> - Github пользователя</b>
        <?php
        endif; ?>
    <?php
    endforeach; ?>
<?php
else: ?>
    <b>В этой группе нету студентов😔. Введите другую группу, либо используйте комманду /cancel для отмены ввода.</b>
<?php
endif; ?>
