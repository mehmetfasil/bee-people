<?php


$tpl = erLhcoreClassTemplate::getInstance('lhchat/adminchat.tpl.php');

$chat = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', $Params['user_parameters']['chat_id']);
$tpl->set('chat',$chat);

if ( erLhcoreClassChat::hasAccessToRead($chat) )
{
	
	$userData = $currentUser->getUserData();
	
	if ($Params['user_parameters_unordered']['remember'] == 'true') {
		CSCacheAPC::getMem()->appendToArray('lhc_open_chats',$chat->id);
	}
	
	if ($userData->invisible_mode == 0) {	
		
		$operatorAccepted = false;
		$chatDataChanged = false;
		
	    if ($chat->user_id == 0) {
	        $currentUser = erLhcoreClassUser::instance();
	        $chat->user_id = $currentUser->getUserID();	     
	        $chatDataChanged = true;
	    }
	    
	    // If status is pending change status to active
	    if ($chat->status == erLhcoreClassModelChat::STATUS_PENDING_CHAT) {
	    	$chat->status = erLhcoreClassModelChat::STATUS_ACTIVE_CHAT;
	    	
	    	if ($chat->wait_time == 0) {
	    		$chat->wait_time = time() - $chat->time;
	    	}
	    	
	    	$chat->user_id = $currentUser->getUserID();
	    	$operatorAccepted = true;
	    	$chatDataChanged = true;
	    }
	    
	    if ($chat->support_informed == 0 || $chat->has_unread_messages == 1 ||  $chat->unread_messages_informed == 1) {
	    	$chatDataChanged = true;
	    }
	    
	    // Store who has acceped a chat so other operators will be able easily indicate this
	    if ($operatorAccepted == true) {
	    	         	        
	        $msg = new erLhcoreClassModelmsg();
	        $msg->msg = (string)$currentUser->getUserData(true)->name_support.' '.erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','has accepted the chat!');
	        $msg->chat_id = $chat->id;
	        $msg->user_id = -1;
	        $msg->time = time();
	        	       
	        if ($chat->last_msg_id < $msg->id) {
	            $chat->last_msg_id = $msg->id;
	        }

	        erLhcoreClassChat::getSession()->save($msg);
	    }
	    
	    // Update general chat attributes
	    $chat->support_informed = 1;
	    $chat->has_unread_messages = 0;
	    $chat->unread_messages_informed = 0;

	    if ($chat->unanswered_chat == 1 && $chat->user_status == erLhcoreClassModelChat::USER_STATUS_JOINED_CHAT)
	    {
	        $chat->unanswered_chat = 0;
	    }

	    erLhcoreClassChat::getSession()->update($chat);
		
	    echo $tpl->fetch();	  
	    flush();	    	    
	    session_write_close();	  
		
	    if ( function_exists('fastcgi_finish_request') ) {
	    	fastcgi_finish_request();
	    };
	    
	    if ($chatDataChanged == true) {
	    	erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.data_changed',array('chat' => & $chat,'user' => $currentUser));
	    }
	    
	    if ($operatorAccepted == true) {	 	    	
	    	erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.accept',array('chat' => & $chat,'user' => $currentUser));	    	
	    	erLhcoreClassChat::updateActiveChats($chat->user_id);	

	    	if ($chat->department !== false) {
	    	    erLhcoreClassChat::updateDepartmentStats($chat->department);
	    	}
	    	
	    	erLhcoreClassChatWorkflow::presendCannedMsg($chat);
	    	$options = $chat->department->inform_options_array;
	    	erLhcoreClassChatWorkflow::chatAcceptedWorkflow(array('department' => $chat->department,'options' => $options),$chat);
	    };
	    exit;	    
	}
    
	echo $tpl->fetch();
	exit;    

} else {
    $tpl->setFile( 'lhchat/errors/adminchatnopermission.tpl.php');
    $tpl->set('show_close_button',true);
    echo $tpl->fetch();
    exit;
}



?>