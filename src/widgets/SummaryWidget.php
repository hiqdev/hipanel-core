<?php


namespace hipanel\widgets;


use Yii;
use yii\base\Widget;

/**
 * Class SummaryWidget
 * @package hipanel\modules\stock\widgets
 */
class SummaryWidget extends Widget
{
    /**
     * @var float[]|null
     */
    public $total_sums;

    /**
     * @var float[]|null
     */
    public $local_sums;

    /**
     * @inheritDoc
     */
    public function run()
    {
        $locals = $this->getSumsString($this->local_sums ?? []);
        $totals = $this->getSumsString($this->total_sums ?? []);
        return '<div class="summary">' .
            ($totals ? Yii::t('hipanel:stock', 'TOTAL: {sum}', ['sum' => $totals]) : null) .
            ($locals ? '<br><span class="text-muted">' . Yii::t('hipanel:stock', 'on screen: {sum}', ['sum' => $locals]) . '</span>' : null) .
            '</div>';
    }

    /**
     * @param array $sumsArray
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    private function getSumsString(array $sumsArray): string
    {
        $totals = '';
        foreach ($sumsArray as $cur => $sum) {
            if ($cur && $sum > 0) {
                $totals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
            }
        }
        return $totals;
    }
}
