<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jackfruit
 * Date: 7/19/13
 * Time: 11:03 AM
 * To change this template use File | Settings | File Templates.
 */
// protected/components/FHtml.php       NOT RECOMMENDED!
class FHtml
{
    const
        SUBMIT_TYPE = 'submit';
    const
        BUTTON_CREATE = 'create',
        BUTTON_UPDATE = 'update',
        BUTTON_DELETE = 'delete',
        BUTTON_PROCESS = 'processing',
        BUTTON_PENDING = 'pending',
        BUTTON_RESET = 'reset',
        BUTTON_SEARCH = 'search',
        BUTTON_EDIT = 'edit',
        BUTTON_CANCEL = 'cancel',
        BUTTON_ADD = 'add',
        BUTTON_REMOVE = 'remove',
        BUTTON_SELECT = 'select',
        BUTTON_MOVE = 'move',
        BUTTON_RELOAD = 'reload',
        BUTTON_OK = 'ok',
        BUTTON_COPY = 'copy',
        BUTTON_ACCEPT = 'accept',
        BUTTON_REJECT = 'reject',
        BUTTON_APPROVED = 'approved',
        BUTTON_BACK = 'back',
        BUTTON_READ = 'read',
        BUTTON_UNREAD = 'unread',
        BUTTON_CONFIRM = 'confirm',
        BUTTON_COMPLETE = 'complete',
        BUTTON_REVERT = 'revert',
        BUTTON_SEND = 'send',
        BUTTON_SAVE = 'save';

    private static $buttonIcons = array(
        self::BUTTON_CREATE => 'fa fa-plus',
        self::BUTTON_SEARCH => 'fa fa-search',
        self::BUTTON_APPROVED => 'fa fa-check',
        self::BUTTON_UPDATE => 'fa fa-save',
        self::BUTTON_DELETE => 'fa fa-trash',
        self::BUTTON_RESET => 'fa fa-refresh',
        self::BUTTON_EDIT => 'fa fa-pencil',
        self::BUTTON_CANCEL => 'fa fa-cancel',
        self::BUTTON_COPY => 'fa fa-copy',
        self::BUTTON_ADD => 'fa fa-plus',
        self::BUTTON_REMOVE => 'fa fa-trash',
        self::BUTTON_SELECT => 'fa fa-share',
        self::BUTTON_MOVE => 'fa fa-move',
        self::BUTTON_OK => 'fa fa-ok',
        self::BUTTON_ACCEPT => 'fa fa-plus',
        self::BUTTON_REJECT => 'fa fa-lock',
        self::BUTTON_APPROVED => 'fa fa-ok-sign',
        self::BUTTON_BACK => 'fa fa-arrow-left',
        self::BUTTON_READ => 'fa fa-bookmark',
        self::BUTTON_UNREAD => 'fa fa-bookmark',
        self::BUTTON_CONFIRM => 'fa fa-signin',
        self::BUTTON_COMPLETE => 'fa fa-remove',
        self::BUTTON_REVERT => 'fa fa-share',
        self::BUTTON_SEND => 'm-fa fa-swapright',
        self::BUTTON_PROCESS => 'fa fa-play',
        self::BUTTON_PENDING => 'fa fa-pause',
        self::BUTTON_SAVE => 'fa fa-save',
    );

    public static function button($type, $style, $htmlOptions = array(), $isEditable = TRUE)
    { //
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return self::showEmpty();
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        if (!$isEditable)
            $htmlOptions['class'] .= ' disabled';
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        if (isset($htmlOptions['value'])) {
            $html .= '  ' . $htmlOptions['value'];
        } else {
            $html .= '  ' . self::buttonValue($style);
        }
        $html .= '</button>';
        return $html;
    }

    public static function showEmpty()
    { //
        $str = '<span style=" font-style: italic" class="text muted">' . Yii::t('common', 'title.empty') . '</span>';
        return $str;
    }

    public static function showEmptyResult()
    { //
        $str = '<span style=" font-style: italic" class="text muted">' . Yii::t('common', 'title.noResult') . '</span>';
        return $str;
    }

    public static function renderAttributes($attributes = array())
    {
        $html = "";
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html;
    }

