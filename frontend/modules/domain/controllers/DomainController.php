<?php

namespace frontend\modules\domain\controllers;

use frontend\modules\domain\models\DomainSearch;
use frontend\modules\domain\models\Domain;
use frontend\components\CrudController;
use yii\filters\VerbFilter;

class DomainController extends CrudController
{
    /**
     * All of security-aware methods are allowed only with POST requests
     *
     * @return array
     */
    public function behaviors () {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'set-note'          => ['post'],
                    'set-nss'           => ['post'],
                    'set-contacts'      => ['post'],
                    'set-whois-protect' => ['post'],
                    'set-autorenewal'   => ['post'],
                    'set-lock'          => ['post'],
                ],
            ],
        ];
    }

    static protected function mainModel     () { return Domain::className(); }
    static protected function searchModel   () { return DomainSearch::className(); }

    public function actionSetLock           () { return $this->_switch('lock'); }
    public function actionSetAutorenewal    () { return $this->_switch('autorenewal'); }
    public function actionSetWhoisProtect   () { return $this->_switch('whois-protect'); }

    public function _switch ($what) {
        return $this->performEditable(['scenario' => "set-$what", 'attributes' => ['id','enable']]);
    }

    public function actionSetNote () {
        return $this->performEditable(['scenario' => 'set-note', 'attributes' => ['id', 'note']]);
    }

}
