<?php
class Breadcrumbs extends CWidget
{
    public $links = array();
    public $homeLink;
    public $encodeLabel = true;
    public $activeLinkTemplate = '<a href="{url}">{label}</a>';
    public $inactiveLinkTemplate = '<span>{label}</span>';

    public function run()
    {
        if (empty($this->links))
            return;

        $links = array();
        if ($this->homeLink === null)
            $links[] = CHtml::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl);
        elseif ($this->homeLink !== false)
            $links[] = $this->homeLink;
        foreach ($this->links as $label => $url) {
            if (is_string($label) || is_array($url))
                $links[] = strtr($this->activeLinkTemplate, array(
                    '{url}' => CHtml::normalizeUrl($url),
                    '{label}' => $this->encodeLabel ? CHtml::encode($label) : $label,
                ));
            else
                $links[] = str_replace('{label}', $this->encodeLabel ? CHtml::encode($url) : $url, $this->inactiveLinkTemplate);
        }

        $this->render('breadcrumbs',
            array('links' => $links)
        );
    }
}

?>