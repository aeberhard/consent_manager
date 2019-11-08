<?php
$showlist = true;
$pid = rex_request('pid', 'int', 0);
$func = rex_request('func', 'string');
$csrf = rex_csrf_token::factory('iwcc_text');
$clang_id = (int)str_replace('clang', '', rex_be_controller::getCurrentPagePart(3));
$table = rex::getTable('iwcc_text');
if ($func == 'delete')
{
    iwcc_clang::deleteDataset($table, $pid);
}
elseif ($func == 'add' || $func == 'edit')
{
    $formDebug = false;
    $showlist = false;
    $form = rex_form::factory($table, '', 'pid = ' . $pid, 'post', $formDebug);
    $form->addParam('pid', $pid);
    $form->addParam('sort', rex_request('sort', 'string', ''));
    $form->addParam('sorttype', rex_request('sorttype', 'string', ''));
    $form->addParam('start', rex_request('start', 'int', 0));
    $form->setApplyUrl(rex_url::currentBackendPage());
    $form->addHiddenField('clang_id', $clang_id);
    iwcc_rex_form::getId($form, $table);

    $form->addRawField(iwcc_rex_form::getFakeText($this->i18n('iwcc_uid'), $form->getSql()->getValue('uid')));
    $form->addHiddenField('uid', $form->getSql()->getValue('uid'));
    $field = $form->addTextAreaField('text');
    $field->setLabel($this->i18n('iwcc_text'));

    $title = $form->isEditMode() ? $this->i18n('iwcc_text_edit') : $this->i18n('iwcc_text_add');
    $content = $form->get();

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $title);
    $fragment->setVar('body', $content, false);
    echo $fragment->parse('core/page/section.php');
}

if ($showlist)
{
    $listDebug = false;
    $sql = 'SELECT pid,uid,text FROM ' . $table . ' WHERE clang_id = ' . $clang_id;

    $list = rex_list::factory($sql, 100, '', $listDebug);
    $list->addParam('page', rex_be_controller::getCurrentPage());
    $list->addTableAttribute('class', 'iwcc-table iwcc-table-text');

    $list->removeColumn('pid');

    $tdIcon = '<i class="fa fa-coffee"></i>';
    //$thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '"' . rex::getAccesskey(rex_i18n::msg('add'), 'add') . '><i class="rex-icon rex-icon-add"></i></a>';
    $list->addColumn('', $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams('', ['func' => 'edit', 'pid' => '###pid###']);

    $list->setColumnLabel('uid', $this->i18n('iwcc_uid'));
    $list->setColumnLabel('text', $this->i18n('iwcc_text'));

    $list->addColumn(rex_i18n::msg('function'), '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('edit'));
    $list->setColumnLayout(rex_i18n::msg('function'), ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('function'), ['pid' => '###pid###', 'func' => 'edit', 'start' => rex_request('start', 'string')]);

    $content = $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('title', $this->i18n('iwcc_cookies'));
    $fragment->setVar('content', $content, false);
    echo $fragment->parse('core/page/section.php');
}
