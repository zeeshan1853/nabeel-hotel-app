<?php

namespace api\components;

use yii\rest\UrlRule;

/**
 * Description of CUrlRule
 *
 * @author zeeshan
 */
class CUrlRule extends UrlRule {

    public $pluralize = false;
    
    public $tokens = [
        '{id}' => '<id:\\w+>'
    ];

}