    private static function buttonValue($style)
    {
        $lib = array(
            self::BUTTON_CREATE => Yii::t('common', 'button.create'),
            self::BUTTON_UPDATE => Yii::t('common', 'button.update'),
            self::BUTTON_DELETE => Yii::t('common', 'button.delete'),
            self::BUTTON_RESET => Yii::t('common', 'button.reset'),
            self::BUTTON_SEARCH => Yii::t('common', 'button.search'),
            self::BUTTON_EDIT => Yii::t('common', 'button.edit'),
            self::BUTTON_CANCEL => Yii::t('common', 'button.cancel'),
            self::BUTTON_COPY => Yii::t('common', 'button.copy'),
            self::BUTTON_ADD => Yii::t('common', 'button.add'),
            self::BUTTON_REMOVE => Yii::t('common', 'button.remove'),
            self::BUTTON_SELECT => Yii::t('common', 'button.select'),
            self::BUTTON_MOVE => Yii::t('common', 'button.move'),
            self::BUTTON_OK => Yii::t('common', 'button.ok'),
            self::BUTTON_ACCEPT => Yii::t('common', 'button.accept'),
            self::BUTTON_REJECT => Yii::t('common', 'button.reject'),
            self::BUTTON_APPROVED => Yii::t('common', 'button.approved'),
            self::BUTTON_BACK => Yii::t('common', 'button.back'),
            self::BUTTON_SEND => Yii::t('common', 'button.send'),
            self::BUTTON_READ => Yii::t('common', 'button.read'),
            self::BUTTON_UNREAD => Yii::t('common', 'button.unread'),
            self::BUTTON_CONFIRM => Yii::t('common', 'button.confirm'),
            self::BUTTON_COMPLETE => Yii::t('common', 'button.complete'),
            self::BUTTON_REVERT => Yii::t('common', 'button.revert'),
            self::BUTTON_PROCESS => Yii::t('common', 'button.processing'),
            self::BUTTON_PENDING => Yii::t('common', 'button.pending'),
            self::BUTTON_SAVE => Yii::t('common', 'button.save'),
        );
        return $lib[$style];
    }

    public static function dynamicButton($type, $style, $text, $htmlOptions = array())
    { //
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return self::showEmpty();
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        $html .= '  ' . $text;
        $html .= '</button>';
        return $html;
    }

    public static function buttonSubmit($style, $htmlOptions = array(), $isSmall = FALSE, $isEditable = TRUE, $isShowtext = TRUE)
    { //
        $type = self::SUBMIT_TYPE;
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return;
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        if ($isSmall)
            $htmlOptions['class'] .= ' mini';
        if (!$isEditable)
            $htmlOptions['class'] .= ' disabled';
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        if ($isShowtext)
            $html .= '  ' . self::buttonValue($style);
        $html .= '</button>';
        return $html;
    }

    public static function showLink($text = '', $htmlOptions = array(), $icon = '')
    { //
        $html = '<a ' . self::renderAttributes($htmlOptions) . '>';
        if (!empty($icon))
            $html .= '<i class="' . $icon . '"></i> ';
        $html .= $text;
        $html .= '</a>';
        return $html;
    }

    public static function showImage($short_path, $width = 100, $height = 100, $no_dimension = FALSE, $empty_no_image = FALSE)
    {
        //$filename without full path
        if (empty($short_path)) {
            if ($empty_no_image) return '';
            if ($no_dimension) {
                $src = 'http://www.placehold.it/300x300/EFEFEF/AAAAAA&amp;text=no+image';
            } else {
                $src = 'http://www.placehold.it/' . $width . 'x' . $height . '/EFEFEF/AAAAAA&amp;text=no+image';
            }
        } else {
            $path = Yii::getPathOfAlias('site') . DS . 'upload' . DS;
            $path .= $short_path;
            $path = str_replace('\\', '/', $path);
            if (!file_exists($path)) {
                if ($empty_no_image) return '';
                if ($no_dimension) {
                    $src = 'http://www.placehold.it/300x300/EFEFEF/AAAAAA&amp;text=no+image';
                } else {
                    $src = 'http://www.placehold.it/' . $width . 'x' . $height . '/EFEFEF/AAAAAA&amp;text=no+image';
                }
            } else {
                $path = Yii::app()->request->baseUrl . DS . 'upload' . DS . $short_path;
                $path = str_replace('\\', '/', $path);
                $src = $path;
            }
        }

        if ($no_dimension) {
            $str = '<img alt="" src="' . $src . '"';
        } else {
            $str = '<img alt="" width="' . $width . '" height="' . $height . '" src="' . $src . '"';
        }
        $str .= '/>';
        return $str;
    }
}