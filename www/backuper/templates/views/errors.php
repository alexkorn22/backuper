<h3 class="text-center">Ошибки</h3>
<div class="alert alert-danger" role="alert">
    <ul>
        <? foreach ($errors as $error):?>
            <li><?=$error?></li>
        <?endforeach;?>
    </ul>

</div>
