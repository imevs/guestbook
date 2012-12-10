<h2>Add Comment</h2>
             <? fdsfdas(); ?>
<? if ($errors): ?>
<? foreach ($errors as $error):?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Error!</strong> <?= $error?>
    </div>
<? endforeach;?>
<? endif; ?>

<form class="form-horizontal" method="POST" action="index.php?action=main">
    <div class="control-group">
        <label class="control-label" for="inputEmail">Email</label>
        <div class="controls">
            <input type="text" id="inputEmail" placeholder="Email" name="comment_author">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputComment">Comment</label>
        <div class="controls">
            <textarea rows="3" id="inputComment" placeholder="Comment" name="comment_text"></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Send</button>
        </div>
    </div>
</form>