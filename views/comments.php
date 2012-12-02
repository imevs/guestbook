<? if (isset($comments)): ?>
    <h2>Comments list </h2>
    <dl>
        <?foreach ($comments as $comment): ?>
            <dt><?= $comment['author']?></dt>
            <dd><?= $comment['text']?></dd>
        <?endforeach;?>
    </dl>
<? endif; ?>