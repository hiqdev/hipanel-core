<li>
    <span
       onclick="change_skin('<?= $data['attribute']; ?>')"
       class="clearfix full-opacity-hover">
        <div>
            <span style="display:block; width: 20%; float: left; height: 10px;" class="<?= $data['bg']; ?>-active"></span>
            <span class="<?= $data['bg']; ?>" style="display:block; width: 80%; float: left; height: 10px; "></span>
        </div>
        <div>
            <span style="display:block; width: 20%; float: left; height: 40px; background: #222d32;"></span>
            <span style="display:block; width: 80%; float: left; height: 40px; background: #f4f5f7;"></span>
        </div>
        <p class="text-center"><?= $data['label']; ?></p>
    </span>
</li>