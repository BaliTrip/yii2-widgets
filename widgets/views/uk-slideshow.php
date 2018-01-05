<?php

use yii\helpers\Html;
use yii\helpers\Json;

\balitrip\uikit\assets\Slideshow::register($this);

?>
<div class="uk-slidenav-position" data-uk-slideshow='<?=Json::encode($options)?>' data-uk-check-display>
    <ul class="uk-slideshow">
	<?php foreach ($items as $key=>$item): ?>
		<li>
			<?php if (!empty($images[$key])): ?>
				<?= Html::img($images[$key], ['option' => 'value']); ?>
			<?php endif ?>
			<div class="uk-overlay-panel uk-overlay-background uk-flex uk-flex-center uk-flex-middle uk-text-center">
				<?php if ($this->context->slide_container): ?>
				<div class="uk-container uk-container-center">	
					<?=$item?>
				</div>
				<?php else: ?>
					<?=$item?>
				<?php endif ?>
			</div>
		</li>
	<?php endforeach ?>
    </ul>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
    <ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-flex-center">
	    <?php for ($i=0; $i < count($items); $i++): ?>
			<li data-uk-slideshow-item="<?=$i?>"><a href=""></a></li>
		<?php endfor ?>
    </ul>
</div>