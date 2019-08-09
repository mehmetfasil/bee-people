<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhchatarchive/viewarchivedchat.tpl.php');

$archive = erLhcoreClassModelChatArchiveRange::fetch($Params['user_parameters']['archive_id']);
$archive->setTables();

$chat = erLhcoreClassModelChatArchive::fetch($Params['user_parameters']['chat_id']);

$tpl->set('chat',$chat);
$tpl->set('messages', erLhcoreClassChat::getList(array('limit' => 1000,'filter' => array('chat_id' => $chat->id)),'erLhcoreClassModelChatArchiveMsg',erLhcoreClassModelChatArchiveRange::$archiveMsgTable));
$tpl->set('archive',$archive);

$Result['content'] = $tpl->fetch();



$Result['path'] = array(
		array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('department/departments','System configuration')),
		array('url' => erLhcoreClassDesign::baseurl('chatarchive/archive'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatarchive/archive','Chat archive')),
		array('url' => erLhcoreClassDesign::baseurl('chatarchive/list'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatarchive/list','Archives list')),
		array('url' => erLhcoreClassDesign::baseurl('chatarchive/listarchivechats').'/'.$archive->id,'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatarchive/list','Archived chats')));
$Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatarchive/viewarchivedchat','View archived chat'));


?>