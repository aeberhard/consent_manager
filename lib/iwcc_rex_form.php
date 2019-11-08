<?php

class iwcc_rex_form
{

    public static function getFakeText($label, $value)
    {
        $html = '';
        $html .= '<dl class="rex-form-group form-group iwcc-fake">';
        $html .= '<dt><label class="control-label">' . $label . '</label></dt>';
        $html .= '<dd><input disabled class="form-control" type="text" value="' . $value . '"></dd>';
        $html .= '</dl>';
        return $html;
    }

    public static function getFakeTextarea($label, $value)
    {
        $html = '';
        $html .= '<dl class="rex-form-group form-group iwcc-fake">';
        $html .= '<dt><label class="control-label">' . $label . '</label></dt>';
        $html .= '<dd><textarea disabled class="form-control" rows="6">' . $value . '</textarea></dd>';
        $html .= '</dl>';
        return $html;
    }

    public static function getFakeCheckbox($label, $checkboxes)
    {
        $html = '';
        $html .= '<dl class="rex-form-group form-group iwcc-fake">';
        if ($label) {
            $html .= '<dt><label class="control-label">'.$label.'</label></dt>';
        }
        $html .= '<dd>';
        foreach ($checkboxes as $v) {
            $checked = $v[0] == '|1|' ? 'checked' : '';
            $html .= '<div class="checkbox"><label class="control-label"><input type="checkbox" disabled '.$checked.'>'.$v[1].'</label></div>';
        }
        $html .= '</dd>';
        $html .= '</dl>';
        return $html;
    }

    public static function getId(&$form, $table) {
        if (!$form->isEditMode()) {
            $db = rex_sql::factory();
            $db->setTable($table);
            $form->addHiddenField('id', $db->setNewId('id', 1));
        }
        else {
            $form->addHiddenField('id', $form->getSql()->getValue('id'));
        }
    }

    public static function removeDeleteButton(rex_extension_point $ep)
    {
        $subject = $ep->getSubject();
        $subject['delete'] = '';
        $ep->setSubject($subject);
    }

    public static function showInfo($msg)
    {
        return '<div class="iwcc-rex-form-info"><i class="fa fa-info-circle"></i>' . $msg . '</div>';
    }

    public static function validateHostname($hostname)
    {
        return filter_var($hostname, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    public static function formatCookiegroupPrioLabel($groupId)
    {
        $db = rex_sql::factory();
        $db->setTable(rex::getTable('iwcc_cookiegroup'));
        $db->setWhere('id=' . $groupId);
        $db->select('uid,domain');
        $group = $db->getArray()[0];
        if ($group['domain']) {
            $db = rex_sql::factory();
            $db->setTable(rex::getTable('iwcc_domain'));
            $db->setWhere('id=' . $group['domain']);
            $db->select('uid');
            $domain = $db->getArray();
            if ($domain) {
                return $group['uid'].' ['.$domain[0]['uid'].']';
            }
        }
        return $group['uid'];
    }

}