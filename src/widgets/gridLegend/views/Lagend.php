<ul class="well well-sm list-unstyled"
    style="display: flex; justify-content: space-between; margin-bottom: .5em ; padding: .5em 1em">
    <?php foreach ($items as $item) : ?>
        <li>
            <i class="fa fa-square"
               style="color: <?= $this->context->getColor($item) ?>; padding-right: 0.5rem;"></i> <?= $this->context->getLabel($item) ?>
        </li>
    <?php endforeach; ?>
</ul>
