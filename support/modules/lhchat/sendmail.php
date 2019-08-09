<?php

// Set new chat owner
$currentUser = erLhcoreClassUser::instance();
$currentUser->getUserID();
$chat = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', $Params['user_parameters']['chat_id']);

// Chat can be closed only by owner
if ( erLhcoreClassChat::hasAccessToRead($chat) )
{
  $tpl = erLhcoreClassTemplate::getInstance('lhchat/sendmail.tpl.php');
  $mailTemplate = erLhAbstractModelEmailTemplate::fetch(1);
  erLhcoreClassChatMail::prepareSendMail($mailTemplate);
  $mailTemplate->recipient = $chat->email;

  if (isset($_POST['SendMail'])) {

	  	$Errors = erLhcoreClassChatMail::validateSendMail($mailTemplate, $chat);

	  	if (count($Errors) == 0) {
	  		erLhcoreClassChatMail::sendMail($mailTemplate, $chat);

	  		// Set as mail send only if recipient is the same as chat user
	  		if ($chat->email == $mailTemplate->recipient) {
	  			$chat->mail_send = 1;
	  			$chat->saveThis();
	  		}

	  		$tpl->set('message_saved',true);
	  	} else {
	  		$tpl->set('errors',$Errors);
	  	}
  }

  $tpl->set('mail_template',$mailTemplate);
  $tpl->set('chat',$chat);
  $Result['content'] = $tpl->fetch();
  $Result['pagelayout'] = 'popup';

} else {
	exit;
}

?>